@extends('master')

@section('content')

        <!--body wrapper start-->
        <div class="wrapper">
              
              <!--Start Page Title-->
               <div class="page-title-box">
                    <h4 class="page-title">{{ ucfirst($name) }} Followup List</h4>
                    <ol class="breadcrumb">
                        <li>
                            <a href="#">Dashboard</a>
                        </li>
                        <li>
                            <a href="#">List</a>
                        </li>
                        <li class="active">
                            Follow Up
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

                     
                     <div class="classic-form">
                       <button class="btn btn-primary" onclick="transferFollowup();">Transfer Data</button>
                       <button class="btn btn-primary" onclick="updatestatus();">Update Status</button>
                     </div>

                     <ul class="nav nav-tabs" role="tablist">
                      <li role="presentation" class="{{ $followupnum == 1 ? 'active' : ''}}"><a href="{{  route('counsellorfollowuplist',['id' => $id, 'name' => strtolower($name), 'followupnum' => 1 ]) }}">First </a></li>
                      <li role="presentation" class="{{ $followupnum == 2 ? 'active' : ''}}"><a href="{{ route('counsellorfollowuplist',['id' => $id, 'name' => strtolower($name), 'followupnum' => 2]) }}">Second</a></li>
                      <li role="presentation" class="{{ $followupnum == 3 ? 'active' : ''}}"><a href="{{ route('counsellorfollowuplist',['id' => $id, 'name' => strtolower($name), 'followupnum' => 3]) }}">Third</a></li>
                      <li role="presentation" class="{{ $followupnum == 4 ? 'active' : ''}}"><a href="{{ route('counsellorfollowuplist',['id' => $id, 'name' => strtolower($name), 'followupnum' => 4]) }}">Fourth</a></li>
                      <li role="presentation" class="{{ $followupnum == 5 ? 'active' : ''}}"><a href="{{ route('counsellorfollowuplist',['id' => $id, 'name' => strtolower($name), 'followupnum' => 5]) }}">Fifth</a></li>
                    </ul>

            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
					    <thead>
					    <tr>
                  <th><input type="checkbox" id="selectAll" value="" /></th>
                  <th>ID</th>
                  <th>Date</th>
                  <th>Counsellor Name</th>
                  <th>Follow Up</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>City</th>
                  <th>Course</th>
                  <th>Source</th>
                  <th>Query</th>
                  <th></th>
					    </tr>
					    </thead>
					    <tbody>

					    @foreach($list as $forms)
					    <tr class="odd gradeX {{ $forms->status == 1 ? 'success' : ''}}">
                  <td><input type="checkbox" name="followupid[]" value="{{ $forms->id }}" data-formid="{{ $forms->formid }}" data-level="{{ $forms->level }}" /></td>
                  <td>{{ $forms->formid }}</td>
                  <td>{{ date('Y-m-d',strtotime($forms->posted_at)) }}</td>
                  <td>{{ $counsellors[$forms->counsellor_id]['firstname'] }}</td>
                  <td>{{ followuptext($forms->level) }}</td>
                  <td>{{ $forms->name }}</td>
                  <td onclick="opendetail('{{ $forms->form_email }}')">{{ $forms->form_email }}</td>
                  <td>{{ $forms->phone }}</td>
                  <td>{{ $forms->city }}</td>
                  <td>{{ $forms->course }}</td>
                  <td>{{ $forms->source }}</td>
                  <td title="{{ $forms->query }}">{{ $forms->query }}</td>
                  <td><a target="_blank" href="{{ route('followupdetail',['id' => $forms->formid])}}">View</a></td>
					    </tr>
					    @endforeach
					    
					    </tbody>
					    </table>
              
                   </div>
                  </div>
              </div>
             <!--End row-->
             
             
                  
              </div>
             <!--End row-->
         
          </div>
        <!-- End Wrapper-->

