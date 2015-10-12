$(function(){
	var isError = false;
	var defaultSubstitution = function () {

	}
	var postListSubstitution = function () {
		
	}
	var postSubstitution = function () {
		
	}
	var loadFile = function (callback, filename, autoPath = true) {
		if (autoPath)
			filename = "../server/html/parts/" + filename;
		$.get(filename)
		.fail(function () {
			isError = true;
				console.log("Problems loading '" + filename + "'");
			})
		.always(callback);
	}
	var content = "
	[head]
	[header]
	[main]
	[footer]";

	$("body").html(content);
});