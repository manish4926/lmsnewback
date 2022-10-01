
@extends('master')

@section('content')
    
        <!--body wrapper start-->
        <div class="wrapper">
              
              <!--Start Page Title-->
               <div class="page-title-box">
                    <h4 class="page-title">Add New Form</h4>
                    <ol class="breadcrumb">
                        <li>
                            <a href="#">Dashboard</a>
                        </li>
                        <li>
                            <a href="#">Form</a>
                        </li>
                        <li class="active">
                            Add New Forms
                        </li>
                    </ol>
                    <div class="clearfix"></div>
                 </div>
                  <!--End Page Title-->          
           @include('partials.alerts')
           
             <!--Start row-->
             <div class="row">
                 <div class="col-md-12">
                   <div class="white-box">
                     <h2 class="header-title">Select Fields</h2>
                       
                        <form method="POST" action="{{ route('addformsubmit') }}" class="form-horizontal"  data-parsley-validate>
                          @csrf
                          <div class="form-group">
                            <label class="col-md-2 control-label">Name</label>
                            <div class="col-md-10">
                              <input class="form-control" value="" type="text" name="name" placeholder="Enter Name" required="">
                            </div>
                          </div>
                          
                          <div class="form-group">
                            <label class="col-md-2 control-label" for="example-email">Email</label>
                            <div class="col-md-10">
                              <input id="example-email" name="email" class="form-control" placeholder="Email" type="email" required="" onkeyup="checkformexist(this)">
                              <div id="emailexistmesssage"></div>
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-md-2 control-label">Phone</label>
                            <div class="col-md-10">
                              <input class="form-control" value="" type="text" name="phone" placeholder="Enter Phone Number" required="">
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-md-2 control-label">City</label>
                            <div class="col-md-10">
                              <input class="form-control" value="" type="text" name="city" placeholder="Enter City">
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-sm-2 control-label">Select Course</label>
                            <div class="col-sm-10">
                              <select class="form-control" name="course" required="">
                                <option disabled="" selected="">Program Interested in</option>
                                <option value="PGDM">PGDM</option>
                                <option value="PGDM-IB">PGDM-IB</option>
                                <option value="PGDM-RM">PGDM-RM</option>
                                <option value="PGDM-PT">PGDM-PT</option>
                                <option value="PGDM-PT">PGDM-WP</option>
                                <option value="BBA">BBA</option>
                                <option value="BCA">BCA</option>
                                <option value="MCA">MCA</option>
                                <option value="FPM programme">FPM programme</option>
                              </select>
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-sm-2 control-label">Select Source</label>
                            <div class="col-sm-10">
                              <select class="form-control" name="source" required="">
                                <option disabled="" selected="">Select Source Type</option>
                                <option value="Walk In">Walk In</option>
                                <option value="Landline">Landline</option>
                                <option value="Helpline">Helpline</option>
                                <option value="Chat">Chat</option>
                                <option value="Instagram">Instagram</option>
                                <option value="Whatsapp Chat">Whatsapp Chat</option>
                              </select>
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-md-2 control-label">Query</label>
                            <div class="col-md-10">
                              <textarea class="form-control" rows="5" name="message" required=""></textarea>
                            </div>
                          </div>
                          <div class="form-group m-b-0">
                            <div class="col-sm-offset-2 col-sm-9">
                              <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                          
                          
                        </form>
                   </div>
                  </div>
              </div>
             <!--End row-->
             
             
                  
              </div>
             <!--End row-->
         
          </div>
        <!-- End Wrapper-->

@endsection