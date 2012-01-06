function dispContent(content) {
	if (content =="teachertime") {
		document.getElementById("teachertime").className="";
		document.getElementById("teacherschedule").className="hide";
		document.getElementById("li_schedule").className="";
		document.getElementById("li_time").className="active";
	}else {
		document.getElementById("teachertime").className="hide";
		document.getElementById("teacherschedule").className="";
		document.getElementById("li_schedule").className="active";
		document.getElementById("li_time").className="";
	}
	
}
function openfun(el){
	var time = $(el).html();
	$(el).unbind('click');
	$(el).html("<img src='loader.gif' style='height:22px;' />");
	$.ajax({
		url: "teachrequest.php",
		type: "POST",
		data: { time: time, action: 1 },
		cache: false,
		success: function(html){
			if (html=="success"){
					$(el).html(time);
					$(el).removeClass("open");
					$(el).addClass("blocked");
					$(el).click( function(){ blockedfun(el); } );
			} else { alert(html); }
		},
  error: function(data, statusCode) {
    alert("ERROR: "+data)
  }
	});
	//alert($(el).html()); 
}
function blockedfun(el){
	var time = $(el).html();
	$(el).unbind('click');
	$(el).html("<img src='loader.gif' style='height:22px;' />");
	$.ajax({
		url: "teachrequest.php",
		type: "POST",
		data: { time: time, action: 3 },
		cache: false,
		success: function(html){
			if (html=="success"){
					$(el).html(time);
					$(el).removeClass("blocked");
					$(el).addClass("open");
					$(el).click( function(){ openfun(el); } );
			} else { alert(html); }
		}
	});
	//alert($(el).html()); 
}
function bookedfun(el){
	var time = $(el).html();
	$(el).html("<img src='loader.gif' style='height:22px;' />");
	$(el).unbind('click');
	$.ajax({
		url: "teachrequest.php",
		type: "POST",
		data: { time: time, action: 2 },
		cache: false,
		success: function(html){
			$(el).html(time);
			$(el).click( function(){ bookedfun(el); } );
		var $dialog = $('<div></div>')
			.html("The person who booked this appointment is: "+html)
			.dialog({
				autoOpen: false,
				title: 'Appointment booked'
			});
            var $block = $('<div style="cursor: pointer;">Cancel</div>');
            $block.click(function() { 
                $block.html("Cancelled");
                $(el).unbind('click');
                $block.unbind('click');
                $block.css('cursor', 'auto');
                $(el).html("<img src='loader.gif' style='height:22px;' />");
                $.ajax({
                    url: "teachrequest.php",
                    type: "POST",
                    data: { time: time, action: 4 },
                    cache: false,
                    success: function(html){
                        if (html=="success"){
                            $(el).html(time);
                            $(el).removeClass("booked");
                            $(el).addClass("blocked");
                            $(el).click( function(){ openfun(el); } );
                        } else { alert(html); }
                    }
                });
            });
            $dialog.append($block);
			$dialog.dialog('open');
		}

	});
}
$(document).ready(function(){
	$(".open").each(function (ind, el) {
		$(el).click( function(){ openfun(el) } );
	});
	$(".blocked").each(function (ind, el) {
		$(el).click( function(){ blockedfun(el) } );
	});
	$(".booked").each(function (ind, el) { 
		$(el).click( function(){ bookedfun(el) } );
	});

});

