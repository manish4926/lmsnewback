<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700&display=swap" rel="stylesheet">
<link href="{{ asset('plugins/flowy/styles.css') }}" rel='stylesheet' type='text/css'>
    <link href="{{ asset('plugins/flowy/flowy.min.css') }}" rel='stylesheet' type='text/css'>
    <script src="{{ asset('plugins/flowy/flowy.min.js') }}"></script>
    <script src="{{ asset('plugins/flowy/main.js') }}"></script>   
    <script src="{{ asset('js/jquery.min.js') }}"></script>

    <link rel="stylesheet" type="text/css" href="{{ asset('css/toastr.css') }}">
    <script src="{{ asset('js/toastr.min.js') }}"></script>
</head>
<body>
 <div id="navigation">
        <div id="leftside">
            <div id="details">
            <div id="back"><img src="{{ asset('plugins/flowy/assets/arrow.svg') }}"></div>
            <div id="names">
                <p id="title">First Followup automation pipeline</p>
                <p id="subtitle">Marketing automation</p>
            </div>
        </div>            
        </div>
        <div id="centerswitch">
            {{-- <div id="leftswitch">First Followup</div>
            <div id="rightswitch">Second Followup</div>
            <div id="leftswitch">Third Followup</div>
            <div id="leftswitch">Fourth Followup</div>
            <div id="leftswitch">Fifth Followup</div> --}}
        </div>
        <div id="buttonsright">
            <div class="generate-now">Generate</div>
            <div id="discard" onclick="window.location.reload();">Discard</div>
            <div id="publish">Publish to site</div>
        </div>
    </div>
    <div id="leftcard">
        <div id="closecard">
            <img src="{{ asset('plugins/flowy/assets/closeleft.svg') }}">
        </div>
        <p id="header">Blocks</p>
        <div id="search">
            <img src="{{ asset('plugins/flowy/assets/search.svg') }}">
            <input type="text" placeholder="Search blocks">
        </div>
        <div id="subnav">
            <div id="triggers" class="navactive side navtab" data-show="sources-list">Sources</div>
            {{-- <div id="actions" class="navdisabled side navtab">Actions</div> --}}
            <div id="loggers" class="navdisabled side navtab" data-show="counsellor-list">Counsellors</div>
        </div>
        <div id="blocklist">
            <div class="sources-list data-list">
                @foreach($sources as $source)
                <div class="blockelem @if(!empty($source->flowid)) {{ "flow-generated" }} @else {{ "create-flowy" }}  @endif  noselect" @if(!empty($source->flowid)) data-flowid="{{ $source->flowid}}" @endif>
                    <input type="hidden" name='blockelemtype' class="blockelemtype" value="source-{{ $source->source}}">
                    <div class="grabme">
                        <img src="{{ asset('plugins/flowy/assets/grabme.svg') }}">
                    </div>
                    <div class="blockin">
                        <div class="blockico">
                            <span></span>
                            <img src="{{ asset('plugins/flowy/assets/databaseorange.svg') }}">
                        </div>
                        <div class="blocktext">
                            @if(!empty($source->flowid)) <span class="flow-generated-icon"><img src="{{ asset('plugins/flowy/assets/checkon.svg') }}"></span>  @endif
                            <p class="blocktitle">{{ $source->source}}</p>
                            <p class="blockdesc">Triggers when somebody visits a specified page</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="counsellor-list data-list">
                @foreach($counsellors as $counsellor)
                <div class="blockelem create-flowy noselect">
                    <input type="hidden" name='blockelemtype' class="blockelemtype" value="counsellor-{{ $counsellor->id}}">
                    <div class="grabme">
                        <img src="{{ asset('plugins/flowy/assets/grabme.svg') }}">
                    </div>
                    <div class="blockin">
                        <div class="blockico">
                            <span></span>
                            <img src="{{ asset('plugins/flowy/assets/actionblue.svg') }}">
                        </div>
                        <div class="blocktext">
                            <p class="blocktitle">{{ $counsellor->firstname." ".$counsellor->lastname}}</p>
                            <p class="blockdesc">Counsellor</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div id="propwrap">
        <div id="properties">
            <div id="close">
                <img src="{{ asset('plugins/flowy/assets/close.svg') }}">
            </div>
            <p id="header2">Properties</p>
            <div id="propswitch">
                <div id="dataprop">Data</div>
                <div id="alertprop">Alerts</div>
                <div id="logsprop">Logs</div>
            </div>
            <div id="proplist">
                <p class="inputlabel">Select database</p>
                <div class="dropme">Database 1 <img src="{{ asset('plugins/flowy/assets/dropdown.svg') }}"></div>
                <p class="inputlabel">Check properties</p>
                <div class="dropme">All<img src="{{ asset('plugins/flowy/assets/dropdown.svg') }}"></div>
                <div class="checkus"><img src="{{ asset('plugins/flowy/assets/checkon.svg') }}"><p>Log on successful performance</p></div>
                <div class="checkus"><img src="{{ asset('plugins/flowy/assets/checkoff.svg') }}"><p>Give priority to this block</p></div>
            </div>
            <div id="divisionthing"></div>
            <div id="removeblock">Delete blocks</div>
        </div>
    </div>
    <div id="canvas">
    </div> 


<script type="text/javascript">
    
    $('.navtab').click(function(){
        $('.data-list').hide();
        var classname = $(this).data('show');
        $("."+classname).show();
        
    });

    $('#publish').click(function(){

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        var flowc = flowy.output();
        var sflowc = JSON.stringify(flowc);

        var sources = [];
        var counsellors = [];

        var blocks = flowc.blocks;
        $.each(blocks, function( index, singleblock ) {
            var blockval = singleblock.data[0].value;
            var parent = singleblock.parent;

            if(parent == -1 ) {
                sources.push(blockval);
            } else if(parent == 0 ) {
                counsellors.push(blockval);
            }
        });

        sources = JSON.stringify(sources);
        counsellors = JSON.stringify(counsellors);
        //console.log(flowc);
        //console.log(flowc.blocks[0].data[0].value);

        $.ajax({
            type    : 'POST',
            url     : '{{ route('automateqeryflowsubmit') }}',
            data    : { sources: sources, counsellors: counsellors, sflowc: sflowc},
            success: function(result){
                toastr["success"]("Data Successfully Added");
                console.log("Data Successfully Added");
                
            }
                    
        });
    });

    $('.flow-generated').click(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        var flowid = $(this).data('flowid');

        $.ajax({
            type    : 'POST',
            url     : '{{ route('importautomateqeryflowsubmit') }}',
            data    : { flowid: flowid},
            success: function(result){
                var output = JSON.parse(result);
                flowy.import(output);
                console.log(output);
                
                toastr["success"]("Data Successfully Added");
                console.log("Data Successfully Added");
                
            }
                    
        });
    });

    $('.generate-now').click(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type    : 'POST',
            url     : '{{ route('automatecallflowgenerate') }}',
            data    : { },
            success: function(result){
                
                toastr["success"]("Flow Generated Successfully Added");
                console.log("Flow Generated Successfully Added");
                
            }
                    
        });
    });
    

</script>

</body>
</html>