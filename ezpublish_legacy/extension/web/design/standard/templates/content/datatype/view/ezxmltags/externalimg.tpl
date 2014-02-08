<div class="externalimg{if and(is_set($align),$align|ne(''))}
 {if $align|eq('left')} flt{/if}
 {if $align|eq('right')} frt{/if}
{/if}" >
  <img src="{$url}" alt="" {if or(is_set($resize)|not, $resize|eq('true')|not)}width="590"{/if}
	{if and( $align|eq('left'), $resize|eq('true'))}width="200"{/if}{if and( $align|eq('right'), $resize|eq('true'))}width="200"{/if}/>
   {if $pie|ne('')}
	<p class="imgfooter">{$pie}</p>
   {/if}
</div>
