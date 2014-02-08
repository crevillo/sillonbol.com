{ezpagedata_set( 'site_title', $node.name)}
{ezpagedata_set( 'description', $node.data_map.short_title.content|wash)}
{ezpagedata_set( 'node_image', $node.data_map.image.content.facebook.url|ezroot(no))}
{ezpagedata_set( 'tags', $node.data_map.image.content.facebook.url|ezroot(no))}
{if $node.data_map.tags.content.tags|count|gt(0)}
{def $pos = rand( 0, $node.data_map.tags.content.tags|count|sub(1))}
{ezpagedata_set( 'keyword_id', $node.data_map.tags.content.tag_ids[$pos])}
{/if}
{ezscript_require( array( 'ezjsc::jquery', 'ezjsc::jqueryio', 'article/jquery.scrollTo-1.4.2-min.js', 'article/article.js' ) )}

                                               <article class="grid_12">
                                                   <div class="box">
                                                   		<div class="box-padding">
                                                        	<h1 class="f-s color-3 text-shadow">{$node.data_map.title.content}</h1>
                                                            <div class="p2">{$node.data_map.short_title.content}</div>
<div class="share-links clearfix">{*
<div class="rating">
    {attribute_view_gui attribute=$node.data_map.star_rating}
</div>*}
{if $node.children_count}
<div id="comments-count">
    <a href="#comments" id="comments-link">{$node.children_count} {cond( $node.children_count|eq(1), ' comentario', ' comentarios')}</a>
</div>
{else}
<div id="comments-count">
    <a href="#comments" id="comments-link">Sé el primero en comentar esto...</a>
</div>
{/if}
<div class="twitter"><a href="https://twitter.com/share" class="twitter-share-button" data-via="sillonbolcom" data-lang="es">Twittear</a>
{literal}
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>{/literal}</div>					
<div class="gplus">
<g:plusone size="small" width="120"></g:plusone>
{literal}
<script type="text/javascript">
  window.___gcfg = {lang: 'es'};

  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>
{/literal}
</div>
<div class="facebook"><div id="fb-root"></div>
{literal}
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/es_ES/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="fb-like" data-send="false" data-layout="button_count" data-width="100" data-show-faces="false"></div>
</div>
{/literal}


</div>
{if $node.data_map.image.has_content}
                                                            <figure class="indent-bot1 article-photo"><img src={$node.data_map.image.content.article.url|ezroot} alt=""></figure>
{/if}


{if $node.data_map.autor.has_content}
                                                            <div class="author">
                                                                <p>Publicado por {$node.data_map.autor.content} el {$node.object.published|datetime( 'custom', '%d de %F de %Y a las %H:%i horas')}.</p>
                                                            </div>
                                                            {/if}
{if $node.object.owner.data_map.twitter.has_content}
    <div class="twitter-follow">
      <a href="https://twitter.com/{$node.object.owner.data_map.twitter.content}" class="twitter-follow-button" data-lang="es" data-show-count="false">Seguir a {$node.object.owner.data_map.twitter.content}</a>
<script>{literal}!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="http://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");{/literal}</script>
             
    </div>
{/if}
                                                            <div class="wysiwyg">
                                                                {$node.data_map.body.content.output.output_text}
                                                            </div>
                                                           
                                                            {attribute_view_gui attribute=$node.data_map.tags}
{attribute_view_gui attribute=$node.data_map.comments}


                
                                                           
{def $comments = fetch( 'content', 'list', hash( 'parent_node_id', $node.node_id,
                                                 'class_filter_type', 'include',
                                                 'class_filter_array', array( 'comment' ),
                                                 'sort_by', array( 'published', true() )
))}

{*
                                                             <div id="comments">
                                                            <h2 id="comments-header">{if $comments|count|eq(0)}Sé el primero en comentar esto{else}{$comments|count} comentario{if $comments|count|ne(1)}s{/if}{/if}</h2>
                                                            {foreach $comments as $comment}
                                                            <div class="comment" id="comment-{$comment.node_id}">
                                                                <p class="author">{$comment.data_map.author.content|wash}</p>
                                                                <p class="message-comment">{$comment.data_map.message.content|wash(xhtml)|nl2br}</p>

<p class="fecha">{$comment.object.published|datetime( 'custom', '%d de %F de %Y a las %H:%i horas')}.</p>
                                                            </div>
                                                            {/foreach}
</div>
                                                            
*}

                                                          <div id="goTop"><a href="#" id="toplink">volver al contenido</a></div> 
                                                        </div>

                                                        
                                                   </div>
                                                  
                                               </article>
                                              
                                           
