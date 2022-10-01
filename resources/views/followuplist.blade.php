@extends('master')

@section('content')
<style type="text/css">

</style>
        <!--body wrapper start-->
        <div class="wrapper">
              
              <!--Start Page Title-->
               <div class="page-title-box">
                    <h4 class="page-title">Followup List</h4>
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

                    <ul class="nav nav-tabs" role="tablist">
                      @php $routename = $request->route()->getName(); @endphp
                      <li role="presentation" class="{{ $followupnum == 1 ? 'active' : ''}}"><a href="{{ route($routename, ['followupnum' => 1]) }}">First </a></li>
                      <li role="presentation" class="{{ $followupnum == 2 ? 'active' : ''}}"><a href="{{ route($routename, ['followupnum' => 2]) }}">Second</a></li>
                      <li role="presentation" class="{{ $followupnum == 3 ? 'active' : ''}}"><a href="{{ route($routename, ['followupnum' => 3]) }}">Third</a></li>
                      <li role="presentation" class="{{ $followupnum == 4 ? 'active' : ''}}"><a href="{{ route($routename, ['followupnum' => 4]) }}">Fourth</a></li>
                      <li role="presentation" class="{{ $followupnum == 5 ? 'active' : ''}}"><a href="{{ route($routename, ['followupnum' => 5]) }}">Fifth</a></li>
                    </ul>
                   
                    <h2 class="header-title">Select Fields</h2>

                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="datatable-example">
					    <thead>
					    <tr>
                
                  <th>ID</th>
                  <th>Date</th>
                  @if($user->hasRole('Admin'))
                  <th>Counsellor Name</th>
                  @endif
                  {{-- <th>Follow Up</th> --}}
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
                  <td>{{ $forms->formid }}</td>
                  <td>{{ date('Y-m-d',strtotime($forms->posted_at)) }}</td>
                  @if($user->hasRole('Admin')) <td>{{ $counsellors[$forms->counsellor_id]['firstname'] }}</td> @endif
                  {{-- <td>{{ followuptext($forms->level) }}</td> --}}
                  <td>{{ $forms->name }}</td>
                  <td onclick="opendetail('{{ $forms->form_email }}')">{{ $forms->form_email }}</td>
                  <td>{{ $forms->phone }}</td>
                  <td>{{ $forms->city }}</td>
                  <td>{{ $forms->course }}</td>
                  <td>{{ $forms->source }}</td>
                  <td title="{{ $forms->query }}">{{ !empty($forms->lastcategory) ? $forms->lastcategory : $forms->query }}</td>
                  <td><a target="_blank" href="{{ route('followupdetail',['id' => $forms->formid])}}">View</a></td>
					    </tr>
					    @endforeach
					    
					    </tbody>
					    </table>
              {{-- <code>{{$list->firstItem()."-".$list->lastItem()." of ".$list->total() }}</code> --}}
              @if($userProfile['dataversion']['options'] == 'light') {{ $list->appends(request()->input())->links() }} @endif
                   </div>
                  </div>
              </div>
             <!--End row-->
             
             
                  
              </div>
             <!--End row-->
         
          </div>
        <!-- End Wrapper-->


 <!-- END Small Modal -->
@push('bottomscripts')
@if($userProfile['dataversion']['options'] != 'light')
<!--<link rel="stylesheet" type="text/css" href="../../assets/widgets/datatable/datatable.css">-->
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/datatable/datatable.css') }}">
<script src="{{ asset('plugins/datatable/js-core.js') }}"></script>
<script src="{{ asset('plugins/datatable/datatable.js') }}"></script>
<script src="{{ asset('plugins/datatable/datatable-bootstrap.js') }}"></script>
<script src="{{ asset('plugins/datatable/datatable-tabletools.js') }}"></script>

<script type="text/javascript">

    /* Datatables basic */

    $(document).ready(function() {
        $('#datatable-example').dataTable({
            "order": [[ 1, "desc" ]]
        });
    });

    /* Datatables hide columns */

    $(document).ready(function() {
        var table = $('#datatable-hide-columns').DataTable( {
            "scrollY": "300px",
            "paging": false
        } );

        $('#datatable-hide-columns_filter').hide();

        $('a.toggle-vis').on( 'click', function (e) {
            e.preventDefault();

            // Get the column API object
            var column = table.column( $(this).attr('data-column') );

            // Toggle the visibility
            column.visible( ! column.visible() );
        } );
    } );

    /* Datatable row highlight */

    $(document).ready(function() {
        var table = $('#datatable-row-highlight').DataTable();

        $('#datatable-row-highlight tbody').on( 'click', 'tr', function () {
            $(this).toggleClass('tr-selected');
        } );
    });

</script>
@endif
@endpush
@endsection