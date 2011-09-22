jQuery(document).ready(function() {
	jQuery(".dateformatter").mask("99/99/9999");
	jQuery(".datetimeformatter").mask("99/99/9999 99:99");
	jQuery(".phoneformatter").mask("(99) 9999.9999");
	jQuery(".cpformatter").mask("99999-999");
	jQuery("div#uimessage").fadeOut(3500);
	jQuery('.moeda').priceFormat({ prefix: '', centsSeparator: ',', thousandsSeparator: '.', limit: 8 });
	jQuery('.percent').priceFormat({ prefix: '', centsSeparator: ',', thousandsSeparator: '.', limit: 5 });
	jQuery(".mes").mask("99");
});

function ajaxWait() {
	jQuery("#ajaxloader").css("display", "inline").css("visibility", "visible");
}

function ajaxDone() {
	jQuery("#ajaxloader").fadeOut(500);
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

jQuery.fn.assembleFirstPageSelect = function() {
	var sel = document.getElementById("firstpage");
	var def = document.getElementById("firstpage").value;
	for (var i=0; (i<sel.length-1); i++) {
		sel.option[i] = null;
	}
	jQuery("input[name='permission\[\]'][rel['firstpage']").each(
		function(i) {
			if (this.checked) {
			    var opt = new Option(this.value, this.value);
			    jQuery(document).addOptionToFirstPageSelect(sel, opt);
			}
		}		
	);
	sel.value = def;
}

jQuery.fn.addOptionToFirstPageSelect = function(selectField, option) {
    var optsLen = selectField.options.length;
    selectField.options[optsLen] = option;
}

cleangabJs = new CleanGabJs();

