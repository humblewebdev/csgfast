jQuery.noConflict();

jQuery(function($) {

    $(".form_datetime2").datetimepicker({
        format: "dd MM yyyy - hh:ii",
        autoclose: true,
        todayBtn: true,
        startDate: "2013-02-14 10:00",
        minuteStep: 10,
        pickerPosition: "bottom-left"
    });

    $('.chosen').chosen();
    

    $("#mask_phone").inputmask("mask", {"mask": "(999) 999-9999"}); 
	//$(".mask-digits").inputmask("[9999999]");
	$(".mask-digits2").inputmask("[999]");
	$(".mask-digits3").inputmask("[999]");
	$(".mask-zip").inputmask("[99999]");
	$(".mask-max").inputmask("[9999]");
	//$(".mask-notes").inputmask("[]");
	//$(".mask-agent-code").inputmask("**-**-**", { "oncomplete": function(){ alert('inputmask complete'); } });
	$(".mask-agent-code").inputmask("**-**-**");

    $('.uniform').uniform();
	
	
	

});