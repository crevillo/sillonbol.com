<div class="wrapper">
{def $nodes=array()}
{ezscript_require( array( 'ezjsc::jquery', 'jquery-migrate-1.1.1.min.js', 'unoslider.js') )}
{ezcss_require( array( 'unoslider.css', 'themes/modern/theme.css') )}
      <ul id="slider" class="unoslider" >
                {foreach $block.valid_nodes as $node}
                {set $nodes= $nodes|append($node.node_id)}
                <li>
		     <div class="unoslider_caption"><a href="{$node.url_alias|ezurl(no)}">{$node.name}</a></div> 
                    <a href="{$node.url_alias|ezurl(no)}"><img src="{$node.data_map.image.content.big.url|ezurl(no)}" width="{$node.data_map.image.content.big.width}" height="{$node.data_map.image.content.big.height}"/></a>
                  
                </li>
                {/foreach}
            </ul>
        {ezpagedata_set( 'nodes', $nodes )}
