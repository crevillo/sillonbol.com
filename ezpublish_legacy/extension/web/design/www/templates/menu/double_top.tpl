
        <ul class="sf-menu">
        {def $root_node = fetch( 'content', 'node', hash( 'node_id', $pagedata.root_node ) )
             $top_menu_class_filter = array( 'subcategoria' )
             $top_menu_items = fetch( 'content', 'list', hash( 'parent_node_id', $root_node.node_id,
                                                               'sort_by', $root_node.sort_array,
                                                               'class_filter_type', 'include',
                                                               'class_filter_array', $top_menu_class_filter,
                                                               'attribute_filter', array( array( 'subcategoria/ocultar', '!=', 1)) ) )
             $top_menu_items_count = $top_menu_items|count()
             $item_class = array()
             $level_2_items = 0
             $current_node_in_path = first_set($pagedata.path_array[1].node_id, 0  )
             $current_node_in_path_2 = first_set($pagedata.path_array[2].node_id, 0  )}

        {if $top_menu_items_count}
           {foreach $top_menu_items as $key => $item}
                {set $item_class = array()}
                
                    {set 
                         $level_2_items = fetch( 'content', 'list', hash( 'parent_node_id', $item.node_id,
                                                                          'sort_by', $item.sort_array,
                                                                          'class_filter_type', 'include',
                                                                          'class_filter_array', $top_menu_class_filter ) )}
                  
              
                {if $item.node_id|eq( $current_node_id )}
                    {set $item_class = $item_class|append("current")}
                {/if}

                {if eq( $item.class_identifier, 'link')}
                    <li id="node_id_{$item.node_id}"{if $item_class} class="bgr {$item_class|implode(" ")}"{/if}><a {if eq( $ui_context, 'browse' )}href={concat("content/browse/", $item.node_id)|ezurl}{else}href={$item.data_map.location.content|ezurl}{if and( is_set( $item.data_map.open_in_new_window ), $item.data_map.open_in_new_window.data_int )} target="_blank"{/if}{/if}{if $pagedata.is_edit} onclick="return false;"{/if} title="{$item.data_map.location.data_text|wash}" class="menu-item-link" rel={$item.url_alias|ezurl}>{if $item.data_map.location.data_text}{$item.data_map.location.data_text|wash()}{else}{$item.name|wash()}{/if}</a></li>
                {else}
                    <li id="node_id_{$item.node_id}"{if $item_class} class="bgr {$item_class|implode(" ")}"{else}class="bgr"{/if}><a href={if eq( $ui_context, 'browse' )}{concat("content/browse/", $item.node_id)|ezurl}{else}{$item.url_alias|ezurl}{/if}{if $pagedata.is_edit} onclick="return false;"{/if}>{$item.name|wash()}</a>
{if $level_2_items|count|gt(0)}
    <ul>
        {foreach $level_2_items as $level_2_item}
            <li><a href={$level_2_item.url_alias|ezurl}>{$level_2_item.name|wash}</a></li>
        {/foreach}
    </ul>
{/if}
</li>
                {/if}
            {/foreach}
        {/if}
        </ul>
