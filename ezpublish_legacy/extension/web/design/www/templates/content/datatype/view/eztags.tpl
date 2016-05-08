<div class="tags">
{if $attribute.content.tag_ids|count}
    <p>{'Tags'|i18n( 'extension/eztags/datatypes' )}:
    {foreach $attribute.content.tags as $index => $tag}
        <a href={concat( '/tags/view/', $tag.url )|ezurl}>{$tag.keyword|wash}</a>{if lt($index, $attribute.content.tag_ids|count|sub(1))}, {/if} 
    {/foreach}</p>
{/if}
</div>
