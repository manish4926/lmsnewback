<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}" />

<script type="text/javascript">
    var emailtemplatelist = [];
    @foreach($emaillist['templates'] as $list)
        @if($list['isActive'])
            emailtemplatelist.push({'id':'{{ $list['id'] }}',  'name':'{{ $list['name'] }}'});    
        @endif
    @endforeach

    var messagelist = [];
    @foreach($messagelist as $key => $list)
            messagelist.push({'id':'{{ $key }}',  'name':'{{ $list }}'});    
    @endforeach
</script>

<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700&display=swap" rel="stylesheet">
<link href="{{ asset('plugins/flowy/styles.css') }}" rel='stylesheet' type='text/css'>
    <link href="{{ asset('plugins/flowy/flowy.min.css') }}" rel='stylesheet' type='text/css'>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/flowy/flowy.min.js') }}"></script>
    <script src="{{ asset('plugins/flowy/main.js') }}"></script>   
    
    <style type="text/css">
        .navtab {
            width: calc(88% / 3);
            display: inline-block;
            font-family: Roboto;
            font-weight: 500;
            color: #808292;
            font-size: 14px;
            height: 48px;
            line-height: 48px;
            text-align: center;
        }
    </style>
</head>
<body>
 <div id="navigation">
        <div id="leftside">
            <div id="details">
            <div id="back"><img src="{{ asset('plugins/flowy/assets/arrow.svg') }}"></div>
            <div id="names">
                <p id="title">Email automation pipeline</p>
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
            <div class="navactive side navtab" data-show="sources-list">Sources</div>
            {{-- <div class="navdisabled side navtab" data-show="followups-list">Followups</div>
            <div class="navdisabled side navtab" data-show="category-list">Categories</div> --}}
            <div  class="navdisabled side navtab" data-show="email-list">Email/SMS</div>
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
                            <p class="blockdesc">Entry Point</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="email-list data-list">
                <div class="blockelem create-flowy noselect email-single">
                    <input type="hidden" name='blockelemtype' class="blockelemtype" value="counsellor-email">
                    <div class="grabme">
                        <img src="{{ asset('plugins/flowy/assets/grabme.svg') }}">
                    </div>
                    <div class="blockin">
                        <div class="blockico">
                            <span></span>
                            <img src="{{ asset('plugins/flowy/assets/mail.svg') }}">
                        </div>
                        <div class="blocktext">
                            <p class="blocktitle">Email</p>
                            <p class="blockdesc">Delivery Source</p>
                        </div>
                    </div>
                </div>
                <div class="blockelem create-flowy noselect sms-single">
                    <input type="hidden" name='blockelemtype' class="blockelemtype" value="counsellor-sms">
                    <div class="grabme">
                        <img src="{{ asset('plugins/flowy/assets/grabme.svg') }}">
                    </div>
                    <div class="blockin">
                        <div class="blockico">
                            <span></span>
                            <img src="{{ asset('plugins/flowy/assets/sms.svg') }}">
                        </div>
                        <div class="blocktext">
                            <p class="blocktitle">SMS</p>
                            <p class="blockdesc">Delivery Source</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="propwrap">
        <div id="properties">
            <div id="close">
                <img src="{{ asset('plugins/flowy/assets/close.svg') }}">
            </div>
            <p id="header2">Properties</p>
            <div class="section-properties">                    
                
            </div>
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
        console.log(flowc);
        var sflowc = JSON.stringify(flowc);

        var sources = [];
        var commands = [];

        var blocks = flowc.blocks;
        $.each(blocks, function( index, singleblock ) {
            console.log(singleblock);
            var blockval = singleblock.data[0].value;
            var parent = singleblock.parent;

            if(parent == -1 ) {
                sources.push(blockval);
            } else {
                //console.log(singleblock.id);
                var id = singleblock.id;
                var parentid = singleblock.parent;

                commands.push({id: id, parentid: parentid, content: blockval});
                //console.log(blockval);
            }
        });

        sources = JSON.stringify(sources);
        commands = JSON.stringify(commands);
        //console.log(commands);
        //console.log(flowc.blocks[0].data[0].value);

        $.ajax({
            type    : 'POST',
            url     : '{{ route('automateemailflowsubmit') }}',
            data    : { sources: sources, commands: commands, sflowc: sflowc},
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
            url     : '{{ route('automateemailflowimport') }}',
            data    : { flowid: flowid},
            success: function(result){
                var output = JSON.parse(result);
                flowy.import(output);
                console.log(output);
                /*
                toastr["success"]("Data Successfully Added");
                console.log("Data Successfully Added");*/
                
            }
                    
        });
    });
    

</script>

</body>
</html>