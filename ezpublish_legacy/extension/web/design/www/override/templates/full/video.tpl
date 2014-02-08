{ezpagedata_set( 'site_title', $node.name)}
{if $node.data_map.tags.content.tags|count|gt(0)}
{def $pos = rand( 0, $node.data_map.tags.content.tags|count|sub(1))}
{ezpagedata_set( 'keyword_id', $node.data_map.tags.content.tag_ids[$pos])}
{/if}
{ezscript_require( array( 'ezjsc::jquery', 'ezjsc::jqueryio', 'article/jquery.scrollTo-1.4.2-min.js', 'article/article.js' ) )}

                                               <article class="grid_12">
                                                   <div class="box">
                                                   		<div class="box-padding">
                                                        	<h1 class="f-s color-3 text-shadow">{$node.data_map.nombre.content}</h1>
                                                            
<div class="share-links clearfix">
<div class="rating">
    {attribute_view_gui attribute=$node.data_map.star_rating}
</div>
{if $node.children_count}
<div id="comments-count">
    <a href="#comments" id="comments-link">{$node.children_count} {cond( $node.children_count|eq(1), ' comentario', ' comentarios')}</a>
</div>
{else}
<div id="comments-count">
    <a href="#comments" id="comments-link">Sé el primero en comentar esto...</a>
</div>
{/if}
<div class="twitter"><a href="https://twitter.com/share" class="twitter-share-button" data-via="Sillonbolcom" data-lang="es">Twittear</a>
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
<div class="video">
    {if $node.data_map.youtube.has_content}
        {attribute_view_gui attribute=$node.data_map.youtube}
    {elseif $node.data_map.vimeo.has_content}
        {attribute_view_gui attribute=$node.data_map.vimeo}
    {/if}
</div>

                                                            <div class="wysiwyg">
                                                                {$node.data_map.descripcion.content.output.output_text}
                                                            </div>
                                                            
                                                            {attribute_view_gui attribute=$node.data_map.tags}

<div id="button-row" class="clearfix">
    <div class="button-row-item comment" id="button-comment">Comentar</div>
    <div class="button-row-item rate" id="button-rate">Valorar</div>
    <div class="button-row-item share" id="button-share">Compartir</div>
</div>

                <div id="social-part-rate" class="social-part">
                    <a href="#" id="ezsr_down_{$node.data_map.star_rating.id}_{$node.data_map.star_rating.version}_1"><img src={"1.png"|ezimage} width="32" height="32" alt="muy mala" title="una muy mala" /></a>
                    <a href="#" id="ezsr_down_{$node.data_map.star_rating.id}_{$node.data_map.star_rating.version}_2"><img src={"2.png"|ezimage} width="32" height="32" alt="mala" title="mala" /></a>
                    <a href="#" id="ezsr_down_{$node.data_map.star_rating.id}_{$node.data_map.star_rating.version}_3"><img src={"3.png"|ezimage} width="32" height="32" alt="regular" title="regular" /></a>
                    <a href="#" id="ezsr_down_{$node.data_map.star_rating.id}_{$node.data_map.star_rating.version}_4"><img src={"4.png"|ezimage} width="32" height="32" alt="buena" title="buena" /></a>
                    <a href="#" id="ezsr_down_{$node.data_map.star_rating.id}_{$node.data_map.star_rating.version}_5"><img src={"5.png"|ezimage} width="32" height="32" alt="excelente" title="excelente" /></a>
                </div>

<div id="social-part-share" class="social-part">
    <!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style addthis_32x32_style">
<a class="addthis_button_twitter"></a>
<a class="addthis_button_facebook"></a>
<a class="addthis_button_google"></a>
<a class="addthis_button_meneame"></a>
<a class="addthis_button_email"></a>
<a class="addthis_button_compact"></a>

</div>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4f65012e33b8e76d"></script>

<!-- AddThis Button END -->
</div>

<div id="social-part-comment" class="social-part">
    <div id="loading-container"><div id="loading">un momento...</div></div>
    <form id="comment-form" action="web::comment" method="post">
        <fieldset>
            <ul>
                <li><input type="text" name="name" id="comment-form-name" value="Tu nombre..." class="text" /></li>
                <li><textarea name="message" id="comment-form-message">Pon aquí tus comentarios...</textarea></li>
            </ul>
        </fieldset>
        <input type="hidden" name="node_id" id="field_node_id" value="{$node.node_id}" />
        <input type="submit" value="Enviar" name="send" id="comment-form-send" />
    </form>
</div>
                                                           
{def $comments = fetch( 'content', 'list', hash( 'parent_node_id', $node.node_id,
                                                 'class_filter_type', 'include',
                                                 'class_filter_array', array( 'comment' ),
                                                 'sort_by', array( 'published', true() )
))}


                                                              <div id="comments">
                                                            <h2 id="comments-header">{if $comments|count|eq(0)}Sé el primero en comentar esto{else}{$comments|count} comentario{if $comments|count|ne(1)}s{/if}{/if}</h2>
                                                            {foreach $comments as $comment}
                                                            <div class="comment" id="comment-{$comment.node_id}">
                                                                <p class="author">{$comment.data_map.author.content|wash}</p>
                                                                <p class="message">{$comment.data_map.message.content|wash(xhtml)}</p>

<p class="fecha">{$comment.object.published|datetime( 'custom', '%d de %F de %Y a las %H:%i horas')}.</p>
                                                            </div>
                                                            {/foreach}
</div>
                                                            


                                                          <div id="goTop"><a href="#" id="toplink">volver al contenido</a></div> 
                                                        </div>

                                                        
                                                   </div>
                                                  
                                               </article>
                                              
                                           
