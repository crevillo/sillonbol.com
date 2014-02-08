{ezscript_require( array( 'ezjsc::jquery', 'tabs.js') )}
<article class="grid_120 block omega">
                                                    <div class="wrapper">
                                                        <div class="bg-dark">
                                                            <ul class="tabs">
                                                                <li><a href="#tab1">Comentarios</a></li>
                                                                <li><a href="#tab2">Tags</a></li>
                                                            </ul>
                                                        </div>
                                                        <div class="tab_container">
                                                            <div id="tab1" class="tab_content">
                                                                <ul class="list-1">
{def $comments = fetch( 
    'disqus',
    'get_latest_comments',
    hash( 'limit', 8 )
)}

{foreach $comments as $comment}
                                                                   <li class="clearfix">
                                                                       <div class="img-disqus-comment flt">
                                                                           <img src="{$comment.avatar}" />
                                                                       </div>
                                                                       <div class="img-disqus-txt">
<p>{$comment.autor} en <a href="{$comment.article_link}">
{$comment.article_title}</a></p>
<p>{$comment.message|wash|shorten(45)}</p></div>
</li>
{/foreach}
                                                                   
                                                                </ul>
                                                            </div>
                                                            <div id="tab2" class="tab_content">
                                                                {webtagcloud( hash( 'parent_node_id', $block.custom_attributes.subtree_node_id, 'post_sort_by', 'keyword' ))}
                                                            </div>
                                                        </div>
                                                    </div>
                                               </article>
