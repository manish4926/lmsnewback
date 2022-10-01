@extends('master')

@section('content')

<!--body wrapper start-->
<div class="wrapper">

  <!--Start Page Title-->
  <div class="page-title-box">
    <h4 class="page-title">Recall List</h4>
    <ol class="breadcrumb">
      <li>
        <a href="#">Dashboard</a>
      </li>
      <li>
        <a href="#">List</a>
      </li>
      <li class="active">
        Recall
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

        <h2 class="header-title">Today's Reminder Calls</h2>





        <div class="white-box">

          <h2 class="header-title">Select Fields</h2>

          <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>ID</th>
                <th>Date</th>
                @if($user->hasRole('Admin'))
                <th>Counsellor Name</th>
                @endif
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
                <td>{{ $forms->formid }}</td>
                <td>{{ date('Y-m-d',strtotime($forms->posted_at)) }}</td>
                @if($user->hasRole('Admin')) <td>{{ $counsellors[$forms->counsellor_id]['firstname'] }}</td> @endif
                <td>{{ followuptext($forms->followcounts) }}</td>
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


<!-- END Small Modal -->

@endsection