$(document).ready(function(){
	$("li").click(function(){
		$("li.active").removeClass("active");
		$(".menuchoice").addClass("hide");
		$(".menuchoice").removeClass("menuchoice");
	});
});

$(document).ready(function(){
	$("li#li_settings").click(function(){
		$("div#teacher_settings").removeClass('hide');
		$("div#teacher_settings").addClass('menuchoice');
		$("li#li_settings").addClass("active");
	});
});

$(document).ready(function(){
	$("li#li_time").click(function(){
		$("div#teachertime").removeClass('hide');
		$("div#teachertime").addClass('menuchoice');
		$("li#li_time").addClass("active");
	});
});

$(document).ready(function(){
	$("li#li_book").click(function(){
		sendRequest('book');
		$("li#li_book").addClass("active");
	});
});

$(document).ready(function(){
	$("li#li_schedule").click(function(){
		sendRequest('schedule');
		$("li#li_schedule").addClass("active");
	});
});

$(document).ready(function(){
	$("li#li_addstudent").click(function(){
		$("li#li_addstudent").addClass("active");
	});
});

$(document).ready(function(){
	$("li#setting_parent").click(function(){
		$("li#setting_parent").addClass("active");
	});
});
