document.addEventListener("DOMContentLoaded", function(){
    var rightcard = false;
    var tempblock;
    var tempblock2;
    //document.getElementById("blocklist").innerHTML = '<div class="blockelem create-flowy noselect"><input type="hidden" name="blockelemtype" class="blockelemtype" value="1"><div class="grabme"><img src="../plugins/flowy/assets/grabme.svg"></div><div class="blockin">                  <div class="blockico"><span></span><img src="../plugins/flowy/assets/eye.svg"></div><div class="blocktext">                        <p class="blocktitle">New visitor</p><p class="blockdesc">Triggers when somebody visits a specified page</p>        </div></div></div><div class="blockelem create-flowy noselect"><input type="hidden" name="blockelemtype" class="blockelemtype" value="2"><div class="grabme"><img src="../plugins/flowy/assets/grabme.svg"></div><div class="blockin">                    <div class="blockico"><span></span><img src="../plugins/flowy/assets/action.svg"></div><div class="blocktext">                        <p class="blocktitle">Action is performed</p><p class="blockdesc">Triggers when somebody performs a specified action</p></div></div></div><div class="blockelem create-flowy noselect"><input type="hidden" name="blockelemtype" class="blockelemtype" value="3"><div class="grabme"><img src="../plugins/flowy/assets/grabme.svg"></div><div class="blockin">                    <div class="blockico"><span></span><img src="../plugins/flowy/assets/time.svg"></div><div class="blocktext">                        <p class="blocktitle">Time has passed</p><p class="blockdesc">Triggers after a specified amount of time</p>          </div></div></div><div class="blockelem create-flowy noselect"><input type="hidden" name="blockelemtype" class="blockelemtype" value="4"><div class="grabme"><img src="../plugins/flowy/assets/grabme.svg"></div><div class="blockin">                    <div class="blockico"><span></span><img src="../plugins/flowy/assets/error.svg"></div><div class="blocktext">                        <p class="blocktitle">Error prompt</p><p class="blockdesc">Triggers when a specified error happens</p>              </div></div></div>';
    flowy(document.getElementById("canvas"), drag, release, snapping);
    function addEventListenerMulti(type, listener, capture, selector) {
        var nodes = document.querySelectorAll(selector);
        for (var i = 0; i < nodes.length; i++) {
            nodes[i].addEventListener(type, listener, capture);
        }
    }
    function snapping(drag, first) {
        var grab = drag.querySelector(".grabme");
        grab.parentNode.removeChild(grab);
        var blockin = drag.querySelector(".blockin");
        /*blockin.parentNode.removeChild(blockin);
        if (drag.querySelector(".blockelemtype").value == "1") {
            drag.innerHTML += "<div class='blockyleft'><img src='../plugins/flowy/assets/eyeblue.svg'><p class='blockyname'>New visitor</p></div><div class='blockyright'><img src='../plugins/flowy/assets/more.svg'></div><div class='blockydiv'></div><div class='blockyinfo'>When a <span>new visitor</span> goes to <span>Site 1</span></div>";
        } else if (drag.querySelector(".blockelemtype").value == "2") {
            drag.innerHTML += "<div class='blockyleft'><img src='../plugins/flowy/assets/actionblue.svg'><p class='blockyname'>Action is performed</p></div><div class='blockyright'><img src='../plugins/flowy/assets/more.svg'></div><div class='blockydiv'></div><div class='blockyinfo'>When <span>Action 1</span> is performed</div>";
        } else if (drag.querySelector(".blockelemtype").value == "3") {
            drag.innerHTML += "<div class='blockyleft'><img src='../plugins/flowy/assets/timeblue.svg'><p class='blockyname'>Time has passed</p></div><div class='blockyright'><img src='../plugins/flowy/assets/more.svg'></div><div class='blockydiv'></div><div class='blockyinfo'>When <span>10 seconds</span> have passed</div>";
        } else if (drag.querySelector(".blockelemtype").value == "4") {
            drag.innerHTML += "<div class='blockyleft'><img src='../plugins/flowy/assets/errorblue.svg'><p class='blockyname'>Error prompt</p></div><div class='blockyright'><img src='../plugins/flowy/assets/more.svg'></div><div class='blockydiv'></div><div class='blockyinfo'>When <span>Error 1</span> is triggered</div>";
        } else if (drag.querySelector(".blockelemtype").value == "5") {
            drag.innerHTML += "<div class='blockyleft'><img src='../plugins/flowy/assets/databaseorange.svg'><p class='blockyname'>New database entry</p></div><div class='blockyright'><img src='../plugins/flowy/assets/more.svg'></div><div class='blockydiv'></div><div class='blockyinfo'>Add <span>Data object</span> to <span>Database 1</span></div>";
        } else if (drag.querySelector(".blockelemtype").value == "6") {
            drag.innerHTML += "<div class='blockyleft'><img src='../plugins/flowy/assets/databaseorange.svg'><p class='blockyname'>Update database</p></div><div class='blockyright'><img src='../plugins/flowy/assets/more.svg'></div><div class='blockydiv'></div><div class='blockyinfo'>Update <span>Database 1</span></div>";
        } else if (drag.querySelector(".blockelemtype").value == "7") {
            drag.innerHTML += "<div class='blockyleft'><img src='../plugins/flowy/assets/actionorange.svg'><p class='blockyname'>Perform an action</p></div><div class='blockyright'><img src='../plugins/flowy/assets/more.svg'></div><div class='blockydiv'></div><div class='blockyinfo'>Perform <span>Action 1</span></div>";
        } else if (drag.querySelector(".blockelemtype").value == "8") {
            drag.innerHTML += "<div class='blockyleft'><img src='../plugins/flowy/assets/twitterorange.svg'><p class='blockyname'>Make a tweet</p></div><div class='blockyright'><img src='../plugins/flowy/assets/more.svg'></div><div class='blockydiv'></div><div class='blockyinfo'>Tweet <span>Query 1</span> with the account <span>@alyssaxuu</span></div>";
        } else if (drag.querySelector(".blockelemtype").value == "9") {
            drag.innerHTML += "<div class='blockyleft'><img src='../plugins/flowy/assets/logred.svg'><p class='blockyname'>Add new log entry</p></div><div class='blockyright'><img src='../plugins/flowy/assets/more.svg'></div><div class='blockydiv'></div><div class='blockyinfo'>Add new <span>success</span> log entry</div>";
        } else if (drag.querySelector(".blockelemtype").value == "10") {
            drag.innerHTML += "<div class='blockyleft'><img src='../plugins/flowy/assets/logred.svg'><p class='blockyname'>Update logs</p></div><div class='blockyright'><img src='../plugins/flowy/assets/more.svg'></div><div class='blockydiv'></div><div class='blockyinfo'>Edit <span>Log Entry 1</span></div>";
        } else if (drag.querySelector(".blockelemtype").value == "11") {
            drag.innerHTML += "<div class='blockyleft'><img src='../plugins/flowy/assets/errorred.svg'><p class='blockyname'>Prompt an error</p></div><div class='blockyright'><img src='../plugins/flowy/assets/more.svg'></div><div class='blockydiv'></div><div class='blockyinfo'>Trigger <span>Error 1</span></div>";
        }*/
        return true;
    }
    function drag(block) {
        block.classList.add("blockdisabled");
        tempblock2 = block;
    }
    function release() {
        if (tempblock2) {
            tempblock2.classList.remove("blockdisabled");
        }
    }
    var disabledClick = function(){
        document.querySelector(".navactive").classList.add("navdisabled");
        document.querySelector(".navactive").classList.remove("navactive");
        this.classList.add("navactive");
        this.classList.remove("navdisabled");
        /*if (this.getAttribute("id") == "triggers") {
            document.getElementById("blocklist").innerHTML = '<div class="blockelem create-flowy noselect"><input type="hidden" name="blockelemtype" class="blockelemtype" value="1"><div class="grabme"><img src="../plugins/flowy/assets/grabme.svg"></div><div class="blockin">                  <div class="blockico"><span></span><img src="../plugins/flowy/assets/eye.svg"></div><div class="blocktext">                        <p class="blocktitle">New visitor</p><p class="blockdesc">Triggers when somebody visits a specified page</p>        </div></div></div><div class="blockelem create-flowy noselect"><input type="hidden" name="blockelemtype" class="blockelemtype" value="2"><div class="grabme"><img src="../plugins/flowy/assets/grabme.svg"></div><div class="blockin">                    <div class="blockico"><span></span><img src="../plugins/flowy/assets/action.svg"></div><div class="blocktext">                        <p class="blocktitle">Action is performed</p><p class="blockdesc">Triggers when somebody performs a specified action</p></div></div></div><div class="blockelem create-flowy noselect"><input type="hidden" name="blockelemtype" class="blockelemtype" value="3"><div class="grabme"><img src="../plugins/flowy/assets/grabme.svg"></div><div class="blockin">                    <div class="blockico"><span></span><img src="../plugins/flowy/assets/time.svg"></div><div class="blocktext">                        <p class="blocktitle">Time has passed</p><p class="blockdesc">Triggers after a specified amount of time</p>          </div></div></div><div class="blockelem create-flowy noselect"><input type="hidden" name="blockelemtype" class="blockelemtype" value="4"><div class="grabme"><img src="../plugins/flowy/assets/grabme.svg"></div><div class="blockin">                    <div class="blockico"><span></span><img src="../plugins/flowy/assets/error.svg"></div><div class="blocktext">                        <p class="blocktitle">Error prompt</p><p class="blockdesc">Triggers when a specified error happens</p>              </div></div></div>';
        } else if (this.getAttribute("id") == "actions") {
            document.getElementById("blocklist").innerHTML = '<div class="blockelem create-flowy noselect"><input type="hidden" name="blockelemtype" class="blockelemtype" value="5"><div class="grabme"><img src="../plugins/flowy/assets/grabme.svg"></div><div class="blockin">                  <div class="blockico"><span></span><img src="../plugins/flowy/assets/database.svg"></div><div class="blocktext">                        <p class="blocktitle">New database entry</p><p class="blockdesc">Adds a new entry to a specified database</p>        </div></div></div><div class="blockelem create-flowy noselect"><input type="hidden" name="blockelemtype" class="blockelemtype" value="6"><div class="grabme"><img src="../plugins/flowy/assets/grabme.svg"></div><div class="blockin">                  <div class="blockico"><span></span><img src="../plugins/flowy/assets/database.svg"></div><div class="blocktext">                        <p class="blocktitle">Update database</p><p class="blockdesc">Edits and deletes database entries and properties</p>        </div></div></div><div class="blockelem create-flowy noselect"><input type="hidden" name="blockelemtype" class="blockelemtype" value="7"><div class="grabme"><img src="../plugins/flowy/assets/grabme.svg"></div><div class="blockin">                  <div class="blockico"><span></span><img src="../plugins/flowy/assets/action.svg"></div><div class="blocktext">                        <p class="blocktitle">Perform an action</p><p class="blockdesc">Performs or edits a specified action</p>        </div></div></div><div class="blockelem create-flowy noselect"><input type="hidden" name="blockelemtype" class="blockelemtype" value="8"><div class="grabme"><img src="../plugins/flowy/assets/grabme.svg"></div><div class="blockin">                  <div class="blockico"><span></span><img src="../plugins/flowy/assets/twitter.svg"></div><div class="blocktext">                        <p class="blocktitle">Make a tweet</p><p class="blockdesc">Makes a tweet with a specified query</p>        </div></div></div>';
        } else if (this.getAttribute("id") == "loggers") {
            document.getElementById("blocklist").innerHTML = '<div class="blockelem create-flowy noselect"><input type="hidden" name="blockelemtype" class="blockelemtype" value="9"><div class="grabme"><img src="../plugins/flowy/assets/grabme.svg"></div><div class="blockin">                  <div class="blockico"><span></span><img src="../plugins/flowy/assets/log.svg"></div><div class="blocktext">                        <p class="blocktitle">Add new log entry</p><p class="blockdesc">Adds a new log entry to this project</p>        </div></div></div><div class="blockelem create-flowy noselect"><input type="hidden" name="blockelemtype" class="blockelemtype" value="10"><div class="grabme"><img src="../plugins/flowy/assets/grabme.svg"></div><div class="blockin">                  <div class="blockico"><span></span><img src="../plugins/flowy/assets/log.svg"></div><div class="blocktext">                        <p class="blocktitle">Update logs</p><p class="blockdesc">Edits and deletes log entries in this project</p>        </div></div></div><div class="blockelem create-flowy noselect"><input type="hidden" name="blockelemtype" class="blockelemtype" value="11"><div class="grabme"><img src="../plugins/flowy/assets/grabme.svg"></div><div class="blockin">                  <div class="blockico"><span></span><img src="../plugins/flowy/assets/error.svg"></div><div class="blocktext">                        <p class="blocktitle">Prompt an error</p><p class="blockdesc">Triggers a specified error</p>        </div></div></div>';
        }*/
    }
    addEventListenerMulti("click", disabledClick, false, ".side");
    document.getElementById("close").addEventListener("click", function(){
       if (rightcard) {
           rightcard = false;
           document.getElementById("properties").classList.remove("expanded");
           setTimeout(function(){
                document.getElementById("propwrap").classList.remove("itson"); 
           }, 300);
            $('.section-properties').html('');
            tempblock.classList.remove("selectedblock");
       } 
    });
    
/*document.getElementById("removeblock").addEventListener("click", function(){
 flowy.deleteBlocks();
});*/
var aclick = false;
var noinfo = false;
var blocksection = "";
var beginTouch = function (event) {
    aclick = true;
    noinfo = false;
    if (event.target.closest(".create-flowy")) {
        noinfo = true;
    }
}
var checkTouch = function (event) {
    aclick = false;
}
var doneTouch = function (event) {
    if (event.type === "mouseup" && aclick && !noinfo) {
      if (!rightcard && event.target.closest(".block") && !event.target.closest(".block").classList.contains("dragging")) {
            tempblock = event.target.closest(".block");
            rightcard = true;
            document.getElementById("properties").classList.add("expanded");
            document.getElementById("propwrap").classList.add("itson");
            tempblock.classList.add("selectedblock");
            blocksection = tempblock;
            //Code By Manish
            if(tempblock.classList.contains("email-single")) {
                
                var content = '<p class="inputlabel">Select Email</p>';
                content += '<select class="emaillist">';
                $.each(emailtemplatelist, function( index, value ) {
                  content += '<option value="'+value.id+'">'+value.name+'</option>';
                });
                content += '</select>';
                $('.section-properties').append(content);

                var content = '<p class="inputlabel">Select Days Delay</p>';
                content += '<select class="dayslist">';
                for (i = 0; i < 10; i++) {
                    content += '<option value="'+i+'">After '+i+'</option>';
                }content += '</select>';
                $('.section-properties').append(content);

                var content = '<p class="inputlabel">Select Category</p>';
                content += '<select class="categorylist"> \
                    <option>Intersted Data List</option> \
                    <option>Call Not Connected Data</option> \
                    <option>Junk Data List</option> \
                </select>';
                $('.section-properties').append(content);
                //alert('Its a Email');
                $('.section-properties').append('<button class="active-btn" onclick="emailupdate()">Done</button>');
            }

            else if(tempblock.classList.contains("email-nocategory-single")) {
                
                var content = '<p class="inputlabel">Select Email</p>';
                content += '<select class="emaillist">';
                $.each(emailtemplatelist, function( index, value ) {
                  content += '<option value="'+value.id+'">'+value.name+'</option>';
                });
                content += '</select>';
                $('.section-properties').append(content);

                var content = '<p class="inputlabel">Select Days Delay</p>';
                content += '<select class="dayslist">';
                for (i = 0; i < 10; i++) {
                    content += '<option value="'+i+'">After '+i+'</option>';
                }content += '</select>';
                $('.section-properties').append(content);
                $('.section-properties').append("<br><br>");
                //alert('Its a Email');
                $('.section-properties').append('<button class="active-btn" onclick="emailupdate()">Done</button>');
            }

            else if(tempblock.classList.contains("sms-single")) {
                
                var content = '<p class="inputlabel">Select SMS</p>';
                content += '<select class="smslist">';
                $.each(messagelist, function( index, value ) {
                  content += '<option value="'+value.id+'">'+value.name+'</option>';
                });
                content += '</select>';
                $('.section-properties').append(content);

                var content = '<p class="inputlabel">Select Days Delay</p>';
                content += '<select class="dayslist">';
                for (i = 0; i < 10; i++) {
                    content += '<option value="'+i+'">After '+i+'</option>';
                }content += '</select>';
                $('.section-properties').append(content);

                var content = '<p class="inputlabel">Select Category</p>';
                content += '<select class="categorylist"> \
                    <option>Intersted Data List</option> \
                    <option>Call Not Connected Data</option> \
                    <option>Junk Data List</option> \
                </select>';
                $('.section-properties').append(content);
                //alert('Its a Email');
                $('.section-properties').append('<button class="active-btn" onclick="smsupdate()">Done</button>');
            }
       } 
    }
}
addEventListener("mousedown", beginTouch, false);
addEventListener("mousemove", checkTouch, false);
addEventListener("mouseup", doneTouch, false);
addEventListenerMulti("touchstart", beginTouch, false, ".block");


});

