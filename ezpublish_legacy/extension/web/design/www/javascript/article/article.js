(function( $ )
{
    $(document).ready( function()
    {
        $(".button-row-item").bind( 'click', function(){
            $('.message').remove();
            var aux = $(this).attr( 'id' ).split( '-' );
            $('.social-part').hide();
            $('#social-part-' + aux[1] ).show();
            
        });

        $("a" ,"#social-part-rate").bind( 'click', function(e){
            e.preventDefault();
            $(this).addClass( 'selected' );
            var args = $(this).attr('id').split('_');           
            jQuery.ez( 'ezstarrating::rate::' + args[2] + '::' + args[3] + '::' + args[4], {}, _vote_callBack );         
        });

        $("#comment-form-name").bind( 'focus', function(e){
            $(this).val( '');
        });

        $("#comment-form-message").bind( 'focus', function(e){
            $(this).val( '');
        });

        $("#comment-form").bind( 'submit', function(e){
            e.preventDefault();
            $("#loading-container").fadeIn( 1200 );
            jQuery.ez( 'web::comment', { 
                'name': $("#comment-form-name").val(),
                'message' : $("#comment-form-message").val(),
                'node_id' : $("#field_node_id").val()
            }, _comment_callBack );         
        });

        $("#toplink").bind( 'click', function(e){
            e.preventDefault();
            //$(document.body).animate({scrollTop: $('#content').offset().top}, 1000);
            $.scrollTo( '#top', 500, {axis:'y'} );
        });

        $("#comments-link").bind( 'click', function(e){
           e.preventDefault();
           //$(document.body).animate({scrollTop: $('#button-row').offset().top}, 1000);
           $.scrollTo( '#button-row', 500, {axis:'y'} );
        });                
    });

    function _vote_callBack( data )
    {
        if ( data && data.content !== '' )
        {
            if ( data.content.rated )
            {
                $('.message').remove();
                $("#social-part-rate").append( '<div class="message">Gracias por tu voto</div>' );
            }
            else if ( data.content.already_rated )
            {
                $('.message').remove();
                $("#social-part-rate").append( '<div class="message error">Ya habías votado aquí. Sólo puedes votar una vez</div>' );
                $('#ezsr_has_rated_' + data.content.id).removeClass('hide');
            }
            //else alert('Invalid input variables, could not rate!');
        }
        else
        {
            // This shouldn't happen as we have already checked access in the template..
            // Unless this is inside a aggressive cache-block of course.
            alert( data.content.error_text );
        }
    }

    function _comment_callBack( data )
    {
        if ( data && data.content !== '' )
        {
            if( $("#comments").length )
            {
                $("#loading-container").fadeOut( 400, function(){
                $("#comments").append( data.content.comment );
                $("#comments-header").html( data.content.comments_header );
                $("#social-part-comment").hide();
                $("#comment-form-name").val( 'Tu nombre...' );
                $("#comment-form-message").val( 'Pon aquí tus comentarios...' );
                
                $.scrollTo( '#comment-' + data.content.node_id, 500, {axis:'y'} );               
                });
            }
        }
        else
        {
            // This shouldn't happen as we have already checked access in the template..
            // Unless this is inside a aggressive cache-block of course.
            alert( data.content.error_text );
        }
    }
})(jQuery);
