jQuery.noConflict();

jQuery(function($) {

//The user id of the currently logged in user
var users_id = $('#hidden_users_id').val();




$(".mask-phone").mask("(999) 999-9999");
$(".mask-zip").mask("99999");
$(".mask-digits").mask("?99999");

$('.wysihtml5-sandbox').wysihtml5({
			"font-styles": false, //Font styling, e.g. h1, h2, etc. Default true
			"emphasis": true, //Italics, bold, etc. Default true
			"lists": false, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
			"html": false, //Button which allows you to edit the generated HTML. Default false
			"link": false, //Button to insert a link. Default true
			"image": false, //Button to insert an image. Default true,
			"color": false //Button to change color of font  
			});
$('.uniform').uniform();

$('.chosen').chosen();


$('.footable').footable();
            $('.responsive_table_scroll').mCustomScrollbar({
                set_height: 400,
                advanced:{
                    updateOnContentResize: true,
                    updateOnBrowserResize: true
                }
            });

if ( ($(window).width() <= 750)){
    $('.desk_comp').hide();
	$('.phone_comp').show();
	$(".select").removeClass("chosen");
} else {
    $('.phone_comp').hide();
    $('.desk_comp').show();
}

$(window).resize(function(){
if ( ($(window).width() <= 750)){
    $('.desk_comp').hide();
	$('.phone_comp').show();
	$(".select").removeClass("chosen");
	$(".select").removeClass("chzn-done");
} else {
    $('.phone_comp').hide();
    $('.desk_comp').show();
}
});

$.fn.editable.defaults.mode = 'popup';

$(document).ready(function() {
    $('.updateable').editable();
});







/*---------------------------------------------------------------------------------
* ******Amber Added*****
* Function called when user wants to reset password, 
* data is posted to script for processing
*
----------------------------------------------------------------------------------*/

//On email field update run check for duplicate user with same info
$('#changeemail').on('input focusout', function() {
	var email = $(this).val();
	$('#dup_email').html('<img style="height: 25px;" src="http://greatamericandisposal.com/wp-content/plugins/wp-homepage-slideshow/images/loading.gif"/>');
	$('#dup_email').load('z_scripts/do_check_dup_user.php?email='+email);
});

var successalert = '<div class="alert alert-success"><i class="icon-ok-circle"></i>&nbsp;';
var erroralert = '<div class="alert alert-error"><i class="icon-warning-sign"></i>&nbsp;<button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>';
var alertclose = '</div>';

//Password Update Response Codes: 
	var r000 = 'Broken response from Reset authentication';
	var r11 = 'Password has been updated, a notice has been sent to your email';
	var r12 = 'User does not exist';
	var r13 = 'Password does not meet these complexity requirements.  <br><br>* contain at least (1) upper case letter<br>* contain at least (1) lower case letter<br>* contain at least (1) number or special character<br>* contain at least (7) characters in length';
	var r14 = 'Current Password is not correct';

$("#changeyourpassword").click(function(){

	var suburl = "z_scripts/do_user_pwd_reset.php?type=ChangePW"; // the script where you handle the form input.
	
	var newpwd = $("#new_password").val();
	var conpwd = $("#confirm_password").val();
	var required = "New Password required.";
	var pwdmatch = "Passwords do not match.";
	
	if(newpwd == "")
	{
		
	//	document.getElementById("new_password").innerHTML = "<font color='red'>Invalid: <?php echo trim($email);?> is already in use.</font>";
	  $('#modal_notify_msg').html(erroralert + required + alertclose).hide().html(erroralert + required + alertclose).fadeIn('slow');
	  return false;
	}
	
	if(newpwd != conpwd)
	{
	  $('#modal_notify_msg').html(erroralert + pwdmatch + alertclose).hide().html(erroralert + pwdmatch + alertclose).fadeIn('slow');
	  return false;
	}
	
	$.ajax({
		   type: "POST",
		   url: suburl,
		   data: $("#change_password_form").serialize(), // serializes the form's elements.
		   success: function(data)
		   {   
		      //alert(data);
			  switch(data)
			   {
			    case '11':
				   $('.update_pass').html('<a href="#" class="btn btn-block green" ><i class=icon-check></i> Success</a>');
				   //$('#notify_msg').html('');
				   $('#modal_notify_msg').html(successalert + r11 + alertclose).hide().html(successalert + r11 + alertclose).fadeIn('slow');
				   window.location.href="home_autoredirect.php";
				  break;
				case '12':
				   $('#modal_notify_msg').html(erroralert + r12 + alertclose).hide().html(erroralert + r12 + alertclose).fadeIn('slow');
				  break;
				case '13':
				   $('#modal_notify_msg').html(erroralert + r13 + alertclose).hide().html(erroralert + r13 + alertclose).fadeIn('slow');
				  break;
				case '14':
				   $('#modal_notify_msg').html(erroralert + r14 + alertclose).hide().html(erroralert + r14 + alertclose).fadeIn('slow');
				  break;
				default:
				   $('#modal_notify_msg').html(erroralert + r000 + alertclose).hide().html(erroralert + r000 + alertclose).fadeIn('slow');
				} 
			   
			  
		   }
		 });
	
	return false; // avoid to execute the actual submit of the form.
});



/*------------------------------------------------------
*
* END OF AMBER ADD
*
*-------------------------------------------------------*/

//Function called when user data is updated on page, data is posted to script for processing
$('.update_user_data').click(function(){    
    
	var formid = $(this).attr('formid');
	var dorefresh = $(this).attr('dorefresh');
	
	//alert(formid);

	var updatebutton = this;
	var current_time = new Date().getTime()/1000;
	
    var $valid = $("#"+formid).valid();
        if(!$valid) {
            $validator.focusInvalid();
            return false;
        }  
		
	$("#updatemsgs").html(''); //Empty Msg Container
    $("#updatemsgs").show(); //Initialize msgs container
	
	$(this).html("<img src='img/animated_loading.gif' style='height: 15px; margin-top: -4px;'/> Updating...");
	
	$.ajax({
			type: 'POST',
			url: 'z_scripts/farm_user_agent_update.php?type=submit',
			data: $("#"+formid).serialize() + "&users_id=" + users_id,
			success: function(data, textStatus, jqXHR){
			    $(updatebutton).html("<i class='icon-ok-circle' style='color: green;'></i> Completed");
				
				setTimeout(function(){
				  $(updatebutton).html("<i class='icon-share'></i> Update?");
				  $("#updatemsgs").fadeOut();
				}, 2000);

				
				$('#updatemsgs').html(data);
				if(dorefresh == "1"){location.reload();} //Reload page after completion on flag set
			}
		});
		
		return false; // required to block normal submit since you used ajax	

});

/*$('.update_user_pic').click(function(){    
    
	var formid = $(this).attr('formid');
	var dorefresh = $(this).attr('dorefresh');

	var updatebutton = this;
		
	$("#updatemsgs").html(''); //Empty Msg Container
    $("#updatemsgs").show(); //Initialize msgs container
	
	$(this).html("<img src='img/animated_loading.gif' style='height: 15px; margin-top: -4px;'/> Updating...");
	
	
	
	$.ajax({
			type: 'POST',
			url: 'z_scripts/farm_profile_pic_download.php?redo=yes',
			data: $("#"+formid).serialize(),
			success: function(data, textStatus, jqXHR){
			    $(updatebutton).html("<i class='icon-ok-circle' style='color: green;'></i> Completed");
				
				setTimeout(function(){
				  $(updatebutton).html("<i class='icon-share'></i> Update?");
				  $("#updatemsgs").fadeOut();
				}, 2000);

				
				$('#updatemsgs').html(data);
				if(dorefresh == "1"){window.location = window.location.href.split("&")[0];} //Reload page after completion on flag set
			}
		});
		
		return false; // required to block normal submit since you used ajax	

});*/

$('.update_user_loading').click(function(){ 
	$(this).html("<img src='img/animated_loading.gif' style='height: 15px; margin-top: -4px;'/> Updating...");
});

$('.picrefresh').click(function(){    
window.location = window.location.href.split("&")[0];
});

$('#showmorestaff').click(function(){
	$('.staffobject').toggle();
});

//Resize Custom style select box options on window resize
$( window ).resize(function() {
   var selectboxwidth = $('.styled-select-other').width();
   $("select[id^='routeback_phones']").css("width", selectboxwidth);
});

$('.inner_sidebar').easytabs({
                animationSpeed: 300,
                collapsible: false,
                updateHash: false
});

$('.timepicker3').timepicker({
    minuteStep: 1,
    showSeconds: false,
    showMeridian: true,
    defaultTime: false
  });
  
$('#usernamemirror').on('keyup change', function(){

	var getun = $(this).val();

	$('.usernamemirror').val(getun);
	$('.usernamemirror_text').text(getun);


});

});