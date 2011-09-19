jQuery(document).ready(function() {
	jQuery(".dateformatter").mask("99/99/9999");
	jQuery(".datetimeformatter").mask("99/99/9999 99:99");
	jQuery(".phoneformatter").mask("(99) 9999.9999");
	jQuery(".cpformatter").mask("99999-999");
	jQuery("div#uimessage").fadeOut(4500);
	jQuery('.moeda').priceFormat({ prefix: '', centsSeparator: ',', thousandsSeparator: '.', limit: 8 });
	jQuery('.percent').priceFormat({ prefix: '', centsSeparator: ',', thousandsSeparator: '.', limit: 5 });
	jQuery(".mes").mask("99");
});

function ajaxWait() {
	jQuery("#ajaxloader").css("display", "inline").css("visibility", "visible");
}

function ajaxDone() {
	jQuery("#ajaxloader").fadeOut(1000);
}

function autoCompleteFormatResult(row) {
	return row[0].replace(/(<.+?>)/gi, '');
}

function autoCompleteFormatItem(row) {
	return row[0] + " (<strong>id: " + row[1] + "</strong>)";
}

function CleanGabJs() {
	
	this.auto = function() {
		alert('oi');
	};
	
}

cleangabJs = new CleanGabJs();