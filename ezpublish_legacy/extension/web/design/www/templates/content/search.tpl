<div class="col-md-11 cont-grid">
    <div class="col-md-11 single-in">
{def $search=false()}
{if $use_template_search}
    {set $page_limit=10}

    {def $activeFacetParameters = array()}
    {if ezhttp_hasvariable( 'activeFacets', 'get' )}
        {set $activeFacetParameters = ezhttp( 'activeFacets', 'get' )}
    {/if}

    {def $dateFilter=0}
    {if ezhttp_hasvariable( 'dateFilter', 'get' )}
        {set $dateFilter = ezhttp( 'dateFilter', 'get' )}
        {switch match=$dateFilter}
            {case match=1}
                {def $dateFilterLabel="Last day"|i18n("design/standard/content/search")}
            {/case}
            {case match=2}
                {def $dateFilterLabel="Last week"|i18n("design/standard/content/search")}
            {/case}
            {case match=3}
                {def $dateFilterLabel="Last month"|i18n("design/standard/content/search")}
            {/case}
            {case match=4}
                {def $dateFilterLabel="Last three months"|i18n("design/standard/content/search")}
            {/case}
            {case match=5}
                {def $dateFilterLabel="Last year"|i18n("design/standard/content/search")}
            {/case}
        {/switch}
    {/if}

    {def $filterParameters = fetch( 'ezfind', 'filterParameters' )
         $defaultSearchFacets = fetch( 'ezfind', 'getDefaultSearchFacets' )}
    {* def $facetParameters=$defaultSearchFacets|array_merge_recursive( $activeFacetParameters ) *}

    {set $search=fetch( ezfind,search,
                        hash( 'query', $search_text,
                              'offset', $view_parameters.offset,
                              'limit', $page_limit,
                              'sort_by', hash( 'score', 'desc' ),
                              'facet', $defaultSearchFacets,
                              'filter', $filterParameters,
                              'publish_date', $dateFilter,
                              'spell_check', array( true() ),
                              'search_result_clustering', hash( 'clustering', false() ) )
                             )}
    {set $search_result=$search['SearchResult']}
    {set $search_count=$search['SearchCount']}
    {def $search_extras=$search['SearchExtras']}
    {set $stop_word_array=$search['StopWordArray']}
    {set $search_data=$search}
    {debug-log var=$search_extras.facet_fields msg='$search_extras.facet_fields'}
{/if}
{def $baseURI=concat( '/content/search?SearchText=', $search_text )}

{* Build the URI suffix, used throughout all URL generations in this page *}
{def $uriSuffix = ''}
{foreach $activeFacetParameters as $facetField => $facetValue}
    {set $uriSuffix = concat( $uriSuffix, '&activeFacets[', $facetField, ']=', $facetValue )}
{/foreach}

{foreach $filterParameters as $name => $value}
    {set $uriSuffix = concat( $uriSuffix, '&filter[]=', $name, ':', $value )}
{/foreach}

{if gt( $dateFilter, 0 )}
    {set $uriSuffix = concat( $uriSuffix, '&dateFilter=', $dateFilter )}
{/if}

<script type="text/javascript">
{literal}
    // toggle block
    function ezfToggleBlock( id )
    {
        var value = (document.getElementById(id).style.display == 'none') ? 'block' : 'none';
        ezfSetBlock( id, value );
        ezfSetCookie( id, value );
    }

    function ezfSetBlock( id, value )
    {
        var el = document.getElementById(id);
        if ( el != null )
        {
            el.style.display = value;
        }
    }

    function ezfTrim( str )
    {
        return str.replace(/^\s+|\s+$/g, '') ;
    }

    function ezfGetCookie( name )
    {
        var cookieName = 'eZFind_' + name;
        var cookie = document.cookie;

        var cookieList = cookie.split( ";" );

        for( var idx in cookieList )
        {
            cookie = cookieList[idx].split( "=" );

            if ( ezfTrim( cookie[0] ) == cookieName )
            {
                return( cookie[1] );
            }
        }

        return 'none';
    }

    function ezfSetCookie( name, value )
    {
        var cookieName = 'eZFind_' + name;
        var expires = new Date();

        expires.setTime( expires.getTime() + (365 * 24 * 60 * 60 * 1000));

        document.cookie = cookieName + "=" + value + "; expires=" + expires + ";";
    }
{/literal}
</script>

