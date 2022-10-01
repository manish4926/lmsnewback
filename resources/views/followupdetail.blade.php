@extends('master')
@push('topscripts')
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}">
<style type="text/css">
  .readonly {
    background: #dee2e5;
  }

  .remarks-comments {
    display: block;
    padding: 10px;
    font-size: 14px;
    background-color: #f5f5f5;
    border: 1px solid #ccc;
    border-radius: 4px;
    line-height: 1.42857143;
    color: #333;
    word-break: break-all;
    word-wrap: break-word;
    font-weight: bold;
    position: relative;
  }

  .remarks-comments span {
    display: block;
    width: 100%;
    font-weight: normal;
    font-style: italic;
    font-size: 12px;
    position: absolute;
    top: 0;
    margin-top: -10px;
    background: #ccc;
    background: #d2245c;
    width: auto;
    padding: 0 10px;
    border-radius: 10px;
    right: 20px;
    color: #fff;
  }

  /*.gold-button {
    box-shadow: 0 3px 6px rgba(0,0,0,.16), 0 3px 6px rgba(110,80,20,.4),
              inset 0 -2px 5px 1px rgba(139,66,8,1),
              inset 0 -1px 1px 3px rgba(250,227,133,1);
    background-image: linear-gradient(160deg, #a54e07, #b47e11, #fef1a2, #bc881b, #a54e07);
    color: rgb(120,50,5) !important;
    text-shadow: 0 2px 2px rgba(250, 227, 133, 1);*/
  }

  .callselector {
    display: none;
    background: red;
  }
