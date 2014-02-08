(function( $ )
{
    $(document).ready( function()
    {
        if( $("#event-countdown").length )
        { 
           $("#event-countdown-container").show();
           var date = new Date( $("#event-countdown").html() * 1000 );
            $("#event-countdown").countdown({
                "date" : date,
                "htmlTemplate": "%d <span class=\"cd-time\">día(s)</span> %h <span class=\"cd-time\">hora(s)</span> %i <span class=\"cd-time\">min.</span> %s <span class=\"cd-time\">seg.</span>"

            });
        }
    });
})(jQuery);
