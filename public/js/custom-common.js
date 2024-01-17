// autologout.js
$(document).ready(function () {
    const timeout = 1800000;  // 1800000 ms = 30 minutes, 1 min = 60000
    var idleTimer = null;
    $('*').bind('mousemove click mouseup mousedown keydown keypress keyup submit change mouseenter scroll resize dblclick', function () {
        clearTimeout(idleTimer);
        //console.log(idleTimer);
        idleTimer = setTimeout(function () {
            document.getElementById('logout-form').submit();
        }, timeout);
    });
    $("body").trigger("mousemove");
});
// Wait for window load
$(window).on('load', function(){
	// Animate loader off screen
	$(".se-pre-con").fadeOut("slow");;
});