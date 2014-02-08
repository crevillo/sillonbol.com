  <form action={"/content/search"|ezurl} id="form-top">
  <fieldset>
  <div id="searchbox-inner" class="form-top">   
    {if $pagedata.is_edit}
    <span><input id="searchtext" name="SearchText" type="text" value="" size="12" disabled="disabled" /></span>
    <a href="#" onClick="document.getElementById('form-top').submit()">Buscar</a>						
    {else}
    <span><input id="searchtext" name="SearchText" type="text" value="" size="12" /></span>
    <a href="#" onClick="document.getElementById('form-top').submit()">Buscar</a>
        {if eq( $ui_context, 'browse' )}
         <input name="Mode" type="hidden" value="browse" />
        {/if}
    {/if}
  </div>
  </fieldset>
  </form>

