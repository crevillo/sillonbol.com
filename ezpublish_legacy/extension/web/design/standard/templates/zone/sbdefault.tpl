{if and( is_set( $zones[0].blocks ), $zones[0].blocks|count() )}
{foreach $zones[0].blocks as $block}
    {include uri='design:parts/zone_block.tpl' zone=$zones[0]}
{/foreach}
{/if}
<div class="top">
        <div class="bottom">
            <div class="left">
            	<div class="right">
                	<div class="bottom-left">
                        <div class="bottom-right">
                            <div class="top-right">
                                <div class="top-left"> 
                                   <div class="container_24">
                                       <div class="padding">
                                           <div class="wrapper title">
                                                {if and( is_set( $zones[1].blocks ), $zones[1].blocks|count() )}
{foreach $zones[1].blocks as $block}
    {include uri='design:parts/zone_block.tpl' zone=$zones[1]}
{/foreach}
{/if}
                                           </div>
                                           <div class="wrapper">
                                                                                                {if and( is_set( $zones[2].blocks ), $zones[2].blocks|count() )}
{foreach $zones[2].blocks as $block}
    {include uri='design:parts/zone_block.tpl' zone=$zones[2]}
{/foreach}
{/if}
                                           </div>
                                       </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
