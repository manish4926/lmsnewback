<script type="text/javascript">
$(document).ready(function() {

/*function imgError(image) {
image.onerror = "";
image.src = "{{ asset('img/No-image-found.jpg') }}";
return true;
}*/

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
});

function reloadapidata() {
	$.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});
	console.log('Fetching Started');

	//Get Last Inserted Id
	// var lastinsertedidbeforehit = 0;
	// $.ajax({
	//     type	: 'POST',
	//     url		: '{{ route('getlastinsertedid') }}',
	//     data 	: { },
	// 	success: function(result){
	// 		lastinsertedidbeforehit = result;
	// 	}
	// });

	getRemarksReportTB();
	
	/*$.ajax({
	    type	: 'POST',
	    url		: '{{ route('getwebsitehome') }}',
	    data 	: { },
		success: function(result){
			toastr["success"]("Website Homepage Data Successfully Added");
			console.log("Website Homepage Data Successfully Added");
			//console.log(result);
		}
			    
	});*/

	/*$.ajax({
	    type	: 'POST',
	    url		: '{{ route('getwebsiteinner') }}',
	    data 	: { },
		success: function(result){
			toastr["success"]("Website Inner Page Data Successfully Added");
			console.log("Website Inner Page Data Successfully Added");
			//console.log(result);
		}
			    
	});*/

	/*$.ajax({
	    type	: 'POST',
	    url		: '{{ route('getgoogle') }}',
	    data 	: { },
		success: function(result){
			toastr["success"]("Google Data Successfully Added");
			console.log("Google Data Successfully Added");
			//console.log(result);
		}
			    
	});*/

	/*$.ajax({
	    type	: 'POST',
	    url		: '{{ route('getfacebook') }}',
	    data 	: { },
		success: function(result){
			toastr["success"]("Facebook Data Successfully Added");
			console.log("Facebook Data Successfully Added");
			//console.log(result);
		}
			    
	});*/

	/*$.ajax({
	    type	: 'POST',
	    url		: '{{ route('getmbauniverse') }}',
	    data 	: { },
		success: function(result){
			toastr["success"]("MBA Universe LP Data Successfully Added");
			console.log("MBA Universe LP Data Successfully Added");
			//console.log(result);
		}
			    
	});*/

	/*$.ajax({
	    type	: 'POST',
	    url		: '{{ route('getcollegedunialanding') }}',
	    data 	: { },
		success: function(result){
			toastr["success"]("College Dunia LP Data Successfully Added");
			console.log("College Dunia LP Data Successfully Added");
			//console.log(result);
		}
			    
	});*/

	/*$.ajax({
	    type	: 'POST',
	    url		: '{{ route('getipu') }}',
	    data 	: { },
		success: function(result){
			toastr["success"]("IPU Data Successfully Added");
			console.log("IPU Data Successfully Added");
			//console.log(result);
		}
			    
	});*/

	/*$.ajax({
	    type	: 'POST',
	    url		: '{{ route('getmocktest') }}',
	    data 	: { },
		success: function(result){
			toastr["success"]("Mock Test Data Successfully Added");
			console.log("Mock Test Data Successfully Added");
			//console.log(result);
		}
			    
	});*/


	// $.ajax({
	//     type	: 'POST',
	//     url		: '{{ route('getadmissionform') }}',
	//     data 	: { },
	// 	success: function(result){
	// 		toastr["success"]("Admission Form Data Successfully Added");
	// 		console.log("Admission Form Data Successfully Added");
	// 		//console.log(result);
	// 		//addVerificationMail(lastinsertedidbeforehit);
	// 		getAdmissionFormTB();
			
	// 	}
			    
	// });
}

function addVerificationMail(lastinsertedidbeforehit) {
	console.log('process started');
	$.ajax({
	    type	: 'POST',
	    url		: '{{ route('verificationmailhitter') }}',
	    data 	: { lastinsertedidbeforehit: lastinsertedidbeforehit},
		success: function(result){
			toastr["success"]("Processing Data Completed");
			console.log("Processing Data Completed");
			
		}
			    
	});
}

function getAdmissionFormTB() {
	console.log('report generation started');
	$.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});
	
	$.ajax({
	    type	: 'POST',
	    url		: '{{ route('generateformconversionreport') }}',
	    data 	: { },
		success: function(result){
			toastr["success"]("Admission Form Report Successfully Added");
			console.log("Admission Form Report Successfully Added");
			//console.log(result);
			getRemarksReportTB();
		}
			    
	});
}

function getRemarksReportTB() {
	console.log('report generation started');
	$.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});
	
	$.ajax({
	    type	: 'POST',
	    url		: '{{ route('generateremarksreportreport') }}',
	    data 	: { },
		success: function(result){
			toastr["success"]("Remarks Report Successfully Added");
			console.log("Remarks Report Successfully Added");
		}
			    
	});
}

function checkformexist(e) {
	$.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});
	var email = e.value;
	var length = email.length;
	if(length >= 5) {
		$.ajax({
		    type	: 'POST',
		    url		: '{{ route('checkemailexist') }}',
		    data 	: { email:email},
			success: function(result){
				//toastr["success"]("Facebook Data Successfully Added");
				//console.log("Facebook Data Successfully Added");
				console.log(result);
				if(result == 'false') {
					$('#emailexistmesssage').html('');
					$('#emailexistmesssage').hide();

				} else {
					/**/
					var url = '{{ route("followupdetail", ":id") }}';
					url = url.replace(':id', result);
					$('#emailexistmesssage').html('Email Already Exist. <a href="'+url+'">Click here to view details.</a>');
					$('#emailexistmesssage').show();

				}
				
			}
				    
		});
	}
  	
}



</script>