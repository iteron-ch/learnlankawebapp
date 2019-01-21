$(document).ready(function(){
	$('*')
		.each(function(){
			var attr= $(this).attr('id')
			if (typeof attr !== typeof undefined && attr !== false) {
			self['$' + $(this).attr('id')]= $(this)
		}
	});

	$user.click(function(){
		alert("yo baby");
	});
});