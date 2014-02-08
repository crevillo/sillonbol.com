{def $template = 'default'}
{if is_set( $module_result.content_info)}
{switch match=$module_result.content_info.class_identifier}
    {case in=array( 'article', 'blog_post', 'video' )}
        {set $template = 'article'}
    {/case}
{/switch}
{/if}
{include uri=concat('design:right/', $template, '.tpl')}
{if and( $module_result.content_info.main_node_id|eq(202), $module_result.view_parameters.offset|not )}
<a href="http://es.777.com/video-poker" target="_blank"><img src={"777.jpg"|ezimage} alt="Video Poker" /></a>
{else}
<iframe allowtransparency="true" src="http://ads.betfair.com/ad.aspx?bid=2296&pid=74107" width="300" height="250" marginwidth="0" marginheight="0" hspace="0" vspace="0" frameborder="0" scrolling="no"></iframe>
{/if}
 
