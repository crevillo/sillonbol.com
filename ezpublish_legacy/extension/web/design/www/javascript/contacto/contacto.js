(function( $ )
{
    $(document).ready( function()
    {
        $("input.text", "#contact-form").bind( 'focus', function(e){
            $(this).val( '');
        });

         $("textarea", "#contact-form").bind( 'focus', function(e){
            $(this).val( '');
        });
    });
})(jQuery);
