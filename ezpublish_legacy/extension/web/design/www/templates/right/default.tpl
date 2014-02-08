<article class="grid_6 omega">
{cache-block}
                                                    {include uri="design:blocks/latest_articles.tpl}
{/cache-block}
{cache-block expiry=3600}
                                                    {include uri="design:blocks/sillonbolazo.tpl"}
{/cache-block}

{cache-block expiry=86400 ignore-content-expiry}
                                                    {include uri="design:blocks/tagcloud.tpl"}
{/cache-block}

<!--
{ezpagedata().persistent_variable|attribute(show)}
-->

                                                    
                                                    
                                                    
                                               </article>
