<div class="item">
<div class="grid-spacer">
    {if $node.data_map.short_title.has_content}
    <p class="grid-cat"><a href={$node.url_alias|ezurl}>{$node.data_map.short_title.content}</a></p>
    {/if}
    <h2 class="grid-tit"><a href={$node.url_alias|ezurl}>{$node.name}</a></h2>
    <p class="meta"> <i class="fa fa-clock-o"></i> {$node.object.published|datetime( 'custom', '%j %M %Y' )}</p>
    {if $node.data_map.image.has_content}
    
        <a href={$node.url_alias|ezurl}><img src={$node.data_map.image.content.line.url|ezroot} width="{$node.data_map.image.content.width.height}" height="{$node.data_map.image.content.list.height}" /></a>
    
    {/if}
    <div class="grid-text">
        <p>{$node.data_map.body.content.output.output_text|strip_tags|shorten(255)}</p>
    </div>
</div>
</div>