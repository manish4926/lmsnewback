<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="keywords" content="">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <link rel="icon" href="images/favicon.png" type="image/png">
  <title>Lead Management System - JIMS INDIA</title>
    
     <!--End  Page Level  CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/icons.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/responsive.css') }}">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('css/fontawsome/css/font-awesome.min.css') }}"> --}}
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('css/simple-line-icon/simple-line-icons.css') }}"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.css">
    {{-- <link href="{{ asset('css/icons/font-awesome/font-awesome.css')}}" rel="stylesheet"> --}}
    
    <link rel="stylesheet" type="text/css" href="{{ asset('css/toastr.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/jquery-toast/jquery.toast.min.css') }}">

    <link href="{{ asset('plugins/sweetalert/sweetalert.css') }}" rel="stylesheet"/>
    
    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
          <link rel="stylesheet" type="text/css" href="{{ asset('js/html5shiv.min.js') }}">
          <link rel="stylesheet" type="text/css" href="{{ asset('js/respond.min.js') }}">
    <![endif]-->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    @include('partials.myjs')
    <style>
      .truncate {
  width: 50px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  table-layout:fixed;
}

table { width:250px;table-layout:fixed; }
table tr { height:1em;  }
td { overflow:hidden;white-space:nowrap;  }

@if(!empty($userProfile) AND $userProfile['dataversion']['options'] == 'light')
/* .table thead th {
  background: #c3d9ff;
}
 */
.table {
  word-wrap:break-word;
  table-layout: fixed;
  color: #323537;
}

.table th,.table td,.table caption{
  padding: 4px 10px 4px 5px !important;
  font-size: 85%;    
  white-space: inherit;
}
@endif

