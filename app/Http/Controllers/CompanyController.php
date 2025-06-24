<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\GeneralSetting;
use App\Models\Company;
use App\Models\CompanySubcription;
use App\Models\Industry;
use App\Models\Package;
use App\Models\UserActivity;
use App\Services\SiteAuthService;
use App\Helpers\Helper;
use Auth;
use Session;
use Hash;
use DB;
use DateTime;

class CompanyController extends Controller
{
    protected $siteAuthService;
    public function __construct()
    {
        $this->siteAuthService = new SiteAuthService();
        $this->data = array(
            'title'             => 'Company',
            'controller'        => 'CompanyController',
            'controller_route'  => 'company',
            'primary_key'       => 'id',
            'table_name'        => 'companies',
        );
    }
    /* list */
        public function list(){
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' List';
            $page_name                      = 'company.list';
            $data                           = $this->siteAuthService ->admin_after_login_layout($title,$page_name,$data);
            return view('maincontents.' . $page_name, $data);
        }
    /* list */
    /* add */
        public function add(Request $request){
            $data['module']           = $this->data;
            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'name'              => 'required',
                    'phone'             => 'required',
                    'email'             => 'required',
                    'address'           => 'required',
                    'contact_person'    => 'required',
                    'description'       => 'required',
                    'industry_id'       => 'required',
                    'no_of_employee'    => 'required',
                    'logo'              => 'required',
                ];
                if($this->validate($request, $rules)){
                    /* user activity */
                        $activityData = [
                            'user_email'        => session('user_data')['email'],
                            'user_name'         => session('user_data')['name'],
                            'user_type'         => 'ADMIN',
                            'ip_address'        => $request->ip(),
                            'activity_type'     => 3,
                            'activity_details'  => $postData['name'] . ' ' . $this->data['title'] . ' Added',
                            'platform_type'     => 'WEB',
                        ];
                        UserActivity::insert($activityData);
                    /* user activity */
                    /* logo */
                        $upload_folder = 'company';
                        $imageFile      = $request->file('logo');
                        if($imageFile != ''){
                            $imageName      = $imageFile->getClientOriginalName();
                            $uploadedFile   = $this->upload_single_file('logo', $imageName, $upload_folder, 'image');
                            if($uploadedFile['status']){
                                $logo = $uploadedFile['newFilename'];
                            } else {
                                return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                            }
                        } else {
                            return redirect()->back()->with(['error_message' => 'Please Upload ' . $this->data['title'] . ' Logo']);
                        }
                    /* logo */
                    $fields = [
                        'name'                  => strip_tags($postData['name']),
                        'phone'                 => strip_tags($postData['phone']),
                        'alternate_phone'       => strip_tags($postData['alternate_phone']),
                        'email'                 => strip_tags($postData['email']),
                        'alternate_email'       => strip_tags($postData['alternate_email']),
                        'address'               => strip_tags($postData['address']),
                        'contact_person'        => strip_tags($postData['contact_person']),
                        'description'           => strip_tags($postData['description']),
                        'industry_id'           => strip_tags($postData['industry_id']),
                        'no_of_employee'        => strip_tags($postData['no_of_employee']),
                        'logo'                  => 'uploads/' . $upload_folder . '/' . $logo,
                        'status'                => ((array_key_exists("status",$postData))?1:0),
                    ];
                    Company::insert($fields);
                    return redirect($this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Inserted Successfully !!!');
                } else {
                    return redirect()->back()->with('error_message', 'All Fields Required !!!');
                }
            }
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' Add';
            $page_name                      = 'company.add-edit';
            $data['row']                    = [];
            $data['industries']             = Industry::where('status', '=', 1)->get();
            $data                           = $this->siteAuthService ->admin_after_login_layout($title,$page_name,$data);
            return view('maincontents.' . $page_name, $data);
        }
    /* add */
    /* edit */
        public function edit(Request $request, $id){
            $data['module']                 = $this->data;
            $id                             = Helper::decoded($id);
            $title                          = $this->data['title'].' Update';
            $page_name                      = 'company.add-edit';
            $data['row']                    = Company::where('id', '=', $id)->first();
            $data['industries']             = Industry::where('status', '=', 1)->get();
            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'name'              => 'required',
                    'phone'             => 'required',
                    'email'             => 'required',
                    'address'           => 'required',
                    'contact_person'    => 'required',
                    'description'       => 'required',
                    'industry_id'       => 'required',
                    'no_of_employee'    => 'required',
                ];
                if($this->validate($request, $rules)){
                    /* logo */
                        $upload_folder = 'company';
                        $imageFile      = $request->file('logo');
                        if($imageFile != ''){
                            $imageName      = $imageFile->getClientOriginalName();
                            $uploadedFile   = $this->upload_single_file('logo', $imageName, $upload_folder, 'image');
                            if($uploadedFile['status']){
                                $logo = $uploadedFile['newFilename'];
                                $logoLink = 'uploads/' . $upload_folder . '/' . $logo;
                            } else {
                                return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                            }
                        } else {
                            $logo = $data['row']->logo;
                            $logoLink = $logo;
                        }
                    /* logo */
                    $fields = [
                        'name'                  => strip_tags($postData['name']),
                        'phone'                 => strip_tags($postData['phone']),
                        'alternate_phone'       => strip_tags($postData['alternate_phone']),
                        'email'                 => strip_tags($postData['email']),
                        'alternate_email'       => strip_tags($postData['alternate_email']),
                        'address'               => strip_tags($postData['address']),
                        'contact_person'        => strip_tags($postData['contact_person']),
                        'description'           => strip_tags($postData['description']),
                        'industry_id'           => strip_tags($postData['industry_id']),
                        'no_of_employee'        => strip_tags($postData['no_of_employee']),
                        'logo'                  => $logoLink,
                        'status'                => ((array_key_exists("status",$postData))?1:0),
                    ];
                    Company::where($this->data['primary_key'], '=', $id)->update($fields);
                    /* user activity */
                        $activityData = [
                            'user_email'        => session('user_data')['email'],
                            'user_name'         => session('user_data')['name'],
                            'user_type'         => 'ADMIN',
                            'ip_address'        => $request->ip(),
                            'activity_type'     => 3,
                            'activity_details'  => $postData['name'] . ' ' . $this->data['title'] . ' Updated',
                            'platform_type'     => 'WEB',
                        ];
                        UserActivity::insert($activityData);
                    /* user activity */
                    return redirect($this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Updated Successfully !!!');
                } else {
                    return redirect()->back()->with('error_message', 'All Fields Required !!!');
                }
            }
            $data                           = $this->siteAuthService ->admin_after_login_layout($title,$page_name,$data);
            return view('maincontents.' . $page_name, $data);
        }
    /* edit */
    /* delete */
        public function delete(Request $request, $id){
            $id                             = Helper::decoded($id);
            $model                          = Company::find($id);
            $fields = [
                'status'             => 3,
                'deleted_at'         => date('Y-m-d H:i:s'),
            ];
            Company::where($this->data['primary_key'], '=', $id)->update($fields);
            /* user activity */
                $activityData = [
                    'user_email'        => session('user_data')['email'],
                    'user_name'         => session('user_data')['name'],
                    'user_type'         => 'ADMIN',
                    'ip_address'        => $request->ip(),
                    'activity_type'     => 3,
                    'activity_details'  => $model->name . ' ' . $this->data['title'] . ' Deleted',
                    'platform_type'     => 'WEB',
                ];
                UserActivity::insert($activityData);
            /* user activity */
            return redirect($this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Deleted Successfully !!!');
        }
    /* delete */
    /* change status */
        public function change_status(Request $request, $id){
            $id                             = Helper::decoded($id);
            $model                          = Company::find($id);
            if ($model->status == 1)
            {
                $model->status  = 0;
                $msg            = 'Deactivated';
                /* user activity */
                    $activityData = [
                        'user_email'        => session('user_data')['email'],
                        'user_name'         => session('user_data')['name'],
                        'user_type'         => 'ADMIN',
                        'ip_address'        => $request->ip(),
                        'activity_type'     => 3,
                        'activity_details'  => $model->name . ' ' . $this->data['title'] . ' Deactivated',
                        'platform_type'     => 'WEB',
                    ];
                    UserActivity::insert($activityData);
                /* user activity */
            } else {
                $model->status  = 1;
                $msg            = 'Activated';
                /* user activity */
                    $activityData = [
                        'user_email'        => session('user_data')['email'],
                        'user_name'         => session('user_data')['name'],
                        'user_type'         => 'ADMIN',
                        'ip_address'        => $request->ip(),
                        'activity_type'     => 3,
                        'activity_details'  => $model->name . ' ' . $this->data['title'] . ' Activated',
                        'platform_type'     => 'WEB',
                    ];
                    UserActivity::insert($activityData);
                /* user activity */
            }            
            $model->save();
            return redirect($this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' '.$msg.' Successfully !!!');
        }
    /* change status */
    /* subcriptions */
        public function subcriptions(Request $request, $id){
            $data['module']                 = $this->data;
            $id                             = Helper::decoded($id);
            $page_name                      = 'company.subcriptions';
            $data['row']                    = Company::where('id', '=', $id)->first();
            $title                          = 'Subscription History: ' . (($data['row'])?$data['row']->name:'');
            $data['packages']               = Package::select('id', 'name', 'duration', 'price')->where('status', '=', 1)->get();
            $data['subscriptions']          = DB::table('company_subcriptions')
                                                ->join('companies', 'company_subcriptions.company_id', '=', 'companies.id')
                                                ->join('packages', 'company_subcriptions.package_id', '=', 'packages.id')
                                                ->select('company_subcriptions.*', 'companies.name as company_name', 'packages.name as package_name')
                                                ->where('company_subcriptions.status', '!=', 3)
                                                ->where('company_subcriptions.company_id', '=', $id)
                                                ->orderBy('company_subcriptions.id', 'DESC')
                                                ->get();
            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'package_id'           => 'required',
                    'payment_mode'         => 'required',
                    'payment_amount'       => 'required',
                    'licence_no'           => 'required',
                ];
                if($this->validate($request, $rules)){
                    $getPackage                 = Package::select('id', 'name', 'duration', 'price')->where('id', '=', $postData['package_id'])->first();
                    $payment_amount             = (($getPackage)?$getPackage->price:0);
                    $duration                   = (($getPackage)?$getPackage->duration:0);
                    $start_date                 = date('Y-m-d');
                    $end_date                   = date('Y-m-d', strtotime('+' . $duration . ' months'));
                    
                    $subscriptionCount          = CompanySubcription::where('company_id', $postData['company_id'])->count();
                    if($subscriptionCount > 0){
                        CompanySubcription::where('company_id', $postData['company_id'])->update(['status' => 0]);
                    }

                    $fields = [
                        'company_id'            => strip_tags($postData['company_id']),
                        'package_id'            => strip_tags($postData['package_id']),
                        'payment_mode'          => strip_tags($postData['payment_mode']),
                        'payment_amount'        => $payment_amount,
                        'txn_id'                => strip_tags($postData['txn_id']),
                        'licence_no'            => strip_tags($postData['licence_no']),
                        'start_date'            => $start_date,
                        'end_date'              => $end_date,
                        'comment'               => strip_tags($postData['comment']),
                    ];
                    CompanySubcription::insert($fields);

                    /* user activity */
                        $activityData = [
                            'user_email'        => (($data['row'])?$data['row']->email:''),
                            'user_name'         => (($data['row'])?$data['row']->name:''),
                            'user_type'         => 'ADMIN',
                            'ip_address'        => $request->ip(),
                            'activity_type'     => 3,
                            'activity_details'  => (($data['row'])?$data['row']->name:'') . ' Subscription Renewed',
                            'platform_type'     => 'WEB',
                        ];
                        UserActivity::insert($activityData);
                    /* user activity */
                    return redirect($this->data['controller_route'] . "/subcriptions/" . Helper::encoded($postData['company_id']))->with('success_message', $this->data['title'].' Subscription Renewed Successfully !!!');
                } else {
                    return redirect()->back()->with('error_message', 'All Fields Required !!!');
                }
            }
            $data                           = $this->siteAuthService ->admin_after_login_layout($title,$page_name,$data);
            return view('maincontents.' . $page_name, $data);
        }
    /* subcriptions */
}
