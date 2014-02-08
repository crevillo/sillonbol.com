
<div class="grid_12">
<div class="box">
{ezscript_require( array( 'ezjsc::jquery', 'ezjsc::jqueryio', 'article/jquery.scrollTo-1.4.2-min.js', 'article/jquery.jcountdown1.4.min.js', 'article/jquery.timeago.js', 'article/jquery.timeago.es.js', 'article/article.js' ) )}
{cache-block expiry=86400}
<div class="box-padding">
<div class="content-view-tagcloud">
{def $search_keywords=fetch( ezfind , search,
      hash( query , '',
        'facet', array( 
            hash('field', 'attr_tags_lk', 'sort', 'count', 'limit', 250 )),
        'class_id', array('article','blog_post'),
        'subtree_array', array(2)
        ))}
    
{def $search_extras_keywords=$search_keywords['SearchExtras']}
{def $search_count_keywords=$search_keywords['SearchCount']}
 
<div id="tag-cloud" class="colonne_block">
   <h1>Los tags que más hemos usado:</h1>
    
    <div class="tagclouds {$current_css}">
    {foreach $search_extras_keywords.facet_fields[0].nameList as $facetID => $name}
                
        {def $keyword_count = $search_extras_keywords.facet_fields[0].countList[$facetID]}
        {def $percent = $keyword_count|div( $search_count_keywords )|mul( 200 )|floor|sum( 100 ) }
        {def $tags = fetch( 'tags', 'tags_by_keyword', hash( 'keyword', $facetID))}
            <span style="font-size: {$percent}%"><a href={concat( 'tags/view/', $facetID )|ezurl()}  title="usado {$keyword_count} veces">{$tags[0].keyword|wash()}</a>, </span> 
        {undef $percent $tags $keyword_count}
 
    {/foreach}...
 
    </div>
</div>
{undef $search_extras_keywords $search_keywords $search_count_keywords}

</div>
</div>

</div></div>
{/cache-block}

