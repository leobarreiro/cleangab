jQuery(document).ready(function() {
	   $(".dateformatter").mask("99/99/9999");
	   $(".datetimeformatter").mask("99/99/9999 99:99");
	   $(".phoneformatter").mask("(99) 9999.9999");
	   $(".cpformatter").mask("99999-999");
	   $("div#uimessage").fadeOut(4500);
	   $('.moeda').priceFormat({ prefix: '', centsSeparator: ',', thousandsSeparator: '.', limit: 8 });
	   $('.percent').priceFormat({ prefix: '', centsSeparator: ',', thousandsSeparator: '.', limit: 5 });
	   $(".mes").mask("99");
});

function ajaxWait() {
	jQuery("#ajaxloader").css("display", "inline").css("visibility", "visible");
	//jQuery("#ajaxloader").fadeIn(1000);
}

function ajaxDone() {
	jQuery("#ajaxloader").fadeOut(1000);
}