</style>
<style type="text/css">

  {{-- Chat --}}
  .grey.lighten-3 {
    background-color: #eee!important;
    color: #333;
  }
  .p-3 {
    padding: 1rem!important;
  }
  .w-75 {
    width: 75%!important;
  }
  .rounded {
    border-radius: .25rem!important;
  }
  .user {
    display: block;
    text-align: end;
    font-style: italic;
    display: block !important;
    text-align: right;
    width: 100% !important;
  }

  .card {
    position: relative;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-direction: column;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid rgba(0,0,0,.125);
    border-radius: .25rem;
  }

  .white-skin .primary-color, .white-skin ul.stepper li.active a .circle, .white-skin ul.stepper li.completed a .circle, ul.stepper li.active a .white-skin .circle, ul.stepper li.completed a .white-skin .circle {
    background-color: #4285f4!important;
  }

  .primary-color, ul.stepper li.active a .circle, ul.stepper li.completed a .circle {
    background-color: #4285f4!important;
  }
  .text-white {
    color: #fff!important;
  }
  .justify-content-end {
    -ms-flex-pack: end!important;
    justify-content: flex-end!important;
  }
  .d-flex {
    display: -ms-flexbox!important;
    display: flex!important;
  }
  .border-dark {
    border-color: #212121!important;
}
.chat-message-type {
    flex: 1 0 auto!important;
}
.md-form {
    position: relative;
    margin-top: 1.5rem;
    margin-bottom: 1.5rem;
}
.md-form textarea.md-textarea {
    padding: 1.5rem 0;
    overflow-y: hidden;
}
.card .md-form label {
    font-weight: 300;
}
.md-form label {
    position: absolute;
    top: 0;
    left: 0;
    font-size: 1rem;
    color: #757575;
    cursor: text;
    transition: transform .2s ease-out,color .2s ease-out;
    transform: translateY(12px);
    transform-origin: 0 100%;
}
.mt-5, .my-5 {
    margin-top: 3rem!important;
}
.white-skin .btn-primary {
    color: #fff;
    background-color: #4285f4!important;
}
</style>
@endpush
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
        Follow Up
      </li>
    </ol>
    <div class="clearfix"></div>
  </div>
  <!--End Page Title-->          
  @include('partials.alerts')
  @if($admissionformcheck != null)
  <div class="alert alert-success alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
    <strong>Well done! </strong> <span>{{ $admissionformcheck->name }} has filled the form with Reg. Id:<strong> {{ $admissionformcheck->regid }}</strong>. In Case if Query is not yet closed, Please Close it</span> 
  </div>
  @endif
  <!--Start row-->
  <div class="row">
    <div class="panel-wrap">
      <div class="col-md-12">
        <div class="panel panel-border panel-pink card card-cascade narrower mb-5">
          <div class="panel-heading">
            <h3 class="panel-title">Hotlead Conversations</h3>
          </div>
          <div class="panel-body">
            <div class="conversations">

              @foreach($hotlead_conversations as $conversations)
              @if($conversations->message_by == 'counsellor')

              <div class="text-center"><small>{{ date('d M, H:i', strtotime($conversations->created_at)) }}</small></div>
              <div class="d-flex justify-content-end">
                <p class="primary-color rounded p-3 text-white w-75 ">{{$conversations->message}}
                  <span class="user">Counsellor</span>
                </p>
              </div>
              @else
              <div class="text-center"><small>{{ date('d M, H:i', strtotime($conversations->created_at)) }}</small></div>
              <div class="d-flex justify-content-start media">

                <p class="grey lighten-3 rounded p-3 w-75">{{$conversations->message}}
                  <span class="user">Student</span>
                </p>

              </div>
              @endif
              @endforeach
              
            </div>  
            <div class="row border border-dark ">

                <div class="col-md-12">

                  <div class="d-flex flex-row">

                    <div class="md-form chat-message-type ">
                      <textarea type="text" id="form7" class="md-textarea form-control" rows="3" placeholder="Type your message"></textarea>
                      {{-- <label for="form7" class="">Type your message</label> --}}
                    </div>

                    <div class="mt-5">
                      <button class="btn btn-primary btn-lg waves-effect waves-light send-button">Send</button>
                    </div>

                  </div>

                </div>

              </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="panel panel-border panel-purple">
          <div class="panel-heading">
            <h3 class="panel-title">User Details</h3>
          </div>
          <div class="panel-body">
            <p onclick="updateForm();"><span>Name: </span>{{ $formdetails->name }}</p>
            <p onclick="opendetail('{{ $formdetails->email }}')"><span>Email:</span>{{ $formdetails->email }}</p>
            <p><span>Phone: </span>{{ $formdetails->phone }} 
              @if(!empty($user->myoperatorid))
                  <button class="btn btn-primary active call-myoperator" style="border-radius: 18px;">Call Now</button>
              @endif
                
            </p>
            <p><span>City: </span>{{ $formdetails->city }}</p>
            <p><span>Source: </span>{{ $formdetails->source }}</p>
            <p><span>Course: </span>{{ $formdetails->course }}</p>
            <p><span>Query: </span>{{ $formdetails->query }}</p>
            <p><span>Follow Up:</span>{{ $formdetails->level }}</p>
            <p><span>Status: </span><strong>{{ ucfirst($formdetails->status) }}</strong></p>
          </div>
        </div>
      </div>

      <div class="col-md-8">
        <div class="panel panel-border panel-pink">
          <div class="panel-heading">
            <h3 class="panel-title">Submissions</h3>
          </div>
          <div class="panel-body">
            @if(!empty($previousform))
            <div class="list-group">
              <a target="_blank" href="{{ route('followupdetail',['id' => $previousform->id])}}" class="list-group-item list-group-item-danger">
                <h4 class="list-group-item-heading">Repeated Query</h4>
                <p class="list-group-item-text">Already having a prevoius conversation on form id : <code>({{$previousform->id}})</code> with status: <code>({{$previousform->status}})</code></p>
              </a>
              @foreach($previousformfollowups as $data)
              @if($data->status == 1)
              <a class="list-group-item list-group-item-info">
                <p class="list-group-item-text"><strong>{{ $data->message_type == 'sms' ? 'SMS Send By '.$data->getuser()->firstname : followuptext($data->level)." Followup By ".$data->getuser()->firstname }} on Date ({{ timestampToDate($data->updated_at) }}):</strong><br>{{ $data->comment }}</p>
              </a>
              @endif
              @endforeach
            </div>                             
            @endif

            @foreach($reentrydata as $data)
            @if($data->status != 0 AND !empty($data->comment))
            <p><strong>{{ $data->message_type == 'sms' ? 'SMS Send By '.$data->getuser()->firstname : followuptext($data->level)." Followup By ".$data->getuser()->firstname }} on Date ({{ timestampToDate($data->updated_at) }}):</strong></p>
            <p class="remarks-comments">{{ $data->comment }}</h4><span>Category: {{ $data->category }}</span></p><hr>
            @endif

            @endforeach
                               @if(!empty($unfollowupcounsellorid) AND $unfollowupcounsellorid != $user->id)
                              <div class="alert alert-info" role="alert">Currently Assigned to <strong>{{ $unfollowupcounsellorname }}</strong></div>
                              @endif 
                            </div>
                          </div>
                        </div>
                        @if(($user->hasRole('Counsellor') OR $user->hasRole('FrontDesk')) AND ($formdetails->status == 'open' OR $formdetails->status == 'openandreserve') AND ($checkuserassigned >= 1))
                        <div class="col-md-12">

                          <div class="panel panel-border panel-pink">
                            <div class="panel-heading">
                              <h3 class="panel-title">Add Followup Feedback</h3>
                            </div>
                            <div class="panel-body">
                              <form method="POST" action="{{ route('followupsubmit') }}" class="form-horizontal"  data-parsley-validate>
                                @csrf
                              <div class="col-md-12">
                                <div class="form-group">
                                  <label class="control-label">Query Status</label>
                                  <select class="form-control" name="status" id="formstatus">
                                    {{-- <option value="reattempt">Reattempt</option> --}}
                                    <option value="open">Open And Forward</option>
                                    <option value="openandreserve">Open And Reserve (Recall)</option>
                                    <option value="complete">Complete</option>
                                    <option value="closed">Closed</option>
                                  </select>
                                </div>
                                <div class="form-group callselector" id="callselector" hidden="">
                                  <label class="control-label">Select Recall Date</label>
                                  <input type="text" name="calldate" class="form-control datepicker" required="" readonly="">
                                </div>
                                <div class="form-group">
                                  <label class="control-label"><strong>Categorize Feedback</strong></label>
                                  <div class="tags" id="prefilledcategorizedtags">
                                    <span class="tag" data-value="Call Not Picked">Call Not Picked</span>
                                    <span class="tag" data-value="Callback">Callback / Concerned Person Not Available</span>
                                    <span class="tag" data-value="Switch Off">Switch Off / Not Rechable</span>
                                    <span class="tag" data-value="Call Disconnected">Call Disconnected</span>
                                    <span class="tag" data-value="Interested for Admission">Interested for Admission</span>
                                    <span class="tag" data-value="Need More Time">Need More Time / Not Sure</span>
                                    <span class="tag" data-value="Admission Taken in Another College">Admission Taken in Another College</span>

                                    <hr>
                                    <span class="tag" data-value="Not Appeared In Any Entarance">Not Appeared In Any Entarance</span>
                                    <span class="tag" data-value="Drop This Year">Drop This Year</span>
                                    <span class="tag" data-value="Junk">Junk - 10th, 12th Grad., Already in PG, Wrong No.</span>
                                    <span class="tag" data-value="Looking For Another Course">Looking For Another Course - BBA, BCA, MCA, B.Tech, HM, BJMC</span>
                                    <span class="tag" data-value="MBA Only">MBA Only</span>
                                    <span class="tag" data-value="Not Eligible">Not Eligible - Less marks in graduation and qualifying exam</span>
                                    <span class="tag" data-value="Not Interested">Not Interested - For PGDM / JIMS</span>
                                    <span class="tag" data-value="Form Filled">Form Filled</span>
                                    <hr>
                                    {{-- <code><span style="font-size: 12px;">Please Close Query with Below Remarks</span></code><br> --}}
                                    <span class="tag" data-value="Will Check after Entrance Exam Result">Will Check after Entrance Exam Result</span>
                                    <span class="tag" data-value="Will Check after Nov CAT">Will Check after Nov CAT</span>
                                    <span class="tag" data-value="Will Check after Dec MAT">Will Check after Dec MAT</span>
                                    <span class="tag" data-value="Will Check after Jan CMAT">Will Check after Jan CMAT</span>
                                    <span class="tag" data-value="Will Check after Feb MAT">Will Check after Feb MAT</span>
                                    <span class="tag" data-value="Will Check after May MAT">Will Check after May MAT</span>
                                    <span class="tag" data-value="Will Check after XAT">Will Check after XAT</span>
                                    <span class="tag" data-value="Will Check after GMAT">Will Check after GMAT</span>
                                    <hr>
                                    <span class="tag gold-button" data-value="Golden Calls">Golden Calls</span>
                                    <span class="tag gold-button" data-value="Send to Whatsapp">Send to Whatsapp</span>

                                  </div>
                                  <textarea class="form-control readonly" rows="5" name="category" id="categorybox" required="" placeholder="--------------------- Select Option From Above ---------------------"></textarea>
                                </div>
                                <div class="form-group">
                                  <label class="control-label"><strong>Remarks</strong></label>
                                  <input type="hidden" name="id" value="{{ $currentfollowup->id }}">
                                  <input type="hidden" name="followupnum" value="{{ $followupnum }}">
                                  <input type="hidden" name="formid" value="{{ $currentfollowup->form_id }}">
                                  <div class="tags" id="prefilledtags">
                                    <span class="tag" data-value="Call Not Picked">Call Not Picked</span>
                                    <span class="tag" data-value="Request to Call At">Callback</span>
                                    <span class="tag" data-value="Not Interested">Not Interested</span>
                                    <span class="tag" data-value="Change Course">Change Course</span>
                                    <span class="tag" data-value="Switch Off">Switch Off</span>
                                    <span class="tag" data-value="Call Disconnected">Call Disconnected</span>
                                    <span class="tag" data-value="Not Reachable">Not Reachable</span>
                                    <span class="tag" data-value="Concerned Person Not Available">Not Available</span>
                                    <span class="tag" data-value="Wrong Number">Wrong Number</span>
                                    <span class="tag" data-value="Form Filled">Form Filled</span>
                                    <hr>
                                    {{-- <code><span style="font-size: 12px;">Please Close Query with Below Remarks</span></code><br> --}}
                                    <span class="tag" data-value="Will Check after Entrance Exam Result">Will Check after Entrance Exam Result</span>
                                    <span class="tag" data-value="Will Check after Nov CAT">Will Check after Nov CAT</span>
                                    <span class="tag" data-value="Will Check after Dec MAT">Will Check after Dec MAT</span>
                                    <span class="tag" data-value="Will Check after Jan CMAT">Will Check after Jan CMAT</span>
                                    <span class="tag" data-value="Will Check after Feb MAT">Will Check after Feb MAT</span>
                                    <span class="tag" data-value="Will Check after May MAT">Will Check after May MAT</span>
                                    <span class="tag" data-value="Will Check after XAT">Will Check after XAT</span>
                                    <span class="tag" data-value="Drop This Year">Drop This Year</span>
                                    <span class="tag" data-value="Not Eligible">Not Eligible</span>
                                  </div>
                                  <textarea class="form-control" rows="5" name="comment" id="messagebox" required=""></textarea>
                                </div>

                                <div class="form-group">
                                  <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                                <code>Note: Completed and Closed Query will not be processed further.</code>
                              </div>
                              </form>
                            </div>

                          </div>
                        </div>
                        @endif
                        @if(($user->hasRole('Counsellor') OR $user->hasRole('FrontDesk')) AND ($checkuserassigned >= 1))
                        <div class="col-md-12">
                          <div class="panel panel-border panel-purple">
                            <div class="panel-heading">
                              <h3 class="panel-title">Send SMS to User</h3>
                            </div>
                            <div class="panel-body">
                              <form method="POST" action="{{ route('sendsmssubmit') }}" class="form-horizontal"  data-parsley-validate>
                              @csrf
                              <div class="form-group">
                                <label class="control-label"><strong>Quick Text</strong></label>
                                <input type="hidden" name="id" value="{{ $currentfollowup->id }}">
                                <input type="hidden" name="receiverid" value="{{ $formdetails->id }}">
                                <input type="hidden" name="followupnum" value="{{ $followupnum }}">
                                <input type="hidden" name="counsellor_id" value="{{ $user->id }}">
                                <div class="tags" id="prefilledtagsforsms">
                                  <span class="tag" data-value="Dear Student, JIMS Address: 3, Institutional Area, Sector-5, Rohini (Near Rithala Metro Station), Delhi-110085.">JIMS Sector 5 Address</span>
                                  <span class="tag" data-value="Dear Student, Contact Us on +91-45184000, +91-45184001, +91-45184002">JIMS Phone Number</span>
                                  <span class="tag" data-value="Dear Student, Mail Us at admissions@jimsindia.org or visit https://www.jimsindia.org/">JIMS Email Id and Website</span>
                                  <span class="tag" data-value="Dear Student, Jagan Institute of Management Studies - JIMS Rohini 3 Near Rithala Metro Station, Rohini Institutional Area, Sector 5, Rohini, New Delhi, Delhi 110085 011 45184000 https://maps.google.com/?cid=11664210645613492242">JIMS Map Location</span>
                                  <span class="tag" data-value="Dear Student, Kindly Check JIMS Rohini Information Brochure on https://www.jimsindia.org/downloadform/Admission-Brochure-PGDM.pdf">JIMS Information Brochure</span>
                                  <span class="tag" data-value="Dear Student, Kindly Check JIMS Rohini Application Form on https://www.jimsindia.org/applyonline.aspx">Application Form</span>
                                    {{-- @if($gdpi['date'] > date('Y-m-d'))
                                    <span class="tag"  data-value="Dear {{ $formdetails->name }} Dont miss the opportunity to appear in the GD/PI process of One of the Top B Schools of India, JIMS, Rohini, Delhi on {{ date('d/m/Y',strtotime($gdpi['date'])) }} at {{ $gdpi['location'] }} Reporting Time - {{ $gdpi['time'] }} For more details, contact : {{ $gdpi['phone'] }} or visit website www.jimsindia.org">GDPI Date</span>
                                    @endif --}}
                                    {{-- <span class="tag"  data-value="Dear Student, Jims Sector 3 Address: Community Centre, (Near Police Station), Sector-3, Rohini, Delhi-110085.">JIMS Sector 3 Address</span> --}}
                                  </div>
                                  <textarea class="form-control" rows="2" name="message" id="smsbox" onkeyup="countChar(this)" maxlength="250">Dear Student, </textarea>
                                  <div id="charNum"></div>
                                </div>
                                <div class="form-group">
                                  <button type="submit" class="btn btn-primary">Submit</button>
                                </div>  
                                </form>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class="panel panel-border panel-pink">
                              <div class="panel-heading">
                                <h3 class="panel-title">Send Email to User</h3>
                              </div>
                              <div class="panel-body">
                                <div class="form-group">
                                  <div class="tags" id="prefilledtagsforemail">
                                    <span class="tag" data-value="information-brochure-2018">Send Admission Brochure</span>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="panel panel-border panel-pink">
                              <div class="panel-heading">
                                <h3 class="panel-title">Send Whatsapp to User</h3>
                              </div>
                              <div class="panel-body">
                                <div class="form-group">
                                  <div class="tags">
                                    <a href="http://wa.me/91{{ $formdetails->phone }}" target="_blank" class="tag">Send Whatsapp</a><br><br>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          @endif
                        </div>
                      </div>
                      <!--End row-->



                    </div>
                    <!--End row-->

                  </div>
                  <!-- End Wrapper-->

                  <!-- Small Modal -->
                  <div  id="modal-uodate-contact" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-md">

                      <!-- Modal content-->
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <h4 class="modal-title">Update User Details</h4>
                        </div>
                        <form method="POST" action="{{ route('updatestudentdetailsubmit') }}" class="form-horizontal"  data-parsley-validate>
                        @csrf
                        <div class="modal-body">

                          <input type="hidden" name="formid" value="{{ $formdetails->id }}">
                          <label>Name:</label>
                          <input type="text" class="form-control" name="name" value="{{ $formdetails->name }}" required=""><br>
                          <label>Email:</label>
                          <input type="email" class="form-control" name="email" value="{{ $formdetails->email }}" required="">
                        </div>
                        <div class="modal-footer">
                          <input type="submit" class="btn btn-primary" name="updatesubmit" value="Submit">
                          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                        </form>
                      </div>

                    </div>
                  </div>
                  <!-- END Small Modal -->

                  @push('bottomscripts')
                  <script src="{{ asset('plugins/datatable/js-core.js') }}"></script>

                  <script src="{{ asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>

                  <script type="text/javascript">
                    $('.datepicker').datepicker({
                      format: 'dd-mm-yyyy',
                      todayHighlight: true,
                      startDate: '0d',
                      autoclose: true
                    });
                  </script>
                  <!--<link rel="stylesheet" type="text/css" href="../../assets/widgets/datatable/datatable.css">-->
                  <script type="text/javascript">
                    $(document).ready(function() {

                      $('#prefilledcategorizedtags span').click(function() {
                        $('#categorybox').val($(this).data("value"));
                      });

                      $('#prefilledtags span').click(function() {
                        $('#messagebox').val($(this).data("value"));
                      });

                      $('#prefilledtagsforsms span').click(function() {
                        $('#smsbox').val($(this).data("value")+ "\nRegards, \n{{ $user->firstname." ".$user->lastname }} \n{{ $user->phone}}");
                      });

                      $('#prefilledtagsforemail span').click(function() {
                        toastr["warning"]("Sending Email Initiated");
                        var campaignid = $(this).data("value");
                        var formid = '{{ $id }}';
                        console.log(campaignid);
                        console.log(formid);

    //send email
    $.ajax({
      type  : 'POST',
      url   : '{{ route('sendsmtpemail') }}',
      data  : { campaignid:campaignid, formid: formid},
      success: function(result){
        console.log(result);
        if(result == 'Success') {
          toastr["success"]("Email Sent Successfully");
        } else {
          toastr["error"]("Oops! Something Went Wrong. Please try again");
        }

      }
    });

  });

                      $('select#formstatus').on('change', function() {
                        if(this.value == 'openandreserve') {
                          $('#callselector').show();
                        } else {
                          $('#callselector').hide();
                        }
    //$('#callselector').show();
    //alert( this.value );
  });

                    });

                    /*SMS Character Count*/
                    $('#charNum').text("Character Left: "+ 250);
                    function countChar(val) {
                      var len = val.value.length;
                      var maxlen = 250;
                      if (len > maxlen) {
                        val.value = val.value.substring(0, maxlen);
                      } else {
                        var charLeft = maxlen - len;
                        $('#charNum').text("Character Left: "+ charLeft);
                        if(maxlen - len <= 10) {
                          $('#charNum').css('color', '#F44336');
                        } else {
                          $('#charNum').css('color', 'black');
                        }
                      }
                    };

                    function updateForm(email) {
                      $('#modal-uodate-contact').modal('show');
                    }
                  </script>
                  <script>
                    $(".readonly").on('keydown paste', function(e){
                      e.preventDefault();
                    });
                  </script>


