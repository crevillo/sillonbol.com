# jCountdown - jQuery Countdown Plugin


##Version 1.4

1.4 is a major rewrite of the API meaning how you would use it has slightly changed. Please see the changelog for more information.

	date - Default: null
	       (Must be a valid date string or Date object)

	updateTime - Default: 1000
	       (Interval in milliseconds when the Countdown should update the time)

	htmlTemplate - Default: "%d <span class=\"cd-time\">days</span> %h <span class=\"cd-time\">hours</span> %i <span class=\"cd-time\">mins</span> %s <span class=\"cd-time\">sec</span>"
	         Example: "%d <span class=\"cd-time\">days</span>" (DOM String with tokens)
	         Tokens available: %y = years, %m = months, %d = days, %h = hours, %i = minutes, %s = seconds

	minus - Default: false
	        (Boolean. Whether the Countdown should have to go into minus figures, especially when counting down to a date)

	onChange - Default: null
	           (Callback function for when the Countdown time updates)

	onComplete - Default: null
	             (Callback function for when the Countdown time updates)

	onPause - Default: null
	          (Callback function for when the Countdown Plugin is paused )

	onResume - Default: null
	           (Callback function for when the Countdown Plugin is resumed from pause)

	leadingZero - Default: false
	              (Boolean. Whether time values should have a leading zero for values < 10. e.g 09)

	offset - Default: null
	         int or float (Offset in hours, can be used for setting countdown time to match server time)

	servertime - Default: null
			 int or function (servertime in milliseconds, e.g (time() * 1000) in PHP. Or a callback function that sets the time, via an ajax request for example. Used to match countdown across devices where local time can be different.
	
	direction - Default: "down"
	            Countdown Direction, "down" for down to a date, and up for "up" from a date

	hoursOnly - Default: false
	            (Boolean. If set to true, jCountdown ignores days left and add converts to hours and adds this to the hours left)

	yearsAndMonths - Default: false
	            (Boolean. If set to true, jCountdown counts down the years/months as well)
			
			
	## Methods

	changeSettings - Accepts an object map, the same as when first initialising the plugin
	    Example: $("#time").countdown("changeSettings", options);

	getSettings - Returns setting/settings from countdown plugin, as well as the timer
	    Example: var currentSettings = $("#time").countdown("getSettings");
	    Example: var dateSetting = $("#time").countdown("getSettings", "date");

	resume - Resumes the countdown, if previously pauses, otherwise this method does nothing
	    Example: $("#time").countdown("resume");

	pause - Pauses the countdown, simple as
	    Example: $("#time").countdown("pause");

	complete - Triggers the complete event and ends the countdown early. Also removes timer and unbinds any events.
	    Example: $("#time").countdown("complete");

	destroy - Removes timer and unbinds any events, puts the DOM Element back to its original HTML state
	    Example: $("#time").countdown("destroy");


	Settings you can access in onChange event through settings object:

	daysLeft
	hrsLeft
	minsLeft
	secLeft
	timer (id for Interval)
	offset
	updateTime
	hoursOnly (boolean)
	yearsAndMonths (boolean)
	date
	minus (boolean)
	htmlTemplate

## Usage
```javascript

//Simple
$(document.ready(function(){
	$("#time").countdown({
	    "date" : "july 30, 2011"
	});
});

//Advanced
$(document.ready(function(){

    //Count DOWN to a date, set via ajax
	$("#time2").countdown({
		htmlTemplate: "%y <span class='cd-time'>years</span> %m <span class='cd-time'>months</span> %d <span class='cd-time'>days</span> %h <span class='cd-time'>hours</span> %i <span class='cd-time'>mins</span> %s <span class='cd-time'>sec</span>",
		date: "december 19, 2012 16:00:00",
		yearsAndMonths: true,
		servertime: function() { 
		    var time = null; 
		    $.ajax({url: 'get_time.php', 
		        async: false, 
				dataType: 'text', 
		        success: function( data, status, xhr ) {  
					time = data; 
		        }, 
				error: function(xhr, status, err) { 
		            time = new Date(); 
					time = time.getTime();
		    	}
			});
		    return time; 
		},
		hoursOnly: false,
		leadingZero: true
	});
	
});

```

##Version 1.3.x and below

## Options

	date - Default: new Date()
	       (Must be a valid date string or Date object)

	updateTime - Default: 1000
	       (Interval in milliseconds when the Countdown should update the time)

	htmlTemplate - Default: "%{d} <span class=\"cd-time\">days</span> %{h} <span class=\"cd-time\">hours</span> %{m} <span class=\"cd-time\">mins</span> %{s} <span class=\"cd-time\">sec</span>"
	         Example: "%{d} <span class=\"cd-time\">days</span>" (DOM String with tokens)
	         Tokens available: %{d} = days, %{h} = hours, %{m} = minutes, %{s} = seconds

	minus - Default: false
	        (Boolean. Whether the Countdown should have to go into minus figures, especially when counting down to a date)

	onChange - Default: null
	           (Callback function for when the Countdown time updates)

	onComplete - Default: null
	             (Callback function for when the Countdown time updates)

	onPause - Default: null
	          (Callback function for when the Countdown Plugin is paused )

	onResume - Default: null
	           (Callback function for when the Countdown Plugin is resumed from pause)

	leadingZero - Default: false
	              (Boolean. Whether time values should have a leading zero for values < 10. e.g 09)

	offset - Default: null
	         int or float (Offset in hours, can be used for setting countdown time to match server time)
	
	servertime - Default: null
			 int (servertime in milliseconds, e.g (time() * 1000) in PHP. Used to match countdown across devices where local time can be different.
		
	direction - Default: "down"
	            Countdown Direction, "down" for down to a date, and up for "up" from a date

	hoursOnly - Default: false (New in 1.3.1)
	            (Boolean. If set to true, jCountdown ignores days left and add converts to hours and adds this to the hours left)
## Methods

	changeSettings - Accepts an object map, the same as when first initialising the plugin
	    Example: $("#time").countdown("changeSettings", options);

	getSettings - Returns setting/settings from countdown plugin, as well as the timer
	    Example: var currentSettings = $("#time").countdown("getSettings");
	    Example: var dateSetting = $("#time").countdown("getSettings", "date");

	resume - Resumes the countdown, if previously pauses, otherwise this method does nothing
	    Example: $("#time").countdown("resume");

	pause - Pauses the countdown, simple as
	    Example: $("#time").countdown("pause");

	complete - Triggers the complete event and ends the countdown early. Also removes timer and unbinds any events.
	    Example: $("#time").countdown("complete");

	destroy - Removes timer and unbinds any events, puts the DOM Element back to its original HTML state
	    Example: $("#time").countdown("destroy");
	
	
	Settings you can access in onChange event through settings object:

	daysLeft
	hrsLeft
	minsLeft
	secLeft
	timer (id for Interval)
	offset
	updateTime
	hoursOnly (boolean)
	date
	minus (boolean)
	htmlTemplate
	
## Usage
```javascript

//Simple
$(document.ready(function(){
    $("#time").countdown({
        "date" : "july 30, 2011"
    });
});

//Advanced
$(document.ready(function(){
    //Count DOWN to a date
    $("#time").countdown({
        "date" : "july 30, 2011",
        "offset" : 2,
        "leadingZero" : true,
        "onChange" : function(event, settings){
           if( countdownShouldEnd() ) {
               //countdownShouldEnd is not a real function
               //just shows an example of usage
               $(this).countdown("complete");
           }
        },
        "onComplete" : function(event) {
            $(this).html("Completed");
        }
    });

    //Count UP from a date
    $("#time").countdown({
        "date" : "january 1, 2009",
        "offset" : 2,
        "direction" "up",
        "leadingZero" : true,
        "onChange" : function(event, settings){
           if( countdownShouldEnd() ) {
               //countdownShouldEnd is not a real function
               //just shows an example of usage
               $(this).countdown("complete");
           }
        },
        "onComplete" : function(event) {
            $(this).html("Completed");
        }
    });

});
```

## License

This plugin is licensed under the MIT License (LICENSE.txt).

Copyright (c) 2011 [Tom Ellis](http://www.webmuse.co.uk)
