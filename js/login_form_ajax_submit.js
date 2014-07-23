jQuery.noConflict();

jQuery(function($) {
    
	function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
    };
	
	var successalert = '<div class="alert alert-success"><i class="icon-ok-circle"></i>&nbsp;';
	var erroralert = '<div class="alert alert-error"><i class="icon-warning-sign"></i>&nbsp;<button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>';
	var alertclose = '</div>';
	
	//Sign In Response Codes: 
	var r000 = 'Broken response from login authentication';
	var r0 = 'Account not activated';
	var r1 = 'Password must be updated before login';
	var r2 = 'Password is not correct';
	var r3 = 'Invalid information supplied';
	var r4 = 'Correct User';
	var r5 = 'Correct LDAP User';
	var r6 = 'Correct Admin';
	var r7 = 'Invalid information for LDAP Authentication';
	
	//Password Reset Response Codes: 
	var r01 = 'Reset Success! Check your email for your new password.';
	var r02 = 'Email does not exist!';
	
	//Password Update Response Codes: 
	var r000 = 'Broken response from Reset authentication';
	var r11 = 'Password has been updated, a notice has been sent to your email';
	var r12 = 'User does not exist';
	var r13 = 'Password does not meet these complexity requirements.  <br><br>* contain at least (1) upper case letter<br>* contain at least (1) lower case letter<br>* contain at least (1) number or special character<br>* contain at least (7) characters in length';

$("#doSignIn").click(function(){
    
	var username = $("#username").val();
	var pwd = $("#password").val();
	var required = "Username is required. Password is required.";
	
	if(username == "" || pwd == "")
	{
	  $('#notify_msg').html(erroralert + required + alertclose).hide().html(erroralert + required + alertclose).fadeIn('slow');
	  return false;
	}
	
	var suburl = "z_scripts/do_login_user.php?type=Login"; // the script where you handle the form input.
	
	$.ajax({
		   type: "POST",
		   url: suburl,
		   data: $("#login_form").serialize(), // serializes the form's elements.
		   success: function(data)
		   {   
		        //alert(data);
		        switch(data)
				{
				case '0':
				  $('#notify_msg').html(erroralert + r0 + alertclose).hide().html(erroralert + r0 + alertclose).fadeIn('slow');
				  break;
				case '1':
				   $('#notify_msg').html(erroralert + r1 + alertclose).hide().html(erroralert + r1 + alertclose).fadeIn('slow');
				   $('#email_confirm').val(username);
				   $('#email_confirm_text').html(username);
				   $('#reset-pw').modal('show');
				  break;  
				case '2':
				   $('#notify_msg').html(erroralert + r2 + alertclose).hide().html(erroralert + r2 + alertclose).fadeIn('slow');
				  break;
				case '3':
				   $('#notify_msg').html(erroralert + r3 + alertclose).hide().html(erroralert + r3 + alertclose).fadeIn('slow');
				  break;
				case '4':
				   $('#notify_msg').html(successalert + r4 + alertclose).hide().html(successalert + r4 + alertclose).fadeIn('slow');
				   window.location.href="home_page_farm.php";
				  break;
				case '5':
				   $('#notify_msg').html(successalert + r5 + alertclose).hide().html(successalert + r5 + alertclose).fadeIn('slow');
				   window.location.href="home_page_ldap.php";
				  break;
				case '6':
				   $('#notify_msg').html(successalert + r6 + alertclose).hide().html(successalert + r6 + alertclose).fadeIn('slow');
				   window.location.href="home_page_admin.php";
				  break;
				case '7':
				   $('#notify_msg').html(erroralert + r7 + alertclose).hide().html(erroralert + r7 + alertclose).fadeIn('slow');
				  break;
				default:
				   $('#notify_msg').html(successalert + r000 + data + alertclose).hide().html(successalert + data + r000 + alertclose).fadeIn('slow');
				} 
		   }
		 });
	
	return false; // avoid to execute the actual submit of the form.
});

$(document).keypress(function(e){
	if(e.which == 13){//Enter key pressed
		$('#doSignIn').click();//Trigger search button click event
	}
});

$("#doPwdReset").click(function(){

	var suburl = "z_scripts/do_user_pwd_reset.php?type=Reset"; // the script where you handle the form input.
	
	var email_reset = $("#email_reset").val();
	var required = "Email address required.";
	
	if(email_reset == "" || !isValidEmailAddress(email_reset))
	{
	  $('#notify_msg_modal').html(erroralert + required + alertclose).hide().html(erroralert + required + alertclose).fadeIn('slow');
	  return false;
	}
	
	$.ajax({
		   type: "POST",
		   url: suburl,
		   data: $("#pwd_reset_form").serialize(), // serializes the form's elements.
		   success: function(data)
		   {   
		        //alert(data);
			   
			   switch(data)
				{
				case '01':
				  $('.reset_button').html('<a href="#" class="btn btn-block green" ><i class=icon-check></i> Success</a>');
				  $('#notify_msg_modal').html(successalert + r01 + alertclose).hide().html(successalert + r01 + alertclose).fadeIn('slow');
				  break;
				case '02':
				   $('#notify_msg_modal').html(erroralert + r02 + alertclose).hide().html(erroralert + r02 + alertclose).fadeIn('slow');
				  break;  
				default:
				   $('#notify_msg_modal').html(erroralert + r000 + alertclose).hide().html(erroralert + r000 + alertclose).fadeIn('slow');
				} 
			   			  
		   }
		 });
	
	return false; // avoid to execute the actual submit of the form.
});

$("#doPwdUpdate").click(function(){

	var suburl = "z_scripts/do_user_pwd_reset.php?type=Update"; // the script where you handle the form input.
	
	var newpwd = $("#newpwd").val();
	var conpwd = $("#conpwd").val();
	var required = "New Password required.";
	var pwdmatch = "Passwords do not match.";
	
	if(newpwd == "")
	{
	  $('#notify_msg_modal2').html(erroralert + required + alertclose).hide().html(erroralert + required + alertclose).fadeIn('slow');
	  return false;
	}
	
	if(newpwd != conpwd)
	{
	  $('#notify_msg_modal2').html(erroralert + pwdmatch + alertclose).hide().html(erroralert + pwdmatch + alertclose).fadeIn('slow');
	  return false;
	}
	
	$.ajax({
		   type: "POST",
		   url: suburl,
		   data: $("#pwd_update_form").serialize(), // serializes the form's elements.
		   success: function(data)
		   {   
		      //alert(data);
			  switch(data)
			   {
			    case '11':
				   $('.update_button').html('<a href="#" class="btn btn-block green" ><i class=icon-check></i> Success</a>');
				   $('#notify_msg').html('');
				   $('#notify_msg_modal2').html(successalert + r11 + alertclose).hide().html(successalert + r11 + alertclose).fadeIn('slow');
				   window.location.href="home_autoredirect.php";
				  break;
				case '12':
				   $('#notify_msg_modal2').html(erroralert + r12 + alertclose).hide().html(erroralert + r12 + alertclose).fadeIn('slow');
				  break;
				case '13':
				   $('#notify_msg_modal2').html(erroralert + r13 + alertclose).hide().html(erroralert + r13 + alertclose).fadeIn('slow');
				  break;
				default:
				   $('#notify_msg_modal2').html(erroralert + r000 + alertclose).hide().html(erroralert + r000 + alertclose).fadeIn('slow');
				} 
			   
			  
		   }
		 });
	
	return false; // avoid to execute the actual submit of the form.
});

});