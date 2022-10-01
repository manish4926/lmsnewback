@extends('master')

@section('content')


<div id="page-content-wrapper">
    <div id="page-content">            
        <div class="container">
<div id="page-title">
    <h2>User Roles</h2>
    <p></p>
    
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-body">
                <h3 class="title-hero">
                    List of All Users
                </h3>
                <div class="example-box-wrapper">
                    <table class="table table-striped table-hover responsive no-wrap" id="datatable-example" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Admin</th>
                            <th>Counsellor</th>
                            <th>FrontDesk</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 0; ?>
                        @foreach($userslist as $users)
                        <tr>
                        <form method="POST" action="{{ route('postadminassignroles') }}" >
                            @csrf
                            <td>{{ $i = $i+1 }}</td>
                            <td>{{ $users->firstname }}</td>
                            <td>{{ $users->lastname }}</td>
                            <td>{{ $users->email }} <input type="hidden" name="email" value="{{ $users->email }}"></td>
                            <td><input type="checkbox" {{ $users->hasRole('Admin') ? 'checked' : '' }} name="role_admin"></td>
                            <td><input type="checkbox" {{ $users->hasRole('Counsellor') ? 'checked' : '' }} name="role_counsellor"></td>
                            <td><input type="checkbox" {{ $users->hasRole('FrontDesk') ? 'checked' : '' }} name="role_frontdesk"></td>
                            <td><button type="submit" class="btn btn-primary">Assign Roles</button></td>
                        </form>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
    </div>    
</div>

        </div>
    </div>
</div>
@endsection