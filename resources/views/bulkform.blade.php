@extends('master')
@push('topscripts')
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/dropzone/dropzone.css') }}">
<!-- BEGIN PAGE LEVEL SCRIPTS -->
@endpush
@section('content')
<!--body wrapper start-->
        <div class="wrapper">
              
              <!--Start Page Title-->
               <div class="page-title-box">
                    <h4 class="page-title">Bulk Form Upload</h4>
                    <ol class="breadcrumb">
                        <li>
                            <a href="#">Dashboard</a>
                        </li>
                        <li>
                            <a href="#">Form</a>
                        </li>
                        <li class="active">
                            Bulk Upload
                        </li>
                    </ol>
                    <div class="clearfix"></div>
                 </div>
                  <!--End Page Title-->          
           
           
               <!--Start row-->
                <div class="row">
                  <a href="{{ asset('sample/Bulk Upload Sample.csv') }}">Download Sample Sheet</a> &emsp;
                  <a href="{{ asset('sample/Courses.xlsx') }}">Courses List</a>
                        <div class="col-md-12">
                          <form method="POST" action="{{ route('addbulkformsubmit') }}" class="dropzone bg-gray col-md-10 center-margin" id="dropzone" enctype="multipart/form-data"  data-parsley-validate>
                            @csrf
                              <div class="fallback">
                                <input name="file" type="file" multiple />
                              </div>
                              
                            </form>
                        </div>
                    </div>
                 <!-- end row --> 
 
            </div>
      <!-- End Wrapper-->

@push('bottomscripts')
<script src="{{ asset('plugins/dropzone/dropzone.js') }}"></script>
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<!--<script type="text/javascript">


    var myDropzone = new Dropzone("#dropzone");
    
    myDropzone.on("success", function(file, responseText) {
         console.log(responseText);
    });

</script>-->
@endpush
@endsection