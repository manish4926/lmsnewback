<!DOCTYPE html>
<html>
<head>
  <meta name="csrf-token" content="{{ csrf_token() }}" />
<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="{{ asset('css/parsley.css') }}">
  <script src="{{ asset('js/parsley.min.js') }}"></script>
  <style>
    body {
      margin: 0 auto;
      max-width: 800px;
      padding: 0 20px;
    }

    .container1 {
      border: 2px solid #dedede;
      background-color: #f1f1f1;
      border-radius: 5px;
      padding: 10px;
      margin: 10px 0;
      margin-right: 400px;
    }

    .darker {
      border-color: #ccc;
      background-color: #ddd;
      margin-left: 400px;
      margin-right: 0;
    }

    .container1 p {
      width: 80%;
      text-align: justify;
      word-wrap: break-word;
    }

    .white {
      border-color: #ccc;
      background-color: #fff;
    }

    .container1::after {
      content: "";
      clear: both;
      display: table;
    }

    .container1 img {
      float: left;
      max-width: 50px;
      width: 100%;
      margin-right: 20px;
      border-radius: 50%;
    }

    .container1 img.right {
      float: right;
      margin-left: 20px;
      margin-right:0;
    }

    .time-right {
      float: right;
      color: #aaa;
    }

    .time-left {
      float: left;
      color: #999;
    }

    div.sticky {
      position: -webkit-sticky;
      position: sticky;
      bottom: 0;
      margin-top: 200px;
      background-color: #ddd;
      padding: 10px 0 0 10px;
      font-size: 20px;
    }
    .square {
      height: auto;
      width: 810px;
      padding: 8px;
      background-color: #fff;
      border: 2px solid #dedede;
     
    }

    .container1.darker {
      margin-left: 400px;
    }

    .time-grouper span {
      margin: auto;
      padding: 5px 15px;
    }


    /*3rd party chatbot code*/
    /*.btn {
      display: none;
    }

    .square {
      width: auto;
      padding: 0;
      border: none;
    }

    .container1.darker {
        margin-left: 50px;
        margin-right: 0;
    }

    .container1 {
      margin-right: 50px;
      margin-left: 0;
    }*/
  </style>
</head>
<body>

<span id="ref">
<div class="square">
{{-- <center><h2>Chat Messages</h2></center> --}}

<br/>
  <div class="messagecontainer">
    @foreach($oldchats as $chats) 
      @if($chats->sender_type == 'bot')
        <div class="container1 darker">
          <img src="{{ asset('images/chatbot/chatbot.png') }}" alt="Avatar" class="right" style="width:100%;">
          <p>{!! $chats->message !!}</p>
          <span class="time-left">{{ date('M d,Y h:i A', strtotime($chats->created_at))}}</span>
        </div>
      @else
        <div class="container1">
          <img src="{{ asset('images/chatbot/user.jpg') }}" alt="Avatar" style="width:100%;">
          <p>{!! $chats->message !!}</p>
          <span class="time-left">{{ date('M d,Y h:i A', strtotime($chats->created_at))}}</span>
        </div>
      @endif
      
    @endforeach
    <div class="time-grouper d-flex">
      <span class="badge badge-pill badge-secondary">Today</span>
    </div>
    
  </div>
</div>
</span>
<br/>


@if(empty($oldchats))
<script>
  function addcookies() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    $.ajax({
        type  : 'POST',
        url   : '{{ route('addcookies') }}',
        data : {type: 'chatbotusers' },
      success: function(result){
        var chabotuserid = result;
        botmessage(1, 'bot');
        /*botmessage(2, 'bot');
        botmessage(3, 'bot');
        botmessage(4, 'bot');*/
      }           
    });
  }


  addcookies();       //Add Chatbot User in Cookies
  