<script>
  $('.send-button').click(function(){
  //alert('sddsd')
  var id = '{{ $request->id }}';
  var message = $('#form7').val();
  var dt = new Date();
  
  
  const time = dt.getDate() + " " + dt.toLocaleString('default', { month: 'short' }) + ", " + dt.getHours() + ":" + dt.getMinutes();

  $('.conversations').append(
    '<div class="text-center"><small>'+ time +'</small></div> \
      <div class="d-flex justify-content-end"> \
        <p class="primary-color lighten-3 rounded p-3 text-white w-75">'+message+' \
          <span class="user">You</span> \
        </p> \
    </div>'
  );

  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
  console.log('Fetching Started');
  
  $.ajax({
      type  : 'POST',
      url   : '{{ route('hotleadConversationSubmit') }}',
      data: {"form_id": id, 'message': message},
    success: function(result){
      toastr["success"]("Message Sent");
    }
          
  });
  $('#form7').val('');
  
});

  $('.call-myoperator').click(function(){
  //alert('sddsd')
  var id = '{{ $request->id }}';
  var phone = '{{ $formdetails->phone }}';
  var useroperatorid = '{{ $user->myoperatorid }}';
  var randomrefid = '{{ $request->id."-".time() }}';

  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
  console.log('Fetching Started');
  
  $.ajax({
      type  : 'POST',
      url   : '{{ route('myoperatorcalldirect') }}',
      data: {"phone": phone, 'useroperatorid': useroperatorid, 'randomrefid': randomrefid},
    success: function(result){
      toastr["success"]("Call Request Sent");
    }
          
  });
  
  
});
</script>
                  @endpush
                  @endsection