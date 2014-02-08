
<footer>
    <div class="wrapper">
        {def $avisolegalnode = fetch( 'content', 'node', hash( 'node_id', ezini( 'SBSettings', 'AvisoLegalNode', 'web.ini')))}
        <div class="text-bot">SillonBol 2010 - 2012 <a href="{$avisolegalnode.url_alias|ezurl(no)}">{$avisolegalnode.name}</a></div>
    </div>
</footer>
{ezscript_load( array( 'ezjsc::jquery', ezini( 'JavaScriptSettings', 'JavaScriptList', 'design.ini' ), ezini( 'JavaScriptSettings', 'FrontendJavaScriptList', 'design.ini' ) ) )}

{literal}
<script type='text/javascript'>
  $(document).ready(function(){
    $('#slider').unoslider({
        animation: {
          transition: 'fade',
          speed: 1000,
          delay: 50
        },
        slideshow: {
          'speed': 4,
          'timer': false

        },
        block: {
          'vertical' : 1,
          'horizontal' : 1,
        }
    });
  });
</script>
{/literal}



