<div class="grid_12">
    <div class="box">
        <div class="box-padding">
{def $rss_export = fetch( 'rss', 'export_by_node', hash( 'node_id', $node.node_id ) )}
{if $rss_export}
        <div class="attribute-rss-icon">
            <a href="{concat( '/rss/feed/', $rss_export.access_url )|ezurl( 'no' )}" title="{$rss_export.title|wash()}"><img src="{'rss-icon.gif'|ezimage( 'no' )}" alt="{$rss_export.title|wash()}" /></a>
        </div>
{/if}
        <div class="category-header">
            <h1 class="f-s color-3 text-shadow">{$node.name}</h1>
            <a href="{concat('feeds/', $node.url_alias|explode('/')|implode('-') )|ezurl(no)}"><img src="{"rss-icon.png"|ezimage(no)}" alt="Suscríbite al RSS" title="RSS FEED" /></a>
        </div>

        {def $children = fetch( 'ezfind', 'search', hash( 'query', '',
                                                          'sort_by', hash( 'published', 'desc' ),
                                                          'subtree_array', array( $node.node_id ),
                                                          'class_id', array( 'article', 'blog_post' ),
                                                          'offset', cond( is_set( $view_parameters.offset ),
                                                                                  $view_parameters.offset, 0 )
        ))}
        <div class="navigator-up">
        {include name=navigator
                     uri='design:navigator/google.tpl'
                     page_uri=$node.url_alias
                     item_count=$children.SearchCount
                     view_parameters=$view_parameters
                     item_limit=10}
        </div>
        {foreach $children.SearchResult as $index => $child}
             {node_view_gui view='line' content_node=$child}
        {/foreach}
        <div class="navigator-down">
        {include name=navigator
                     uri='design:navigator/google.tpl'
                     page_uri=$node.url_alias
                     item_count=$children.SearchCount
                     view_parameters=$view_parameters
                     item_limit=10}
        </div>
        </div>
    </div>
</div>
