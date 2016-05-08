$(document).ready(function() {
	
	//When page loads...
	$(".tab_content").hide(); //Hide all content
	$("ul.tabs li:first").addClass("active").show(); //Activate first tab
	$(".tab_content:first").show(); //Show first tab content
	
	//On Click Event
	$("ul.tabs li").click(function() {
	
		$("ul.tabs li").removeClass("active"); //Remove any "active" class
		$(this).addClass("active"); //Add "active" class to selected tab
		$(".tab_content").hide(); //Hide all tab content
	
		var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
		$(activeTab).fadeIn(); //Fade in the active ID content
		Cufon.refresh();
		return false;
	});
	
	})

;
$(document).ready(function() {
	
	//When page loads...
	$(".tab_content_1").hide(); //Hide all content
	$("ul.tabs-1 li:first").addClass("active").show(); //Activate first tab
	$(".tab_content_1:first").show(); //Show first tab content
	
	//On Click Event
	$("ul.tabs-1 li").click(function() {
	
		$("ul.tabs-1 li").removeClass("active"); //Remove any "active" class
		$(this).addClass("active"); //Add "active" class to selected tab
		$(".tab_content_1").hide(); //Hide all tab content
	
		var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
		$(activeTab).fadeIn(); //Fade in the active ID content
		Cufon.refresh();
		return false;
	});
	
	});

$(document).ready(function() {
	
	//When page loads...
	$(".tab_content_2").hide(); //Hide all content
	$("ul.tabs-2 li:first").addClass("active").show(); //Activate first tab
	$(".tab_content_2:first").show(); //Show first tab content
	
	//On Click Event
	$("ul.tabs-2 li").click(function() {
	
		$("ul.tabs-2 li").removeClass("active"); //Remove any "active" class
		$(this).addClass("active"); //Add "active" class to selected tab
		$(".tab_content_2").hide(); //Hide all tab content
	
		var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
		$(activeTab).fadeIn(); //Fade in the active ID content
		Cufon.refresh();
		return false;
	});
	
	});