"use strict";

document.addEventListener("DOMContentLoaded", function () {
	setTimeout(function () {
		Array.prototype.slice
			.call(document.getElementsByClassName("dbe_progress-bar"))
			.forEach(function (instance) {
				instance.classList.add("dbe_progress-bar-filled");
			});
	}, 500);
});
