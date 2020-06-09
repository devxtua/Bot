"use strict";
function send(type) {
	var form = document.getElementById("form");
	form.action (type == 1) ? "1.php" : "2.php";
	form.submit();
}


