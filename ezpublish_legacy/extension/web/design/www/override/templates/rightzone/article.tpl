<div class="wrapper p1">
                                                       <div class="padding-6">
                                                           <div class="wrapper clearfix related-article{if   $last|eq(1)} last{/if}">
                                                               {if $node.data_map.image.has_content}
                                                               <figure class="img-indent3"><a href={$node.url_alias|ezurl}><img src="{$node.data_map.image.content.related.url|ezroot(no)}" alt="" width="{$node.data_map.image.content.related.width}" height="{$node.data_map.image.content.related.height}"></a></figure>{/if}
                                                               <div class="extra-box{if $node.data_map.image.has_content|not} noimg{/if}">
                                                                    <p class="title"><a href={$node.url_alias|ezurl}>{$node.name|wash}</a></p>
                                                                    <p class="subtitle">{$node.data_map.short_title.content|wash}</p>
                                                               </div>
                                                           </div>
                                                       </div>
                                                   </div>
