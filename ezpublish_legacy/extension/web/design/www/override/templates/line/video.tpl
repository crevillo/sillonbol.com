<div class="list-item clearfix">
    <div class="img">
        {if $node.data_map.youtube.has_content}
            <a href={$node.url_alias|ezurl}>{$node.data_map.youtube|webyoutube_preview}</a>
        {elseif $node.data_map.vimeo.has_content}
             <a href={$node.url_alias|ezurl}>{$node.data_map.vimeo|webvimeo_preview}</a>
        {/if}
    </div>
    <div class="item-content">
        <h2 class="f-s color-3 text-shadow"><a href={$node.url_alias|ezurl}>{attribute_view_gui attribute=$node.data_map.nombre}</a></h2>
        <div class="snippet">
            {$node.data_map.descripcion.content.output.output_text|strip_tags|shorten(100)}
        </div>
        <div class="moreinfo">
            {if $node.children_count|gt(0)}
            <div class="comments-count">
                <a href="{$node.url_alias|ezurl(no)}#comments"> {$node.children_count} comentario{if $node.children_count|gt(1)}s{/if}</a>
            </div>
            {/if}
            <div class="tags">
                {attribute_view_gui attribute=$node.data_map.tags}
            </div>
        </div>
    </div>
</div>
