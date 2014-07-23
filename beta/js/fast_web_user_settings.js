jQuery.noConflict();

jQuery(function($) {

$('.set_color').click(function() {

var tablename = $('.color_pick').attr("tablename");
var users_id = $('.color_pick').attr("users_id");
var color = $(this).attr("color");

var suburl = 'z_scripts/farm_web_user_settings.php?command=setui&users_id='+users_id+'&color='+color+'&tablename='+tablename;
	    $.ajax({
		   type: "POST",
		   url: suburl,
		   success: function(data)
		   {   
		       //alert("Color Changed to " + color);
		   }
		 });

});

});