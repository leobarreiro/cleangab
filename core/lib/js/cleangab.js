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

	this.urlbase = "";
	
	this.userFirstPage = "";
	
	this.loadPermissionToSelectFirstPage = function(obj) {
		var sel = document.getElementById("first_page");
		var def = document.getElementById("first_page").value;
		var exists = false;
		if (obj.checked) {
			for (var i=0; i<sel.options.length; i++) {
				if (sel.options[i].value == obj.value) {
					exists = true;
				}
			}
			if (!exists) {
				sel.options[sel.options.length] = new Option(jQuery(obj).attr('rel'), obj.value);
			}
		} 
		else {
			for (var i=0; i<sel.options.length; i++) {
				if (sel.options[i].value == obj.value) {
					sel.options[i] = null;
				}
			}
		}
		sel.value = this.userFirstPage;
	}

}

cleangabJs = new CleanGabJs();