<div class="content-search">

<form action={"/content/search/"|ezurl} method="get">

<div class="attribute-header">
    <h1 class="long">{"Search"|i18n("design/ezwebin/content/search")}</h1>
</div>


<div class="yui3-skin-sam ez-autocomplete">
    <input class="halfbox" type="text" size="20" name="SearchText" id="Search" value="{$search_text|wash}" />
    <input class="button" name="SearchButton" type="submit" value="{'Search'|i18n('design/ezwebin/content/search')}" />
</div>

{if $search_extras.spellcheck_collation}
     {def $spell_url=concat('/content/search/',$search_text|count_chars()|gt(0)|choose('',concat('?SearchText=',$search_extras.spellcheck_collation|urlencode)))|ezurl}
     <p>Quizás también te interese <b>{concat("<a href=",$spell_url,">")}{$search_extras.spellcheck_collation}</a></b></p>
{/if}


{if $stop_word_array}
    <p>
    {"The following words were excluded from the search"|i18n("design/base")}:
    {foreach $stop_word_array as $stopWord}
        {$stopWord.word|wash}
        {delimiter}, {/delimiter}
    {/foreach}
    </p>
{/if}

{switch name=Sw match=$search_count}
  {case match=0}
  <div class="warning">
  <h2>{'No results were found when searching for "%1".'|i18n("design/ezwebin/content/search",,array($search_text|wash))}</h2>
  {if $search_extras.hasError}
      {$search_extras.error|wash}
  {/if}
  {*if $search_extras.spellcheck_collation}
     <b>Did you mean {$search_extras.spellcheck_collation} ?</b>
  {/if*}
  </div>
    <p>{'Search tips'|i18n('design/ezwebin/content/search')}</p>
    <ul>
        <li>{'Check spelling of keywords.'|i18n('design/ezwebin/content/search')}</li>
        <li>{'Try changing some keywords (eg, "car" instead of "cars").'|i18n('design/ezwebin/content/search')}</li>
        <li>{'Try searching with less specific keywords.'|i18n('design/ezwebin/content/search')}</li>
        <li>{'Reduce number of keywords to get more results.'|i18n('design/ezwebin/content/search')}</li>
    </ul>
  {/case}
  {case}
  <div class="feedback">
  <h2>{'Search for "%1" returned %2 matches'|i18n("design/ezwebin/content/search",,array($search_text|wash,$search_count))}</h2>

  </div>

  
  {/case}
{/switch}

  <div id="search_results">

    {foreach $search_result as $result
             sequence array(bglight,bgdark) as $bgColor}
       {node_view_gui view=ezfind_line sequence=$bgColor use_url_translation=$use_url_translation content_node=$result}
    {/foreach}
    <div class="pagination">
    {include na me=Navigator
             uri='design:navigator/google.tpl'
             page_uri='/content/search'
             page_uri_suffix=concat('?SearchText=',$search_text|urlencode,$search_timestamp|gt(0)|choose('',concat('&SearchTimestamp=',$search_timestamp)), $uriSuffix )
             item_count=$search_count
             view_parameters=$view_parameters
             item_limit=$page_limit}
    </div>
  </div>
</form>

</div>

<p class="small"><em>{'Search took: %1 msecs, using '|i18n('ezfind',,array($search_extras.responseHeader.QTime|wash))}{$search_extras.engine}</em></p>


{ezscript_require( array('ezjsc::yui3', 'ezajax_autocomplete.js') )}
<script type="text/javascript">

YUI(YUI3_config).use('ezfindautocomplete', function (Y) {ldelim}
    Y.eZ.initAutoComplete({ldelim}
        url: "{'ezjscore/call/ezfind::autocomplete'|ezurl('no')}",
        inputSelector: '#Search',
        minQueryLength: {ezini( 'AutoCompleteSettings', 'MinQueryLength', 'ezfind.ini' )},
        resultLimit: {ezini( 'AutoCompleteSettings', 'Limit', 'ezfind.ini' )}
    {rdelim});
{rdelim});

{literal}
ezfSetBlock( 'ezfFacets', ezfGetCookie( 'ezfFacets' ) );
ezfSetBlock( 'ezfHelp', ezfGetCookie( 'ezfHelp' ) );
{/literal}
</script>
    </div></div>