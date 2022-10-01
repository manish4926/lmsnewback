@extends('master')

@section('content')

        <!--body wrapper start-->
        <div class="wrapper">
              
              <!--Start Page Title-->
               <div class="page-title-box">
                    <h4 class="page-title">Assign Closed Calls</h4>
                    <ol class="breadcrumb">
                        <li>
                            <a href="#">Dashboard</a>
                        </li>
                        <li>
                            <a href="#">Data</a>
                        </li>
                        <li class="active">
                            Assign Closed Calls
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
                    <form method="POST" action="{{ route('closedcalls',['followupnum' => $followupnum]) }}" class="inline-form"  data-parsley-validate>
                      @csrf
                      <select class="form-control" required="" name="search">
                        <option selected="" disabled="">Select Category</option>
                        @foreach($categories as $category)
                          <option {{ (!empty($search) AND ($search == $category->category)) ? 'selected' : ''}}>{{ $category->category }}</option>  
                        @endforeach
                      </select>
                      <input type="submit" class="btn btn-primary" value="Category Search" name="searchSubmit">
                    </form>
                   </div>
                   <div class="white-box">
                   <h2 class="header-title">Followups</h2>
                      <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="{{ $followupnum == 1 ? 'active' : ''}}"><a href="{{ route('closedcalls', ['followupnum' => 1]) }}">First </a></li>
        <li role="presentation" class="{{ $followupnum == 2 ? 'active' : ''}}"><a href="{{ route('closedcalls', ['followupnum' => 2]) }}">Second</a></li>
        <li role="presentation" class="{{ $followupnum == 3 ? 'active' : ''}}"><a href="{{ route('closedcalls', ['followupnum' => 3]) }}">Third</a></li>
        <li role="presentation" class="{{ $followupnum == 4 ? 'active' : ''}}"><a href="{{ route('closedcalls', ['followupnum' => 4]) }}">Fourth</a></li>
        <li role="presentation" class="{{ $followupnum == 5 ? 'active' : ''}}"><a href="{{ route('closedcalls', ['followupnum' => 5]) }}">Fifth</a></li>
      </ul>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active">
      <button class="btn btn-primary" onclick="openandassigndata({{$followupnum}});">Open And Assign Data</button>
      <br><br>

      <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="datatable-first">
              <thead>
              <tr>
              <th><input type="checkbox" id="selectAll" value="" /></th>
                  <th>ID</th>
                  <th>Date</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  {{-- <th>City</th> --}}
                  <th>Course</th>
                  <th>Source</th>
                  <th>Counsellor</th>
                  <th>Remarks</th>
              </tr>
              </thead>
              <tbody>
              @foreach($followuplist as $followup)
              <tr class="odd gradeX">
              <td><input type="checkbox" name="followup[]" value="{{ $followup->id }}" /></td>
                  <td>{{ $followup->id }}</td>
                  
                  <td>{{ date('Y-m-d',strtotime($followup->posted_at)) }}</td>
                  <td>{{ $followup->name }}</td>
                  <td onclick="opendetail('{{ $followup->email }}')" class="{{ $followup->total > 1 ? 'multicontacts' : ''}}">{{ $followup->email }}</td>
                  <td>{{ $followup->phone }}</td>
                  {{-- <td>{{ $followup->city }}</td> --}}
                  <td>{{ $followup->course }}</td>
                  <td>{{ $followup->source }}</td>
                  <td>{{ $counsellors[$followup->counsellor_id]['firstname'] }}</td>
                  <td title="{{ $followup->category }}">{{ $followup->category }}</td>
              </tr>
              @endforeach
              
        </tbody>
    </table>

    </div>
  </div>
                     
                    
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
    <form method="POST" action="{{ route('openandassigncallssubmit') }}" id="assign-data-form"  data-parsley-validate>
      @csrf
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Open And Assign Data</h4>
      </div>
      <div class="modal-body">
        <div id="">Total Forms : <span id="formcounts">200</span></div><hr>
        <div class="form-group">
          <label class="control-label">Select Counsellor</label>
          <input type="hidden" name="forms" id="forms">
          <input type="hidden" name="level" id="level">
            <select class="form-control" name="counsellor">
                <option selected="" disabled="">Select Counsellor</option>
              @foreach($counsellors as $counsellor)

                <option value="{{ $counsellor['id'] }}">{{ $counsellor['firstname']." ".$counsellor['lastname'] }}</option>
              @endforeach
            </select>
            <br>
            <select class="form-control" name="status">
              <option selected="" disabled="">Select Option</option>
              <option value="open">Open</option>
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

 <!-- END Small Modal -->
@push('bottomscripts')
<!--<link rel="stylesheet" type="text/css" href="../../assets/widgets/datatable/datatable.css">-->
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/datatable/datatable.css') }}">
<script src="{{ asset('plugins/datatable/js-core.js') }}"></script>
<script src="{{ asset('plugins/datatable/datatable.js') }}"></script>
<script src="{{ asset('plugins/datatable/datatable-bootstrap.js') }}"></script>
<script src="{{ asset('plugins/datatable/datatable-tabletools.js') }}"></script>

<script type="text/javascript">

    /* Datatables basic */

    $(document).ready(function() {
        $('#datatable-first').dataTable();
        $('#datatable-second').dataTable();
        $('#datatable-third').dataTable();
        $('#datatable-forth').dataTable();
        $('#datatable-fifth').dataTable();
        $('#datatable-sixth').dataTable();

        $('#selectAll').click(function(e) {
          if($(this).hasClass('checkedAll')) {
            $('input').prop('checked', false);   
            $(this).removeClass('checkedAll');
            } else {
            $('input').prop('checked', true);
            $(this).addClass('checkedAll');

            var yourArray = [];
            $("input:checkbox[name='followup[]']:checked").each(function(){
                yourArray.push($(this).val());
            });
            //alert(yourArray);
            }
        });

        


        $('#assign-data-form').submit(function() {
          $(this).find("button[type='submit']").prop('disabled',true);
          $('.formoverlay').show();
        });
    });

    function openandassigndata(type) {
        var formsarray = [];
        var typenum = type + 1;
        $("input:checkbox[name='followup[]']:checked").each(function(){
            formsarray.push($(this).val());
        });
        
        if(formsarray.length <= 0) {
          alert('Please Select Value to Assign Data');
        }
        else {
          //Open Modal And Pass Values to Form
          /*event.preventDefault();*/
          jQuery.noConflict();
          var formcounts = formsarray.length;
          var formsarray = JSON.stringify(formsarray);
          $('#formcounts').html(formcounts);
          $('#forms').val(formsarray);
          $('#level').val(typenum);
          $('#assign-data-model').modal('show');
        }
        
    }

</script>
@endpush
@endsection