<div class="wrapper"> 
{def $tag = fetch( 'tags', 'tag', hash( 'tag_id',  ezpagedata().persistent_variable.keyword_id))}
{def $related = fetch( 'ezfind', 'search', hash( 'query', '',
                                                 'class_id', array( 'article' ),
                                                 'subtree_array', array( $module_result.content_info.parent_node_id ),
                                                 'filter', array( 
concat('-meta_node_id_si:', ezpagedata().node_id ) ),
                                                 'sort_by', hash( 'modified', 'desc' ),
                                                 'limit', 3
) )}
{if $related.SearchCount|gt(0)}
                                                     
                                                        <div class="bg-white">
                                                            <div class="padding-1">
                                                                 <h2 class="color-3 text-shadow">Artículos relacionados</h2>
                                                            </div>
                                                       </div>
                                                       
                                                   </div>
{foreach $related.SearchResult as $index => $item}
            {def $last = cond( eq( $index, $related.SearchResult|count|sub(1) ) , 1, 0 )}
               
                       {node_view_gui view='rightzone' content_node=$item last=$last}
        {undef $last}
{/foreach}
                                                   
{/if}
{undef $tag $related}
