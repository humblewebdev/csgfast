jQuery.noConflict();

jQuery(function($) {

$(".AcceptWizTerms").click(function(){
  setTimeout(function() { $("#preModal").remove(); }, 2000);
  //alert("Agreement Modal Removed");
});

$("#doRegister").click(function(){  
    
	var suburl = "z_scripts/do_signup_user.php?type=submit"; // the script where you handle the form input.
	var successalert = '<div class="alert alert-success"><i class="icon-ok-circle"></i>&nbsp;';
	var erroralert = '<div class="alert alert-error"><i class="icon-warning-sign"></i>&nbsp;<button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>';
	var alertclose = '</div>';
	
	var successmsg = "Your registration has been successfully completed, you will recieve an email with further information on how to proceed.  Thank you.";
    var homehtml = '<div class="row-fluid"><div class="span12"><div class="well turq"><div class="well-header"><h5>Have a Great Day!</h5></div><div class="well-content align_center">'+successalert + successmsg + alertclose+'<br><a href="login.php" class="btn"><i class="icon-home"></i> Go Back to Login Page</a></div></div></div></div>';
	
	$('.doRegisterLoad').html('&nbsp;&nbsp;&nbsp;<img style="height: 25px;" src="http://greatamericandisposal.com/wp-content/plugins/wp-homepage-slideshow/images/loading.gif"/> Finalizing, Please Wait...');
	$('.doRegisterLoad').removeClass('btn dark_green');
	$('.doRegisterLoad').removeAttr('id');
	$.ajax({
		   type: "POST",
		   url: suburl,
		   data: $("#rootwizard_form").serialize(), // serializes the form's elements.
		   success: function(data)
		   {   
		       if(data.substr(-7) == "success"){ //If the page returned a string of "success"
			   //$('.widgets_area').html(homehtml).hide().html(homehtml).fadeIn('slow');
			   $('.submitmodal').html(homehtml).fadeIn('slow'); // added in, remove if unsuccessful
			   //removed .html(homehtml).hide()
			   
			   } else {
			   
					$("#reg_form_submit_response").html(data); //Display error messages from webpage
					$('.doRegisterLoad').html('<i class="icon-check"></i> Error, Try Again?'); //Put back register button to try again
					$('.doRegisterLoad').addClass('btn dark_green');
					$('.doRegisterLoad').attr('id', 'doRegister');
					} 
		   }
		 });
	

	return false; // avoid to execute the actual submit of the form.
	
});
		
    

		

});