<div class="web-tag-cloud">

{foreach $tag_cloud as $index => $tag}
                
        {def $keyword_count = $search_extras_keywords.facet_fields[0].countList[$facetID]}
        {def $percent = $keyword_count|div( $search_count_keywords )|mul( 200 )|floor|sum( 100 ) }
        {def $tags = fetch( 'tags', 'tags_by_keyword', hash( 'keyword', $tag.tag))}
            <span style="font-size: {$tag['font_size']}%"><a href={concat( 'tags/view/', $tag.tag )|ezurl()}  title="usado {$tags[0].count} veces">{$tags[0].keyword|wash()}</a>, </span> 
        {undef $percent $tags $keyword_count}
 
    {/foreach}...
</div>