function emailupdate() {
    var emailselected = $('.emaillist option:selected').text();
    var daysselected = $('.dayslist option:selected').text();
    var categoryselected = $('.categorylist option:selected').text();
    var emailselectedval = $('.emaillist option:selected').val();
    var daysselectedval = $('.dayslist option:selected').val();
    var categoryselectedval = $('.categorylist option:selected').val();
    var selectedblock = $('.selectedblock');
    var blockdesc = $('.selectedblock').find('.blockdesc');
    var content = '<p>Template: '+emailselected+' <span class="selected-email-template hide-span">'+emailselectedval+'</span> <br>\
    Delay: '+daysselected+' Days <span class="selected-delay-days hide-span">'+daysselectedval+'</span> <br>   \
    Category: '+categoryselected+' Days <span class="selected-delay-days hide-span">'+categoryselectedval+'</span> <br>   \
    </p>';
    blockdesc.html(content);
    var finalval = "send.email-"+"template."+emailselectedval+"-delay."+daysselectedval+"-categorylist."+categoryselectedval;
    $('.selectedblock').find("input[name='blockelemtype']").val(finalval);
    //$('.selectedblock').hide();
};


function smsupdate() {
    var smsselected = $('.smslist option:selected').text();
    var daysselected = $('.dayslist option:selected').text();
    var categoryselected = $('.categorylist option:selected').text();
    var smsselectedval = $('.smslist option:selected').val();
    var daysselectedval = $('.dayslist option:selected').val();
    var categoryselectedval = $('.categorylist option:selected').val();
    var selectedblock = $('.selectedblock');
    var blockdesc = $('.selectedblock').find('.blockdesc');
    var content = '<p>Template: '+smsselected+' <span class="selected-sms-template hide-span">'+smsselectedval+'</span> <br>\
    Delay: '+daysselected+' Days <span class="selected-delay-days hide-span">'+daysselectedval+'</span> <br>   \
    Category: '+categoryselected+' Days <span class="selected-delay-days hide-span">'+categoryselectedval+'</span> <br>   \
    </p>';
    blockdesc.html(content);
    var finalval = "send.sms-"+"template."+smsselectedval+"-delay."+daysselectedval+"-categorylist."+categoryselectedval;
    $('.selectedblock').find("input[name='blockelemtype']").val(finalval);
    //$('.selectedblock').hide();
};