<div  id="assign-data-model" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
    <form method="POST" action="{{ route('counsellortransfercallssubmit') }}" id="assign-data-form"  data-parsley-validate>
      @csrf
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Transfer Data to Another Counsellor</h4>
      </div>
      <div class="modal-body">
        <div id="">Total Forms : <span id="formcounts">0</span></div><hr>
        <div class="form-group">
          <label class="control-label">Select Counsellor</label>
          <input type="hidden" name="forms" id="forms">
          <input type="hidden" name="followupnum" id="followupnum" value="{{ $followupnum }}">
          
          <select class="form-control" name="counsellor">
              <option selected="" disabled="">Select Counsellor</option>
              @foreach($counsellors as $counsellor)
                <option value="{{ $counsellor['id'] }}">{{ $counsellor['firstname']." ".$counsellor['lastname'] }}</option>
              @endforeach
          </select>
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Assign</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
      </form>
    </div>

  </div>
</div>

<div  id="update-form-status-model" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
    <form method="POST" action="{{ route('updateformstatusfromcounsellorsubmit') }}"  data-parsley-validate>
      @csrf
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Update Form Status</h4>
      </div>
      <div class="modal-body">
        <div id="">Total Forms : <span id="uformcounts">200</span></div><hr>
        <div class="form-group">
          <label class="control-label">Select Status</label>
          <input type="hidden" name="forms" id="uforms">
          <input type="hidden" name="followupnum" value="{{ $followupnum }}">
            <select class="form-control" name="status">
              <option selected="" disabled="">Select Option</option>
              <option value="complete">Complete</option>
              <option value="closed">Closed</option>
            </select>
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Update</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
      </form>
    </div>

  </div>
</div>
 <!-- END Small Modal -->
@push('bottomscripts')
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/datatable/datatable.css') }}">
<script src="{{ asset('plugins/datatable/js-core.js') }}"></script>
<script src="{{ asset('plugins/datatable/datatable.js') }}"></script>
<script src="{{ asset('plugins/datatable/datatable-bootstrap.js') }}"></script>
<script src="{{ asset('plugins/datatable/datatable-tabletools.js') }}"></script>
<script type="text/javascript">

    $(document).ready(function() {
          $('#selectAll').click(function(e) { 
          if($(this).hasClass('checkedAll')) {
            $('input').prop('checked', false);   
            $(this).removeClass('checkedAll');
          } else {  
            $('input').prop('checked', true);
            $(this).addClass('checkedAll');

            var yourArray = [];
            $("input:checkbox[name='followupid[]']:checked").each(function(){
                yourArray.push($(this).val());
            });
            //alert(yourArray);
          }
        });
    });

    function transferFollowup() {
      var formsarray = [];
      $("input:checkbox[name='followupid[]']:checked").each(function(){
          formsarray.push($(this).val());
      });

      if(formsarray.length <= 0) {
        alert('Please Select Value to Transfer Data');
      }
      else {
        //Open Modal And Pass Values to Form
        /*event.preventDefault();*/
        jQuery.noConflict();
        var formcounts = formsarray.length;
        var formsarray = JSON.stringify(formsarray);
        $('#formcounts').html(formcounts);
        $('#forms').val(formsarray);
        $('#assign-data-model').modal('show');
      }
      
    }

    function updatestatus() {
      var formsarray = [];
      $("input:checkbox[name='followupid[]']:checked").each(function(){
          var formid = $(this).data('formid');
          var formlevel = $(this).data('level');
          formsarray.push({'formid' : formid, 'formlevel' : formlevel});
      });
      console.log(formsarray);
      if(formsarray.length <= 0) {
        alert('Please Select Value to Transfer Data');
      }
      else {
        //Open Modal And Pass Values to Form
        /*event.preventDefault();*/
        jQuery.noConflict();

        var formcounts = formsarray.length;
        var formsarray = JSON.stringify(formsarray);
        $('#uformcounts').html(formcounts);
        $('#uforms').val(formsarray);
        $('#update-form-status-model').modal('show');
      }
      
    }


</script>
@endpush
@endsection