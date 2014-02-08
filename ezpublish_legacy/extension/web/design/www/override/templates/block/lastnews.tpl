{def $exclude = ezpagedata().persistent_variable.nodes}
<article class="grid_1200 block">
                                                   <div class="bg-white margin-bot0">
                                                   		<div class="padding-1">
                                                            <div class="wrapper">
                                                                <h2 class="fleft color-3 text-shadow">Además...</h2>
                                                                <a class="link-3" href={"noticias"|ezurl}>Ver todos</a>
                                                            </div>
                                                        </div>
                                                   </div>
{def $filter = array()}
{foreach $exclude as $exclude_node_id}
{set $filter = $filter|append( concat( '-meta_main_node_id_si:', $exclude_node_id ))}
{/foreach}

{def $elements = fetch( 'ezfind', 'search', hash( 'query', '',
                                                  'class_id', array( 'article', 'blog_post' ),
                                                  'sort_by', hash( 'published', 'desc' ),
                                                  'limit',6,
                                                  'filter', $filter
 ))}

{foreach $elements.SearchResult as $index => $latest_new}
                                            {if eq( $index|mod(2), 0 )}
                                                   <div class="latest-new-container clearfix">
                                            {/if}
                                                   <div class="box">
                                                   		<div class="box-padding2">
                                                                  
                                                                <div class="wrapper{if $latest_new.data_map.image.has_content|not} noimg{/if}">
                                                                {if $latest_new.data_map.image.has_content}
                                                                <figure class="img-indent"><a href="{$latest_new.url_alias|ezurl(no)}"><img src="{$latest_new.data_map.image.content.latest_new.url|ezroot(no)}" width="{$latest_new.data_map.image.content.latest_new.width}" height="{$latest_new.data_map.image.content.latest_new.height}" alt="" /></a></figure>
                                                                {/if}
                                                                <div class="extra-box">
                                                                    <h2 class="color-3 text-shadow p1">{$latest_new.name}</h2>
                                                                    <p class="p4">
                                                                     	{$latest_new.data_map.short_title.content}
                                                                    </p>
                                                                    <a class="button-2" href="{$latest_new.url_alias|ezurl(no)}"><span class="marker-1">Leer más</span></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                   </div>
                                                  {if eq( $index|mod(2), 1 )}
                                                    </div>
                                                  {/if}
{/foreach}
                                                   
                                               </article>