</script>
@endif
<script type="text/javascript">
  function botmessage(messageid, messagetype, sectionid=null,buttontype =null) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    //console.log(messageid);
    $.ajax({
        type  : 'POST',
        url   : '{{ route('chatbotresponse') }}',
        data : {messageid: messageid , type: 'bot',buttontype: buttontype },
        success: function(result){
          var obj = JSON.parse(result);
          var newdate = formatDate(new Date);
          //console.log(obj);
          if(messagetype == 'bot') {
            if(obj['type'] == 'text' || buttontype == 'buttontext') {
              //console.log(obj['message']);
              
                if(buttontype == 'buttontext') {
                  $('.messagecontainer').append('<div class="container1"> \
                    <img src="{{ asset('images/chatbot/user.jpg') }}" alt="Avatar" style="width:100%;"> \
                    <p>'+ obj['message'] +'</p> \
                    <span class="time-left">'+ newdate +'</span> \
                  </div>');  
                } else {
                  $('.messagecontainer').append('<div class="container1 darker"> \
                    <img src="{{ asset('images/chatbot/chatbot.png') }}" alt="Avatar" class="right" style="width:100%;"> \
                    <p>'+ obj['message'] +'</p> \
                    <span class="time-left">'+ newdate +'</span> \
                  </div>');    
                }
                

                substr = obj.child;
                //console.log(substr);

                if(substr != '') {
                  var randid = Math.floor(Math.random() * (100 - 10 + 1) + 10);
                  
                  $('.messagecontainer').append('<div id="'+randid+'" class="childbox"></div>');

                  var arr = [];
                  
                  substr.forEach(function(item) {
                      // do something with `item
                        botmessage(item, 'bot', randid);  
                      
                      
                      //arr.push(item);
                  });
                  //console.log(arr);
                  //botchildmessage(JSON.stringify(arr), 'bot', randid);

                } else {
                  showLastOptions(); 
                }
                var height = $('.messagecontainer').height() + 1000;
                $('.messagecontainer').animate({scrollTop: height});
            }
            else if(obj['type'] == 'form') {
              //console.log('Show me form');
                $('.messagecontainer').append(' \
                  <div class="container1 white"> \
                    <form id="submitinfoform" data-parsley-validate> \
                      {{ csrf_field() }} \
                      <div class="form-group"> \
                        <label>Name<span class="required-astrik">*</span></label> \
                        <input class="form-control" type="text" name="name" required="">   \
                      </div> \
                      <div class="form-group"> \
                        <label>Email<span class="required-astrik">*</span></label> \
                        <input class="form-control" type="email" name="email" required="">   \
                      </div> \
                      <div class="form-group"> \
                        <label>Phone<span class="required-astrik">*</span></label> \
                        <input class="form-control" type="tel" name="phone" required="">   \
                      </div> \
                      <div class="form-group"> \
                        <label>City<span class="required-astrik">*</span></label> \
                        <input class="form-control" type="text" name="city" required="">   \
                      </div> \
                      <div class="form-group"> \
                        <label>Program Interested in<span class="required-astrik">*</span></label> \
                        <select name="course" class="form-control" required=""> \
                          <option selected readonly>Program Interested in</option> \
                          <option>PGDM</option> \
                          <option>PGDM-IB</option> \
                          <option>PGDM-RM</option> \
                          <option>FPM Programme</option> \
                          <option>MCA</option> \
                          <option>BCA</option> \
                          <option>BBA</option> \
                        </select> \
                      </div> \
                      <div class="form-group"> \
                        <input type="submit" name="submitinfoform">  \
                      </div> \
                    </form> \
                  </div> \
                  ');
            }
            else if(obj['type'] == 'button') {
              $('#'+sectionid).append('<button class="eventbutton" data-id='+ obj['id'] +' >'+ obj['message'] +'</button>');  
                //botchildmessage(JSON.stringify(arr), 'bot', randid);
              }
            
          } else {
            //User Side Message

          }
          //console.log(obj['message']);
          //$(".messagecontainer").stop().animate({ scrollTop: $(".messagecontainer")[0].scrollHeight}, 10);
          /*console.log('process');
          $('.square').stop ().animate ({
  scrollTop: $('.square')[0].scrollHeight
});*/

        }           
    });
  }


  $(document).on('submit', '#submitinfoform', function(event){    

    event.preventDefault();
    var myform = $(this);
    if ( $(this).parsley().isValid() ) {
      $.ajax({
          type  : 'POST',
          url   : '{{ route('infoformsubmit') }}',
          data : myform.serialize(),
          success: function(result){
            //console.log(result);
            //Hide Previous Form and Show Details
            var obj = JSON.parse(result);
            $('.messagecontainer').append('<div class="container1 darker"> \
                <img src="{{ asset('images/chatbot/user.jpg') }}" alt="Avatar" style="width:100%;"> \
                <p>'+ obj['response'] +'</p> \
                <span class="time-left">Date Time (Human)</span> \
              </div>');  

            botmessage(5, 'bot');
          }           
      });
    }
  });


  $(document).ready(function() {
      @if(!empty($oldchats))
        botmessage(6, 'bot');
        
      @endif

      $("body").on("click",".eventbutton",function(){
          let id = $(this).data('id');
          $('#'+id).hide();
          //console.log(id);
          var sectionid = null;
          var buttontype = 'buttontext';
          botmessage(id, 'bot',sectionid, buttontype);
          $(this).parent('.childbox').hide();

      });
  });
  

function formatDate(dt) {
  var d = dt.getDate();
  var month = dt.toLocaleString('default', { month: 'short' });
  var year = dt.getFullYear();
  var hours = dt.getHours();
  var minutes = dt.getMinutes();
  var ampm = hours >= 12 ? 'PM' : 'AM';
  hours = hours % 12;
  hours = hours ? hours : 12; // the hour '0' should be '12'
  minutes = minutes < 10 ? '0'+minutes : minutes;
  var strTime = month + " "+ d + "," + year + " " + hours + ':' + minutes + ' ' + ampm;
  return strTime;
}

function showLastOptions() {
  console.log('We are showing you last options');
  botmessage(6, 'bot');

}
</script>
</body>
</html>