<?php
use App\Helpers\Helper;
$controllerRoute = $module['controller_route'];
?>
@extends('layouts.main')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
   <div class="row g-6">
      <h4><?=$page_header?></h4>
      <h6 class="breadcrumb-wrapper">
         <span class="text-muted fw-light"><a href="<?=url('dashboard')?>">Dashboard</a> /</span>
         <span class="text-muted fw-light"><a href="<?=url($controllerRoute . '/list/')?>"><?=$module['title']?> List</a> /</span>
         <?=$page_header?>
      </h6>
      <div class="nav-align-top mb-4">
         <?php if(session('success_message')){?>
            <div class="alert alert-success alert-dismissible autohide" role="alert">
               <h6 class="alert-heading mb-1"><i class="bx bx-xs bx-desktop align-top me-2"></i>Success!</h6>
               <span><?=session('success_message')?></span>
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
               </button>
            </div>
         <?php }?>
         <?php if(session('error_message')){?>
            <div class="alert alert-danger alert-dismissible autohide" role="alert">
               <h6 class="alert-heading mb-1"><i class="bx bx-xs bx-store align-top me-2"></i>Error!</h6>
               <span><?=session('error_message')?></span>
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
               </button>
            </div>
         <?php }?>
         <div class="card mb-4">
            <div class="card-header">
               <button type="button" class="btn btn-outline-success btn-sm float-end" data-bs-toggle="modal" data-bs-target="#backDropModal">Renew Subscription</button>
            </div>
            <div class="card-body">
               <table class="table table-striped">
                  <thead>
                     <tr>
                        <th>#</th>
                        <th>Package</th>
                        <th>Payment Amount</th>
                        <th>Txn No.</th>
                        <th>Licence No.</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Comment</th>
                        <th>Created At</th>
                        <th>Status</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php if(count($subscriptions) > 0){ $sl=1; foreach($subscriptions as $subscription){?>
                        <tr>
                           <td><?=$sl?></td>
                           <td><?=$subscription->package_name?></td>
                           <td><i class="fa fa-inr"></i> <?=number_format($subscription->payment_amount,2)?></td>
                           <td><?=$subscription->txn_id?></td>
                           <td><?=$subscription->licence_no?></td>
                           <td><?=date_format(date_create($subscription->start_date), "M d, Y")?></td>
                           <td><?=date_format(date_create($subscription->end_date), "M d, Y")?></td>
                           <td><?=$subscription->comment?></td>
                           <td><?=date_format(date_create($subscription->created_at), "M d, Y h:i A")?></td>
                           <td>
                              <?php if($subscription->status){?>
                                 <span class="badge bg-success"><i class="fa fa-check"></i> ACTIVE</span>
                              <?php } else {?>
                                 <span class="badge bg-warning"><i class="fa fa-times"></i> DEACTIVE</span>
                              <?php }?>
                           </td>
                        </tr>
                     <?php } } else {?>
                        <tr>
                           <td colspan="10" style="color: red; text-align: center;">No subscriptions found !!!</td>
                        </tr>
                     <?php }?>
                  </tbody>
               </table>
            </div>
        </div>
      </div>
   </div>
</div>
<!-- Modal -->
<div class="modal fade" id="backDropModal" data-bs-backdrop="static" tabindex="-1">
   <div class="modal-dialog modal-dialog-centered" style="max-width: 50%;">
      <form class="modal-content" method="POST" action="">
         @csrf
         <input type="hidden" name="company_id" value="<?=(($row)?$row->id:0)?>">
         <div class="modal-header">
            <h5 class="modal-title" id="backDropModalTitle">Renew Subscription</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <div class="row g-4 mb-3">
               <div class="col mb-2">
                  <label for="package_id" class="form-label">Package <span class="text-danger">*</span></label>
                  <select id="package_id" class="form-control" name="package_id" required>
                     <option value="" selected>Select Package</option>
                     <?php if($packages){ foreach($packages as $package){?>
                        <option value="<?=$package->id?>"><?=$package->name?> - <?=$package->duration?> months @ <?=$package->price?></option>
                     <?php } }?>
                  </select>
               </div>
               <div class="col mb-2">
                  <label for="payment_mode" class="form-label">Payment Mode <span class="text-danger">*</span></label>
                  <select id="payment_mode" class="form-control" name="payment_mode" required>
                     <option value="" selected>Select Payment Mode</option>
                     <option value="CASH">CASH</option>
                     <option value="UPI">UPI</option>
                     <option value="CREDIT CARD">CREDIT CARD</option>
                     <option value="DEBIT CARD">DEBIT CARD</option>
                     <option value="NETBANKING">NETBANKING</option>
                     <option value="CHEQUE">CHEQUE</option>
                     <option value="DEMAND DRAFT">DEMAND DRAFT</option>
                  </select>
               </div>
            </div>
            <div class="row g-4 mb-3">
               <div class="col mb-2">
                  <label for="payment_amount" class="form-label">Payment Amount <span class="text-danger">*</span></label>
                  <input type="text" id="payment_amount" class="form-control" name="payment_amount" required />
               </div>
               <div class="col mb-2">
                  <label for="txn_id" class="form-label">Txn No.</label>
                  <input type="text" id="txn_id" class="form-control" name="txn_id" />
               </div>
            </div>
            <div class="row g-4 mb-3">
               <div class="col mb-2">
                  <label for="licence_no" class="form-label">Licence No. <span class="text-danger">*</span></label>
                  <input type="text" id="licence_no" class="form-control" name="licence_no" required />
               </div>
               <div class="col mb-2">
                  <label for="comment" class="form-label">Comment</label>
                  <textarea id="comment" class="form-control" name="comment" rows="3"></textarea>
               </div>
            </div>
         </div>
         <div class="modal-footer">
           <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
             Close
           </button>
           <button type="submit" class="btn btn-primary">Save</button>
         </div>
      </form>
  </div>
</div>
@endsection
@section('scripts')
<script src="<?=config('constants.admin_assets_url')?>assets/js/table.js"></script>
@endsection