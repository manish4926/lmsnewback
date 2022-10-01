@if(Session::has('successMessage'))
<div class="alert alert-success alert-dismissible" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>

<strong>Well done! </strong> <span>{{ Session::get('successMessage')}}.</span> </div>
@endif
@if(Session::has('errorMessage'))
<div class="alert alert-info alert-dismissible" role="alert">
                      
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
<strong>Oops! </strong> <span>{{ Session::get('errorMessage')}}</span> </div>
@endif