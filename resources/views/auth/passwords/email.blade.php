<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from mixtheme.com/mixtheme/meter/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 11 Apr 2017 04:18:41 GMT -->
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="keywords" content="">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
  <title>Lead Management System -JIMS</title>
  <link rel="stylesheet" type="text/css" href="{{ asset('css/icons.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/responsive.css') }}">

</head>

<body class="sticky-header">
<style type="text/css">
	.panel{
		margin-top: 50px;
		margin-bottom: 50px;
	}
</style>
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="panel panel-default">
				<div class="panel-heading">Reset Password</div>

				<div class="panel-body">
				@if (session('status'))
					<div class="alert alert-success">
						{{ session('status') }}
					</div>
				@endif
					
					
					<form method="POST" action="password/email">
					@csrf
					<label>Email Address:</label>
					<input type="email" name="email" class="form-control">
					
					<br>
					<input type="submit" name="submit" value="Reset Password" class="btn btn-primary">

					</form>

				</div>
			</div>
		</div>
	</div>


    <!--Begin core plugin -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <!-- End core plugin -->

</body>



</html>