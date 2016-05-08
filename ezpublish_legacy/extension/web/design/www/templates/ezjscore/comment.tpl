<div class="comment hidden" id="comment-{$comment.node_id}">
                                                                <p class="author">{$comment.data_map.author.content|wash}</p>
                                                                <p class="message">{$comment.data_map.message.content|wash(xhtml)}</p>

<p class="fecha">{$comment.object.published|datetime( 'custom', '%d de %F de %Y a las %H:%i horas')}.</p>
                                                            </div>
