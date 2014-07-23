jQuery.noConflict();

jQuery(function($) {

  $('#colorpicker').minicolors({
    textfield: false,
  });
  
  $('#tags').tagsInput();

  $('.autosize').autosize();   
  
  $('#dp').datepicker();

  $(".chosen-select").chosen({ width: '350px' });
  $(".chosen").chosen({ width: '350px', disable_search_threshold: 10 });
  

  $("#textare_char").charCount({
    allowed: 150,    
    warning: 20,
    counterText: 'Characters left: '  
  });
  
  $("#rootwizard_form")

  // Dual Box Select
  var db = jQuery('#dualselect').find('.select_arrows button');  //get arrows of dual select
  var sel1 = jQuery('#dualselect select:first-child');    //get first select element
  var sel2 = jQuery('#dualselect select:last-child');     //get second select element
  
  sel2.empty(); //empty it first from dom.
  
  db.click(function(){
    var t = (jQuery(this).hasClass('ds_prev'))? 0 : 1;  // 0 if arrow prev otherwise arrow next
    if(t) {
      sel1.find('option').each(function(){
        if(jQuery(this).is(':selected')) {
          jQuery(this).attr('selected',false);
          var op = sel2.find('option:first-child');
          sel2.append(jQuery(this));
        }
      }); 
    } else {
      sel2.find('option').each(function(){
        if(jQuery(this).is(':selected')) {
          jQuery(this).attr('selected',false);
          sel1.append(jQuery(this));
        }
      });   
    }
    return false;
  }); 

  $('.uniform').uniform();
$.mask.definitions['h']='[A-Za-z0-9]';
$(".mask-agent-code").mask("**-**-**");
$(".mask-phone").mask("(999) 999-9999"); 
$(".mask-digits-reg").mask("?99999"); 
$(".mask-digits2").mask("?99");

$('.timepicker3').timepicker({
    minuteStep: 1,
    showSeconds: false,
    showMeridian: true,
    defaultTime: false
  });

  
//These functions are used to display back the inputted registration information to the user in the last step
	$('#name').on('keypress change', function() {$('.firstname_verify').html($(this).val());});
	$('#lastname').on('keypress change', function() {$('.lastname_verify').html($(this).val());});
	$('#mainphone').on('keypress change', function() {$('.mainphone_verify').html($(this).val());});
	$('#password').on('keypress change', function() {$('.password_verify').html($(this).val());});
	/*$('#conpassword').on('input focusout', function() {
		
		var pwd = $("#password").val();
		var conpwd = $("#conpassword").val();
		var pwdmatch = "Passwords do not match.";
		
			if(newpwd != conpwd)
			{
			  $('#conpassword').html(pwdmatch);
			  return false;
			}
	}); */
	
	


	$('#agent_code').on('keypress change', function() {$('.agentcode_verify').html($(this).val());});
	$('#email').on('keypress change', function() {$('.email_verify').html($(this).val());});

	//Input Agency Name dynamically based on first and last name user input
	 $('#lastname').on('keypress change', function() {
	 var firstname = $('#name').val();
	 var lastname = $(this).val();
	 $('#agencyname').val(firstname + " " + lastname + " Agency");
	  
	 });
	 
	 //Input Routeback number dynamically based on mainphone user input
	 $('#mainphone').on('keypress change', function() {
	    var rbacknum = $(this).val();
	    $('#rback').val(rbacknum);
	 });
	
    //On website field update run internet search for user data script	
	$('#website').on('focusout', function() {
	    var webaddress = $(this).val();
		if(webaddress != "http://www.farmersagent.com/" && webaddress.length >=  30){
		
		$('#websitenotfound').html('Searching Please Wait... &nbsp;<img style="height: 25px;" src="http://greatamericandisposal.com/wp-content/plugins/wp-homepage-slideshow/images/loading.gif"/>');
		$('#websitefound').html('');
		
		var suburl = 'z_scripts/farmweb_reg_prefill.php?farm_url='+webaddress; // the script where you handle the form input.
	    $.ajax({
		   type: "POST",
		   url: suburl,
		   success: function(data)
		   {   
		       $('#websitenotfound').html('');
			   $('#websitefound').html('');
			   $('.farm_web_pic').html(data);
		   }
		 });
		}
	});
	
	//On agent_code field update run check for duplicate user with same info
	$('#agent_code').on('input focusout', function() {
	    var agent_code = $(this).val();
		$('#dup_agentcode').html('<img style="height: 25px;" src="http://greatamericandisposal.com/wp-content/plugins/wp-homepage-slideshow/images/loading.gif"/>');
		$('#dup_agentcode').load('z_scripts/do_check_dup_user.php?agent_code='+agent_code);
	});
    
	//On email field update run check for duplicate user with same info
	$('#email').on('input focusout', function() {
	    var email = $(this).val();
		$('#dup_email').html('<img style="height: 25px;" src="http://greatamericandisposal.com/wp-content/plugins/wp-homepage-slideshow/images/loading.gif"/>');
		$('#dup_email').load('z_scripts/do_check_dup_user.php?email='+email);
	});
	
	//Functions to remove and add products visually and to hidden fast_products input
	
	$('.addprod').click(function(){
	  var prodid = $(this).attr('prodid');
	  //alert('Just added ' + prodid);
	  $('.'+prodid).removeClass('blue');
	  $('.'+prodid).addClass('green');
	  
	  var currentprods = $('#fast_products').val(); //List of product id's already set for register
	  var thisprodid = prodid.substr(-1); //This product id
	  
	  if(currentprods.indexOf(thisprodid) == -1){ //Check if product isn't already in there
	  
	  $('#fast_products').val(function(i,val) { 
			 return val + (!val ? '' : '#') + prodid.substr(-1);
	   });
	   
	   }
	  
	  newcurrentprods = $('#fast_products').val();
	  
	  var prodarray = newcurrentprods.split('#');
	  $('.added_fields').hide();
	  
	  $('.no_add_fields').show();
	  
	  prodarray.forEach(function(prodid){
	     $('.prod_id_'+prodid).show();
		 if(1){ //change this to a prod id that has no additional fields needed.
			$('.no_add_fields').hide();
		 }
          //alert(prodid);
	  });
	  
	  $('.icon_'+prodid).removeClass('icon-plus');
	  $('.icon_'+prodid).addClass('icon-check');
	});
	
	$('.removeprod').click(function(){
	  var prodid = $(this).attr('prodid');
	  //alert('Just removed ' + prodid);
	  $('.'+prodid).removeClass('green');
	  $('.'+prodid).addClass('blue');
	  
	  var currentprods = $('#fast_products').val(); //List of product id's already set for register
	  var thisprodid = prodid.substr(-1); //This product id
	  
	  if(currentprods.indexOf(thisprodid) != -1){ //Check if product isn't already in there
	  
	  $('#fast_products').val(function(i,val) {
             	  
			 if(val.slice(0,1) == thisprodid && val.length >= 3){
			    return val.replace(thisprodid + "#", "");
				//alert("All Alone");
				
			 } else if(val.slice(0,1) == thisprodid){
			    return val.replace(thisprodid, "");
				//alert("All Alone");
				
			 }else { return val.replace('#' + thisprodid, ""); }
	   });
	   
	   }
	  
	  newcurrentprods = $('#fast_products').val();
	  
	  var prodarray = newcurrentprods.split('#');
	  $('.added_fields').hide();
	  
	  $('.no_add_fields').show();
	  
	  prodarray.forEach(function(prodid){
	     $('.prod_id_'+prodid).show();
		 if(prodid != "1"){
			$('.no_add_fields').hide();
		 }
          //alert(prodid);
	  });

	  
	  $('.icon_'+prodid).removeClass('icon-check');
	  $('.icon_'+prodid).addClass('icon-plus');
	});
	
	
	$('.addprod').click(function(){
	  var prodname = $(this).attr('prodname');
	  //alert('Just added ' + prodid);
	  
	  var currentprods = $('#products_verify').text(); //Functions to bind product names
	  
	  //alert('Just added ' + prodname);
	  if(currentprods.indexOf(prodname) == -1){ //Check if product isn't already in there
	  
	  $('#products_verify').text(function(i,text) { 
			 return text + (!text ? '' : ', ') + prodname;
	   });
	   
	   }
	  

	});
	
	$('.removeprod').click(function(){
	  var prodname = $(this).attr('prodname');
	  //alert('Just added ' + prodid);
	  
	  var currentprods = $('#products_verify').text(); //List of product id's already set for register
	  
	  //alert('Just removed ' + prodname);
	  if(currentprods.indexOf(prodname) != -1){ //Check if product isn't already in there
	  
	  $('#products_verify').text(function(i,text) {  //function to unbind product names
	  
	    var plength = -(prodname.length);
	    var endtext = text.slice(plength);
		
		//alert(endtext);
		
	    if(endtext == prodname && (currentprods.indexOf(",") > 0)){
		  return text.replace(", "+prodname, ""); 
		} else
        if(currentprods.indexOf(",") > 0){     	  
		 return text.replace(prodname + ", ", ""); 
		} else { return text.replace(prodname, ""); }

	   });
	   
	   }
	  

	});
	
	//Functions to dynamically show and unshow staff boxes for additional staff
	
	$('.addstaff').click(function(){
	  var nextstaff = $(this).attr('nextstaffclass');
	  $("."+nextstaff).fadeIn();
	});
	
	$('.removestaff').click(function(){
	  var thisstaff = $(this).attr('thisstaffclass');
	  $("."+thisstaff).fadeOut();
	});
	
	$('.showstaff').click(function(){
	   var trigger = $(this).val();
	   if(trigger == "Yes"){ $(".staff1").fadeIn(); }
	   if(trigger == "No"){ $(".viewstaff").fadeOut(); }
	});
	
	var staff1;
	var staff2;
	var staff3;
	var staff4;
	var staff5;
	var num;
	
	
	
	//Function to trigger wizard with options
	
	$('#rootwizard2').bootstrapWizard({
        'nextSelector': '.next-button',
        'previousSelector': '.previous-button',
        onTabClick: function (tab, navigation, index) {
            return false;
        },
        onNext: function(tab, navigation, index) {
            var $valid = $("#rootwizard_form").valid();
            if(!$valid) {
                $validator.focusInvalid();
                return false;
            }

            var total = navigation.find('li').length;
            var current = index + 1;

            if (current == 1) {
                $('#rootwizard2').find('.previous-button').hide();
				
            } else {
                $('#rootwizard2').find('.previous-button').show();
            }

            if (current >= total) {
                $('#rootwizard2').find('.next-button').hide();
                $('#rootwizard2').find('.s-button').show();
            } else {
                $('#rootwizard2').find('.next-button').show();
                $('#rootwizard2').find('.s-button').hide();
            }
        },
        onPrevious: function (tab, navigation, index) {
            var total = navigation.find('li').length;
            var current = index + 1;

            if (current == 1) {
                $('#rootwizard2').find('.previous-button').hide();
            } else {
                $('#rootwizard2').find('.previous-button').show();
            }

            if (current >= total) {
                $('#rootwizard2').find('.next-button').hide();
                $('#rootwizard2').find('.s-button').show();
            } else {
                $('#rootwizard2').find('.next-button').show();
                $('#rootwizard2').find('.s-button').hide();
            }
        },
        onTabShow: function(tab, navigation, index) {
            console.log('onTabShow');
            var $total = navigation.find('li').length;
            var $current = index+1;
            var $percent = ($current/$total) * 100;
            $('#rootwizard2').find('.bar').animate({
                'width': $percent+'%'
            });
        }
    });

    $('#rootwizard2 .s-button').click(function() {
        var $valid = $("#rootwizard_form").valid();
        if(!$valid) {
            $validator.focusInvalid();
            return false;
        }
    });

    $('#rootwizard2').find('.previous-button').hide();
    $('#rootwizard2').find('.s-button').hide();
	$('#rootwizard2').find('.next-button-root').hide();
	
	
	/*var finalprods = $('#fast_products').val();
	if(finalprods){
		$('#done_select').find('.next-button').show();
	}*/
	
});