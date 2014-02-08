
{if $pagedesign.data_map.image.content.is_valid|not()}
    <div id="logo"><a href={"/"|ezurl} title="{ezini('SiteSettings','SiteName')|wash}" class="logo">{ezini('SiteSettings','SiteName')|wash}</a></div>
{else}
    <div id="logo"><a href={"/"|ezurl} title="{ezini('SiteSettings','SiteName')|wash}" class="logo"></a></div>
{/if}

