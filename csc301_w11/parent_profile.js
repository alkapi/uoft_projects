$(document).ready(function(){
	$("ul#menu li[class!=bye]").each(function(index){
        $(this).click(function(){
		    $("li.active").removeClass("active");
            $(this).addClass("active");
            var content = $(this).html();
            $.ajax({
                url: "profile_content.php",
                type: "POST",
                data: {content: content},
                cache: false,
                success: function(html){ $("#tablike").html(html); },
                error: function(data, statusCode){
                    alert("ERROR: " + data); }
            });
	    });
    });

$("body").delegate("#courses li.notyet", "click", function(){
    $("li.activeCourse").removeClass("activeCourse");
    $(this).addClass("activeCourse");
    var courseCode = $(this).html();
    $.ajax({
        url: "booking.php",
        type: "POST",
        data: {courseCode: courseCode},
        cache: false,
        success: function(html){$("div#booktable").html(html);},
        error: function(data, statusCode){ alert("ERROR: " + data);}
    });
});

$("body").delegate("#courses li.already", "click", function(){
    $("li.activeCourse").removeClass("activeCourse");
    var courseCode = $(this).html();
    var message = "<h2>You have already booked an interview.</br>To cancel, please click View Schedule.</h2>";
    $("div#booktable").html(message);
});

$("body").delegate("#studentlist li", "click", function(){
    $("li.activeStu").removeClass("activeStu");
    $(this).addClass("activeStu");
    var stuNum = $(this).attr("name");
    $.ajax({
        url: "courses.php",
        type: "POST",
        data: {stuNum :stuNum},
        cache: false,
        success: function(html){ 
            $("div#courses").html(html);
            $("div#booktable").html("<h2>Please select a course</h2>");
        },
        error: function(data, statusCode){ alert("ERROR: " + data);}
    });
});

$("body").delegate("#schedulelist li", "click", function(){
    $("li.activeStu").removeClass("activeStu");
    $(this).addClass("activeStu");
    var stuNum = $(this).attr("name");
    $.ajax({
        url: "schedule.php",
        type: "POST",
        data: {stuNum :stuNum},
        cache: false,
        success: function(html){ 
            $("div#scheduletable").html(html);
        },
        error: function(data, statusCode){ alert("ERROR: " + data);}
    });
});


$("body").delegate(".open", "click", function(){
	var time = $(this).html();
    var courseCode = $("li.activeCourse").html();
    var message = "You are booking an interview for " + courseCode + " at " + time;
	$("#test").html(message);
	$(this).addClass("trig");
	$("td[rel]").overlay({
		top: 225,
		mask: {
			color: '#ebebeb',
			loadSpeed: 200,
			opacity: 0.7
		},
		closeOnClick: false
	});
});

$("body").delegate("#confirm", "click", function(){
	book();
});

$("body").delegate("#cancel", "click", function(){
    $(".trig").removeClass("trig");
});
$("body").delegate("td.parentCancel", "click", function(){
    var time = $(this).attr("name");
	$.ajax({
		url: "cancelrequest.php",
		type: "POST",
		data: { time: time},
		cache: false,
		success: function(html){
            var message = "You have just canceled an interview";
            alert(message);
            $("div#scheduletable").html("<h2>Please select a student<ash2>")
        },
  		error: function(data, statusCode) {
	    	alert("ERROR: "+data)
	  	}
	});

});

function book(){
	var time = $(".trig").html();
	var courseCode = $("li.activeCourse").html();
    var stuNum = $("li.activeStu").attr("name");
	$.ajax({
		url: "bookrequest.php",
		type: "POST",
		data: { time: time, stuNum: stuNum, courseCode: courseCode},
		cache: false,
		success: function(html){
            var message = "<h2>You have just booked an interview.</br>Please select another course to continue.</h2>";
			$(".close").click();
            $("div#booktable").html(message);
            $("li.activeCourse").removeClass("notyet").addClass("already");
        },
  		error: function(data, statusCode) {
	    	alert("ERROR: "+data)
	  	}
	});
}

});