.sticky-left-side {
  @if(!empty($userProfile) AND $userProfile['theme']['options'] == 'blue')
    background: linear-gradient(to top, #30cfd0 0%, #330867 100%);
  @elseif(!empty($userProfile) AND $userProfile['theme']['options'] == 'red')
    background: #c31432;  /* fallback for old browsers */
    background: -webkit-linear-gradient(to right, #240b36, #c31432);  /* Chrome 10-25, Safari 5.1-6 */
    background: linear-gradient(to right, #240b36, #c31432); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
    /* background: linear-gradient(to top, rgba(36, 11, 54, 0.7) 0%, rgba(195, 20, 50, 0.7) 100%); */

  @endif
  
}
    </style>

      
@stack('topscripts')   
</head>

<body class="sticky-header @if(!empty($userProfile) AND $userProfile['sidebar']['options'] == 'minimized') {{'left-side-collapsed'}} @endif" >
@if(Session::has('successMessage'))
<script type="text/javascript">
    toastr["success"]("{{ Session::get('successMessage')}}");
</script>
@endif
@if(Session::has('errorMessage'))
<script type="text/javascript">
    toastr["error"]("{{ Session::get('errorMessage')}}");
</script>
@endif
@if(Session::has('popupMessage'))
<script type="text/javascript">
$(document).ready(function() {
    $("#{{ Session::get('popupMessage')}}").modal("show");
});
</script>
@endif
<!-- Small Modal -->
<div  id="modal-contact" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Contact Details</h4>
      </div>
      <div class="modal-body">
        <div id="userdetail"></div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
 <!-- END Small Modal -->

    <!--Start left side Menu-->
    <div class="left-side sticky-left-side">

        <!--logo-->
        <div class="logo">
            <a href="{{ route('dashboard') }}"><img src="{{ asset('images/logo-white.png') }}" alt=""></a>
        </div>

        <div class="logo-icon text-center">
            <a href="{{ route('dashboard') }}"><img src="{{ asset('images/logo-icon.png') }}" alt=""></a>
        </div>
        <!--logo-->

        <div class="left-side-inner">
            <!--Sidebar nav-->
            <ul class="nav nav-pills nav-stacked custom-nav">
           		
                
                @if($user->hasRole('Admin'))
                <li class="nav-active"><a href="{{ route('dashboard') }}"><i class="icon-home"></i> <span>Dashboard</span></a>
                @endif

                @if($user->hasRole('FrontDesk') OR $user->hasRole('Admin'))
                <li><a href="{{ route('addform') }}"><i class="icon-note"></i> <span>Add Form</span></a></li> 
                @endif

                @if($user->hasRole('Admin'))
                {{-- <li><a href="{{ route('addform') }}"><i class="icon-note"></i> <span>Add Form</span></a></li> --}}
				        <li><a href="{{ route('addbulkform') }}"><i class="icon-layers"></i> <span>Bulk Form Add</span></a></li>
                <li><a href="{{ route('viewforms') }}"><i class="ti-layout-slider"></i> <span>View Forms</span></a></li>
                <li><a href="{{ route('sourcedata') }}"><i class="ti-files"></i> <span>Source Data</span></a></li>
                <li><a href="{{ route('assigndata',['followupnum'=>1]) }}"><i class="icon-grid"></i> <span>Assign Data</span></a></li>
                <li><a href="{{ route('assigneddata') }}"><i class="icon-drawer"></i> <span>Assigned</span></a></li>
                <li><a href="{{ route('admissionform') }}"><i class="icon-fire"></i> <span>Admission Form</span></a></li>
                {{-- <li><a href="{{ route('analyserecord') }}"><i class="icon-pie-chart"></i> <span>Analyse Records</span></a></li> --}}
                @endif
                
                
                @if($user->hasRole('Counsellor') OR $user->hasRole('FrontDesk'))
                <li><a href="{{ route('followuplist',['followupnum'=>1]) }}"><i class="ti-layout"></i> <span>Followup List</span></a></li>
                <li><a href="{{ route('followuplistall',['followupnum'=>1]) }}"><i class="icon-list"></i> <span>Followup List (All)</span></a></li>
                <li><a href="{{ route('followuplistrecall') }}"><i class="icon-call-out"></i> <span>Recall Leads</span></a></li>
                <li><a href="{{ route('calllogs',['followupnum'=>1]) }}"><i class="icon-clock"></i> <span>Call
                 Logs</span></a></li>
                <li><a href="{{ route('dailycounsellordata') }}"><i class="ti-stats-up"></i> <span>Daily Record</span></a></li>

                @endif
                <li><a href="{{ route('logoutMethod') }}"><i class="icon-lock"></i> <span>Logout</span></a>
                </li>

                

            </ul>
            <!--End sidebar nav-->

        </div>
    </div>
    <!--End left side menu-->


    <!-- main content start-->
    <div class="main-content" >

        <!-- header section start-->
        <div class="header-section">

            <a class="toggle-btn"><i class="fa fa-bars"></i></a>

            {{-- <form class="searchform">
                <input type="text" class="form-control" name="keyword" placeholder="Search here..." />
            </form> --}}

            <!--notification menu start -->
            <div class="menu-right">
                <ul class="notification-menu">
                    <li class="">
                      <a href="#" class="btn btn-default dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-tasks"></i>
                        <span class="badge">8</span>
                      </a>
                      <div class="dropdown-menu dropdown-menu-head pull-right">
                        <h5 class="title">You have 8 pending task</h5>
                        <ul class="dropdown-list">
                          <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 220px;"><li class="notification-scroll-list notification-list " style="overflow: hidden; width: auto; height: 220px;">
                            <!-- list item-->
                            <a href="javascript:void(0);" class="list-group-item">
                              <div class="media">
                                <div class="pull-left p-r-10">
                                  <em class="fa  fa-shopping-cart noti-primary"></em>
                                </div>
                                <div class="media-body">
                                  <h5 class="media-heading">A new order has been placed.</h5>
                                  <p class="m-0">
                                    <small>29 min ago</small>
                                  </p>
                                </div>
                              </div>
                            </a>

                            <!-- list item-->
                            <a href="javascript:void(0);" class="list-group-item">
                              <div class="media">
                                <div class="pull-left p-r-10">
                                  <em class="fa fa-check noti-success"></em>
                                </div>
                                <div class="media-body">
                                  <h5 class="media-heading">Databse backup is complete</h5>
                                  <p class="m-0">
                                    <small>12 min ago</small>
                                  </p>
                                </div>
                              </div>
                            </a>

                            <!-- list item-->
                            <a href="javascript:void(0);" class="list-group-item">
                              <div class="media">
                                <div class="pull-left p-r-10">
                                  <em class="fa fa-user-plus noti-info"></em>
                                </div>
                                <div class="media-body">
                                  <h5 class="media-heading">New user registered</h5>
                                  <p class="m-0">
                                    <small>17 min ago</small>
                                  </p>
                                </div>
                              </div>
                            </a>

                            <!-- list item-->
                            <a href="javascript:void(0);" class="list-group-item">
                              <div class="media">
                                <div class="pull-left p-r-10">
                                  <em class="fa fa-diamond noti-danger"></em>
                                </div>
                                <div class="media-body">
                                  <h5 class="media-heading">Database error.</h5>
                                  <p class="m-0">
                                    <small>11 min ago</small>
                                  </p>
                                </div>
                              </div>
                            </a>

                            <!-- list item-->
                            <a href="javascript:void(0);" class="list-group-item">
                              <div class="media">
                                <div class="pull-left p-r-10">
                                  <em class="fa fa-cog noti-warning"></em>
                                </div>
                                <div class="media-body">
                                  <h5 class="media-heading">New settings</h5>
                                  <p class="m-0">
                                    <small>18 min ago</small>
                                  </p>
                                </div>
                              </div>
                            </a>
                          </li><div class="slimScrollBar" style="background: rgb(0, 0, 0); width: 7px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px; height: 160.797px;"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div></div>
                          <li class="last"> <a href="#">View all notifications</a> </li>
                        </ul>
                      </div>
                    </li>
                    @if($user->hasRole('Admin'))
                    <li><a href="javascript:void(0);" onclick="reloadapidata();" class="btn btn-primary" style="padding: 10px 15px; font-size: 20px;"><i class="ti-import"></i></a></li>
                    @endif
                    <li>
                        <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                            <img src="{{ asset('images/users/avatar-4.jpg') }}" alt="" />
                            {{ $user->firstname }}
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-usermenu pull-right">
                        
                          <li> <a href="{{ route('profileoptions') }}"> <i class="fa fa-lock"></i> Edit Profile </a> </li>
                          <li> <a href="{{ route('logoutMethod') }}"> <i class="fa fa-lock"></i> Logout </a> </li>
                        </ul>
                    </li>

                </ul>
            </div>
            <!--notification menu end -->

        </div>
        <!-- header section end-->
<div class="formoverlay">

    <div class="three col">
        <h2>Please wait while we process data....</h2>
        <div class="loader" id="loader-2">
          <span></span>
          <span></span>
          <span></span>
        </div>
      </div>
</div>
{{-- End Of Header--}}
@yield('content')
{{-- Start Of Footer--}}
<script type="text/javascript">

  $(document).ready(function() {
        $('.dataTables_filter input').attr("placeholder", "Search...");
    });

    function opendetail(email) {
      event.preventDefault();
      jQuery.noConflict(); 
      $('#modal-contact').modal('show');

      $.ajaxSetup({
      headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type  : 'GET',
            url   : '{{ route('getuserdetail') }}',
            data : {email: email , '_token': $('input[name=_token]').val()},
          success: function(result){
            var output = "";
            console.log(result);
            output += '<h4>Email</h4><p>'+email+'</p>';
            output += '<h4>Phone</h4>';
            for (var i = 0; i <= result.length - 1; i++) {
              //console.log(result[i]['phone']);
              output += '<p>'+result[i]['phone']+' - '+result[i]['source']+' <span class="shadowtext">('+result[i]['date']+')</span> </p>';
            }
            $('#userdetail').html(output);
          }           
        });
    }
</script>
@if(!$user->hasRole('Admin'))
<script type="text/javascript">
$(document).ready(function () {
    //Disable full page
    $('body').bind('cut copy paste', function (e) {
        alert('Copy text is not allowed');
        e.preventDefault();
    });
    
    //Disable full page
    $("body").on("contextmenu",function(e){
        alert('Right click is not allowed');
        return false;
    });
    
});
</script>
@endif

        <!--Start  Footer -->
        <footer class="footer-main"> 2020 &copy; Lead Management System.	</footer>	
         <!--End footer -->

       </div>
      <!--End main content -->
    


    <!--Begin core plugin -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/jquery.nicescroll.js') }}"></script>
    <script src="{{ asset('js/functions.js') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-toast/jquery.toast.min.js') }}"></script>
    <!-- End core plugin -->
    <script src="{{ asset('plugins/sweetalert/sweet-alert.js') }}"></script>
    <script src="{{ asset('pages/jquery.sweet-alert.custom.js') }}"></script>

@stack('bottomscripts')   
@if(Session::has('sweetsuccessMessage'))
<script type="text/javascript">
  $(document).ready(function () {
    setTimeout(function () {
        swal({title: '{{ Session::get('sweetsweeterrorMessage')}}', type: 'success'});
    }, 1000);
});
</script>
@endif
@if(Session::has('sweeterrorMessage'))
<script type="text/javascript">
  $(document).ready(function () {
    setTimeout(function () {
        swal({title: '{{ Session::get('sweeterrorMessage')}}', type: 'error'});
    }, 500);
});
</script>
@endif
</body>
</html>
