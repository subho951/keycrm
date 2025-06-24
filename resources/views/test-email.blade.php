@if(session('success_message'))
    <div class="alert alert-success">{{ session('success_message') }}</div>
@endif

@if(session('error_message'))
    <div class="alert alert-danger">{{ session('error_message') }}</div>
@endif

<form action="/test-email-function" method="POST">
    @csrf
    <button type="submit" class="btn btn-primary">Send Test Email</button>
</form>