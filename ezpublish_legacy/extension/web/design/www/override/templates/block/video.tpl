<article class="grid_6 alpha">
                                                 
<div class="wrapper indent-bot">
                                                        <div class="bg-dark">
                                                            <div class="padding-1">
                                                                
                                                                    <h2>Un vídeo...</h2>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="wrapper">
                                                        {def $video = $block.valid_nodes.0}
                                                        <figure class="img-indent2 video video-home">
                                                            {if $video.data_map.youtube.has_content}
                                                                <a href={$video.url_alias|ezurl}>{$video.data_map.youtube|webyoutube_preview}</a>
                                                            {elseif $video.data_map.vimeo.has_content}
                                                                 <a href={$video.url_alias|ezurl}>{$video.data_map.vimeo|webvimeo_preview}</a>
                                                            {/if}
                                                            <a class="button-video" title="ver vídeo" href="{$video.url_alias|ezurl(no)}"></a>
                                                        </figure>
                                                        <h3 class="video"><a href="{$video.url_alias|ezurl(no)}">{$video.name}</a></h3>
                                                        <p>{$video.data_map.descripcion.content.output.output_text|strip_tags|shorten(120)}</p>
                                                    </div>
</article>
