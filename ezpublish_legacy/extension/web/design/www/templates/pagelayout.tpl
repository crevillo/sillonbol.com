<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$site.http_equiv.Content-language|wash}" lang="{$site.http_equiv.Content-language|wash}">
<head>
{def $basket_is_empty   = cond( $current_user.is_logged_in, fetch( shop, basket ).is_empty, 1 )
     $user_hash         = concat( $current_user.role_id_list|implode( ',' ), ',', $current_user.limited_assignment_value_list|implode( ',' ) )}

{include uri='design:page_head_displaystyles.tpl'}

{if is_set( $extra_cache_key )|not}
    {def $extra_cache_key = ''}
{/if}

{cache-block keys=array( $module_result.uri, $basket_is_empty, $current_user.contentobject_id, $extra_cache_key )}
{def $pagedata         = ezpagedata()
     $pagestyle        = $pagedata.css_classes
     $locales          = fetch( 'content', 'translation_list' )
     $pagedesign       = $pagedata.template_look
     $current_node_id  = $pagedata.node_id}

{include uri='design:page_head.tpl'}
{include uri='design:page_head_style.tpl'}
{include uri='design:page_head_script.tpl'}

</head>
{* To simplify IE css targeting. IE9 conforms, so threat as rest *}
<!--[if lt IE 7 ]><body class="ie6"><![endif]-->
<!--[if IE 7 ]>   <body class="ie7"><![endif]-->
<!--[if IE 8 ]>   <body class="ie8"><![endif]-->
<!--[if (gt IE 8)|!(IE)]><!--><body id="page1"><!--<![endif]-->
<a href="https://github.com/crevillo/sillonbol.com"><img style="position: absolute; top: 0; left: 0; border: 0;" src="https://s3.amazonaws.com/github/ribbons/forkme_left_red_aa0000.png" alt="Fork me on GitHub"></a>
<!-- Complete page area: START -->

<!-- Change between "sidemenu"/"nosidemenu" and "extrainfo"/"noextrainfo" to switch display of side columns on or off  -->
<div class="bg-top">
<div class="main {$pagestyle}">


  {if and( is_set( $pagedata.persistent_variable.extra_template_list ),
             $pagedata.persistent_variable.extra_template_list|count() )}
    {foreach $pagedata.persistent_variable.extra_template_list as $extra_template}
      {include uri=concat('design:extra/', $extra_template)}
    {/foreach}
  {/if}
<header>
   <div class="row-1">
        <div class="wrapper">
            <ul class="menu-right">
                <li><a href="http://www.twitter.com/Sillonbolcom"><img src={"twitter.png"|ezimage} width="32" height="32" alt="Síguenos en twitter" /></a></li>
                 <li><a href="http://www.facebook.com/sillonbol"><img src={"facebook.png"|ezimage} width="32" height="32" alt="Síguenos en facebook" /></a></li>
                  <li><a href={"rss/feed/noticias"|ezurl}><img src={"rss.png"|ezimage} width="32" height="32" alt="RSS" /></a></li>
                <li class="txt"><a href="{fetch( 'content', 'node', hash( 'node_id', ezini( 'SBSettings', 'QuienesSomosNode', 'web.ini'))).url_alias|ezurl(no)}">¿Qué es sillonbol?</a></li>
                <li class="txt last"><a href="{fetch( 'content', 'node', hash( 'node_id', ezini( 'SBSettings', 'ContactNode', 'web.ini'))).url_alias|ezurl(no)}">Contacto</a></li>
            </ul>
        </div>
    </div>
    <div class="bg-row">
  <!-- Header area: START -->
  {include uri='design:page_header.tpl'}
  <!-- Header area: END -->

  {cache-block keys=array( $module_result.uri, $user_hash, $extra_cache_key )}

  <!-- Top menu area: START -->
  {if $pagedata.top_menu}
    {include uri='design:page_topmenu.tpl'}
  {/if}
   </div>
</header>
  <!-- Top menu area: END -->

  <!-- Path area: START -->
{*

  {if $pagedata.show_path}
    {include uri='design:page_toppath.tpl'}
  {/if}
*}
  <!-- Path area: END -->

  <!-- Toolbar area: START -->
  {if and( $pagedata.website_toolbar, $pagedata.is_edit|not)}
    {include uri='design:page_toolbar.tpl'}
  {/if}
  <!-- Toolbar area: END -->  
  {/cache-block}
{/cache-block}

    <!-- Main area: START -->
<section id="content">
    {if and( is_set( ezpagedata().persistent_variable.rightmenu ), ezpagedata().persistent_variable.rightmenu|not )}
        {include uri='design:page_mainarea.tpl'}
{else}
    <div class="top" id="top">
    <div class="bottom">
        <div class="left">
           	<div class="right">
               	<div class="bottom-left">
                    <div class="bottom-right">
                         <div class="top-right">
                              <div class="top-left"> 
                                  <div class="container_24">
                                       <div class="padding">
                                           <div class="wrapper">   
    {include uri='design:page_mainarea.tpl'}
    {include uri='design:right_part.tpl'}
    
</div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{/if}
</section>
    <!-- Main area: END -->
{def $key = cond( is_set( ezpagedata().persistent_variable.nodes ), ezpagedata().persistent_variable.nodes|count, 1 ) }
{cache-block keys=array( $key )}

    {if is_unset($pagedesign)}
        {def $pagedata   = ezpagedata()
             $pagedesign = $pagedata.template_look}
    {/if}

    <!-- Extra area: START -->
    {if $pagedata.extra_menu}
        {include uri='design:page_extramenu.tpl'}
    {/if}
    <!-- Extra area: END -->

  <!-- Columns area: END -->

  <!-- Footer area: START -->
  {include uri='design:page_footer.tpl'}
  <!-- Footer area: END -->

</div>
</div>
<!-- Complete page area: END -->

<!-- Footer script area: START -->
{include uri='design:page_footer_script.tpl'}
<!-- Footer script area: END -->

{literal}
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-30316932-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

{/literal}

{/cache-block}

{* This comment will be replaced with actual debug report (if debug is on). *}
<!--DEBUG_REPORT-->
</body>
</html>
