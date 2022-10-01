@extends('master')

@section('content')

        <!--body wrapper start-->
        <div class="wrapper">
              
              <!--Start Page Title-->
               <div class="page-title-box">
                    <h4 class="page-title">View Forms</h4>
                    <ol class="breadcrumb">
                        <li>
                            <a href="#">Dashboard</a>
                        </li>
                        <li>
                            <a href="#">View</a>
                        </li>
                        <li class="active">
                            Forms
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
                   <a class="btn btn-primary center-margin" href="{{ route('downloadcompleteexcel') }}">Download Complete Excel</a>
                   <a class="btn btn-primary center-margin" href="{{ route('downloaduniqueexcel') }}">Download Unique Excel</a><br><br>
                     <h2 class="header-title">Select Fields</h2>
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="datatable-example">
					    <thead>
					    <tr>
					        <th>ID</th>
					        <th>Name</th>
					        <th>Email</th>
					        <th>Phone</th>
					        <th>City</th>
                  <th>Course</th>
                  <th>Page Path</th>
					        <th>Source</th>
					        <th>Query</th>
					    </tr>
					    </thead>
					    <tbody>

					    @foreach($myform as $forms)
					    <tr class="odd gradeX">
					        <td>{{ $forms->id }}</td>
					        <td><a href="{{ route('followupdetail',['id' => $forms->id])}}"> {{ $forms->name }}</a></td>
					        <td onclick="opendetail('{{ $forms->email }}')" class="{{ $forms->total > 1 ? 'multicontacts' : ''}}">{{ $forms->email }}</td>
					        <td>{{ $forms->phone }}</td>
					        <td>{{ $forms->city }}</td>
                  <td>{{ $forms->course }}</td>
                  <td>{{ $forms->pagepath }}</td>
					        <td>{{ $forms->source }}</td>
					        <td title="{{ $forms->query }}">{{ $forms->query }}</td>
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
        $('#datatable-example').dataTable();
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
@endpush
@endsection