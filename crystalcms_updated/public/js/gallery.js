$(document).ready(function () {
	$(".galleryimagea").on("click", function(e) {
		window.open($(this)[0].href, "", "menubar=no, toolbar=no");
		e.preventDefault();
	})
});