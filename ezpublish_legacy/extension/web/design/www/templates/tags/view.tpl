<div class="col-md-11 cont-grid">
    <div class="col-md-11 single-in">
        
    <div class="grid">
        

        {def $children = fetch( 'ezfind', 'search', hash( 'query', '',
                                                          'sort_by', hash( 'published', 'desc' ),
                                                          'subtree_array', array( 2 ),
                                                          'class_id', array( 'article', 'blog_post' ),
                                                          'filter', concat( 'attr_tags_lk:"', $tag.keyword|wash, '"' ),
                                                          'offset', cond( is_set( $view_parameters.offset ),
                                                                                  $view_parameters.offset, 0 )
        ))}
       
        {foreach $children.SearchResult as $index => $child}
             {node_view_gui view='line' content_node=$child}
        {/foreach}
         </div>
    </div>
        <div class="pagination">
        {include name=navigator
                     uri='design:navigator/google.tpl'
                     page_uri=concat( 'tags/view/', $tag.keyword|wash )
                     item_count=$children.SearchCount
                     view_parameters=$view_parameters
                     item_limit=9}
        </div>
   
</div>
