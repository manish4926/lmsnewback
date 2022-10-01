@extends('master')

@section('content')
@push('topscripts')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/lc_switch.css') }}">
    <script src="{{ asset('js/lc_switch.js') }}"></script>
    <style type="text/css">
        .radio-list li{
            border-bottom: 1px solid #ddd;
            padding-top: 20px;
            padding-bottom: 50px;
        }

        .radio-inline {
            display: inline-block;

        }

        .checkbox-custom, .radio-custom {
    opacity: 0;
    position: absolute;   
}

.checkbox-custom, .checkbox-custom-label, .radio-custom, .radio-custom-label {
    display: inline-block;
    vertical-align: middle;
    margin: 5px;
    cursor: pointer;
}

.checkbox-custom-label, .radio-custom-label {
    position: relative;
}

.checkbox-custom + .checkbox-custom-label:before, .radio-custom + .radio-custom-label:before {
    content: '';
    background: #fff;
    border: 2px solid #ddd;
    display: inline-block;
    vertical-align: middle;
    width: 25px;
    height: 25px;
    padding: 2px;
    margin-right: 10px;
    text-align: center;
}

.checkbox-custom:checked + .checkbox-custom-label:before {
    content: "\f00c";
    font-family: 'FontAwesome';
    background: rebeccapurple;
    color: #fff;
}

.radio-custom + .radio-custom-label:before {
    border-radius: 50%;
}

.radio-custom:checked + .radio-custom-label:before {
    content: "\f00c";
    font-family: 'FontAwesome';
    color: #bbb;
}

.checkbox-custom:focus + .checkbox-custom-label, .radio-custom:focus + .radio-custom-label {
  outline: 1px solid #ddd; /* focus style */
}
    </style>
@endpush

<div id="page-content-wrapper">
    <div id="page-content">            
        <div class="container">
<div id="page-title">
    <h2>User Options</h2>
    <p></p>
    
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-body">
                
                <div class="example-box-wrapper">
                    
                    <form method="POST" action="{{ route('profileoptionssubmit') }}">
                        @csrf
                    <ul class="no-bullets radio-list">
                        <li>
                            <div class="col-md-4">
                                <label><strong>Select Theme</strong></label>
                            </div>
                            <div class="col-md-8">
                                <div class="radio-inline">
                                    <input id="radio-1" class="radio-custom" name="theme" value="default" type="radio" {{ $userProfile['theme']['options'] == 'default' ? 'checked' : '' }}>
                                    <label for="radio-1" class="radio-custom-label">Default</label>
                                </div>
                                <div class="radio-inline">
                                    <input id="radio-2" class="radio-custom" name="theme" value="blue" type="radio" {{ $userProfile['theme']['options'] == 'blue' ? 'checked' : '' }}>
                                    <label for="radio-2" class="radio-custom-label">Blue</label>
                                </div>
                                <div class="radio-inline">
                                    <input id="radio-3" class="radio-custom" name="theme" value="red" type="radio" {{ $userProfile['theme']['options'] == 'red' ? 'checked' : '' }}>
                                    <label for="radio-3" class="radio-custom-label">Red</label>
                                </div>
                                
                            </div>
                        </li>
                        <li>
                            <div class="col-md-4">
                                <label><strong>Sidebar</strong></label>
                            </div>
                            <div class="col-md-8">
                                <div class="radio-inline">
                                    <input id="radio-sidebar-1" class="radio-custom" name="sidebar" value="minimized" type="radio" {{ $userProfile['sidebar']['options'] == 'minimized' ? 'checked' : '' }}>
                                    <label for="radio-sidebar-1" class="radio-custom-label">Minimize</label>
                                </div>
                                <div class="radio-inline">
                                    <input id="radio-sidebar-2" class="radio-custom" name="sidebar" value="filled" type="radio" {{ $userProfile['sidebar']['options'] == 'filled' ? 'checked' : '' }}>
                                    <label for="radio-sidebar-2" class="radio-custom-label">Filled</label>
                                </div>
                                
                            </div>
                        </li>

                        <li>
                            <div class="col-md-4">
                                <label><strong>Data Version</strong></label>
                            </div>
                            <div class="col-md-8">
                                <div class="radio-inline">
                                    <input id="radio-data-version-1" class="radio-custom" name="dataversion" value="light" type="radio" {{ $userProfile['dataversion']['options'] == 'light' ? 'checked' : '' }}>
                                    <label for="radio-data-version-1" class="radio-custom-label">Lighter</label>
                                </div>
                                <div class="radio-inline">
                                    <input id="radio-data-version-2" class="radio-custom" name="dataversion" value="fulfilled" type="radio" {{ $userProfile['dataversion']['options'] == 'fulfilled' ? 'checked' : '' }}>
                                    <label for="radio-data-version-2" class="radio-custom-label">Fulfilled</label>
                                </div>
                                
                            </div>
                        </li>
                    </ul>
                    <input class="btn btn-primary" type="submit" name="submit" value="Submit">
                    </form>
                </div>
            </div>
        </div>
    </div>    
</div>

        </div>
    </div>
</div>
@push('bottomscripts')
    

    <script type="text/javascript">
    $(document).ready(function(e) {
        $('input').lc_switch();
    
        // triggered each time a field changes status
        $(document).on('.lcs_check', function() {
            var status  = ($(this).is(':checked')) ? 'checked' : 'unchecked',
                num     = $(this).val(); 
                console.log(status);
        });
    });
    </script>
@endpush
@endsection