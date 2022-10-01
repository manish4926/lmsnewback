<!DOCTYPE html>
<html lang="en">

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
  
  <style type="text/css">
  @if(!empty($theme) AND $theme['themecolor'] != 'default')
    body {
        background: url(" {{ asset('images/backbanner.jpg') }}") no-repeat center;
        background-size: cover;
        overflow: hidden;
      }

      .background-overlay {

        width: 100%;
        height: 100%;
        background: rgba(0,33,71,0.7);
        @if($theme['themecolor'] == 'blue')
        background: linear-gradient(to top, rgba(48, 207, 208, 0.7) 0%, rgba(51, 8, 103, 0.7) 100%);
        @elseif($theme['themecolor'] == 'red')
        background: #c31432;  /* fallback for old browsers */
        background: -webkit-linear-gradient(to right, #240b36, #c31432);  /* Chrome 10-25, Safari 5.1-6 */
        background: linear-gradient(to right, #240b36, #c31432); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
        background: linear-gradient(to top, rgba(36, 11, 54, 0.7) 0%, rgba(195, 20, 50, 0.7) 100%);

        @endif
      }
  @endif
  </style>
</head>

<body class="sticky-header">
<div class="background-overlay">
 
 <!--Start login Section-->
  <section class="login-section">
       <div class="container">
           <div class="row">
               <div class="login-wrapper">
                   <div class="login-inner">
                       
                       <div class="logo">
                         <img src="{{ asset('images/jims.png') }}"  alt="logo"/>
                       </div>
                      
                      <h2 class="header-title text-center">Login</h2>
                        
                       <form method="POST" action="{{ route('loginMethod') }}"  data-parsley-validate>
                        @csrf
                           <div class="form-group">
                               <input type="text" class="form-control"  placeholder="Username" name="email">
                           </div>
                           
                           <div class="form-group">
                               <input type="password" class="form-control"  placeholder="Password" name="pass">
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
                               <input type="submit" value="Login" class="btn btn-primary btn-block" >
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



</div>
    <!--Begin core plugin -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <!-- End core plugin -->

</body>



</html>
