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
  <title>Lead Management System </title>
  <link rel="stylesheet" type="text/css" href="{{ asset('css/icons.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/responsive.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/jquery-toast/jquery.toast.min.css') }}">

</head>

<body class="sticky-header">

 
 <!--Start login Section-->
  <section class="login-section">
       <div class="container">
           <div class="row">
               <div class="login-wrapper">
                   <div class="login-inner">
                       
                       <div class="logo">
                         <img src="{{ asset('images/logo-dark.png') }}"  alt="logo"/>
                       </div>
                      
                      <h2 class="header-title text-center">Register</h2>
                        
                       <form method="POST" action="{{ route('registerMethod') }}" id="registerform"  data-parsley-validate>
                        @csrf
                           <div class="form-group">
                               <input type="text" class="form-control"  placeholder="First Name" name="fname" id="fname">
                           </div>
                           <div class="form-group">
                               <input type="text" class="form-control"  placeholder="Last Name" name="lname" id="lname">
                           </div>
                           <div class="form-group">
                               <input type="text" class="form-control"  placeholder="Email" name="email" id="email">
                           </div>
                           
                           <div class="form-group">
                               <input type="password" class="form-control"  placeholder="Password" name="password" id="password">
                           </div>

                           <div class="form-group">
                               <input type="password" class="form-control"  placeholder="Retype Password" name="password_confirmation" id="password_confirmation">
                           </div>

            <div class="form-group">
                           <div class="pull-left">
                            <div class="checkbox primary">
                              <input  id="checkbox-2" type="checkbox" name="remember">
                              <label for="checkbox-2">Remember me</label>
                            </div>
                           </div>
                           
                           <div class="pull-right">
                               <a href="{{ route('forgotpasswordreset') }}" class="a-link">
                               <i class="fa fa-unlock-alt"></i> Forgot password?
                               </a>
                           </div>
                         </div>
                          
                           <div class="form-group">
                               <input type="submit" value="Register" class="btn btn-primary btn-block" >
                           </div>
                           
                           {{-- <div class="form-group text-center">
                            Don't have an account?  <a href="#">Sign Up </a>
                           </div> --}}
                           
                       </form>
                       
                        <div class="copy-text"> 
                         <p class="m-0">2020 &copy; Jims</p>
                        </div>
                    
                   </div>
               </div>
               
           </div>
       </div>
  </section>
 <!--End login Section-->




    <!--Begin core plugin -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-toast/jquery.toast.min.js') }}"></script>
    <!-- End core plugin -->
<script type="text/javascript">
$("#registerform").submit(function(event){   
  event.preventDefault();
  var fname                 = $('#fname').val();
  var lname                 = $('#lname').val();
  var email                 = $('#email').val();
  var password              = $('#password').val();
  var password_confirmation = $('#password_confirmation').val();
  $.ajax({
      type  : 'POST',
      url   : '{{ route('registerMethod') }}',
      data : {fname: fname,lname: lname,email: email,password: password,password_confirmation: password_confirmation, '_token': $('input[name=_token]').val()},
    success: function(result){
        $.toast().reset('all');
        $("body").removeAttr('class');
        $.toast({
                heading: 'Notification',
                text: 'User successfully registered',
                position: 'top-right',
                loaderBg:'#FFBD4A',
                icon: 'success',
                hideAfter: 3500, 
                stack: 6
              });
        return false;  
    }           
  });
});
</script>
</body>
</html>
