jQuery.noConflict();

jQuery(function($) {

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

 $('.textarea').wysihtml5();

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

$(".admin_update_product").click(function(){
	var formid = $(this).attr('formid');
	var prodid = $(this).attr('prodid');
	var updatebutton = this;
	var $valid = $("#"+formid).valid();
	if(!$valid) {
		$validator.focusInvalid();
		return false;
	}  
	
	$.ajax({
		type: 'POST',
		url: 'z_scripts/admin_product_update.php',
		data: $("#"+formid).serialize() + "&prodid="+prodid,
		success: function(data, textStatus, jqXHR){
			$(updatebutton).html("<i class='icon-ok-circle' style='color: green;'></i> Completed");
			alert(data);
			location.reload();
		}
	});
	return false; // required to block normal submit since you used ajax	
});

$(".update_user_data").click(function(){    
   
	var formid = $(this).attr('formid');
	var users_id = $(this).attr('users_id');
	var dorefresh = $(this).attr('dorefresh');

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
				  //$("#updatemsgs").fadeOut();
				}, 2000);
				//alert(data);
				
				//$('#updatemsgs').html(data);
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