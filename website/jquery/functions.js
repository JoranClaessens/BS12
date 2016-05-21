// JavaScript Document
$(function() {
	$("nav li:has(ul)").doubleTapToGo();
	
	// Sliding Menu small screen
	$("#menu-wrap").prepend('<div id="menu-trigger">Menu</div>');
	$("#menu-trigger").on("click", function(){
		$("#menu").slideToggle();
	});
	
	// Toggle upload field Registreren
	$("#createkey").on("click", function(){
		$(".toggleView").slideUp();
	});
	$("#uploadkey").on("click", function(){
		$(".toggleView").slideDown();
	});
	
	// JQuery to download a file by a link
	$("#downloadFile").on("click", function () {
		$.fileDownload($(this).prop("href"));
	 	$("#downloadFile").prop("href", "?page=home");
	 	$("#downloadFile").text("Naar Home");
		$("#downloadFile").off();
		return false;
	});

	// JQuery to download New ZIP-file
	$("#clickDownZIP").on("click", function () {
		$.fileDownload($(this).prop("href"));
	 	$("#clickDownZIP").prop("href", "?aktie=wisZIP");
	 	$("#clickDownZIP").text("Wis Bestand");
		$("#clickDownZIP").off();
		return false;
	});

// iPad
	var isiPad = navigator.userAgent.match(/iPad/i) !== null;
	if (isiPad) { $("#menu ul").addClass("no-transition"); }
});