{default enable_help=true() enable_link=true() canonical_link=true()}

{if is_set(ezpagedata().persistent_variable.site_title)}
    {def $site_title=concat( $pagedata.persistent_variable.site_title, ' - Sillonbol.com' )}
{else}
{let name=Path
     path=$module_result.path
     reverse_path=array()}
  {if is_set($pagedata.path_array)}
    {set path=$pagedata.path_array}
  {elseif is_set($module_result.title_path)}
    {set path=$module_result.title_path}
  {/if}
  {section loop=$:path}
    {set reverse_path=$:reverse_path|array_prepend($:item)}
  {/section}
{set-block scope=root variable=site_title}
{section loop=$Path:reverse_path}{$:item.text|wash}{delimiter} / {/delimiter}{/section}
{/set-block}

{/let}

{/if}
    <title>{$site_title}</title>

    {if and(is_set($#Header:extra_data),is_array($#Header:extra_data))}
      {section name=ExtraData loop=$#Header:extra_data}
      {$:item}
      {/section}
    {/if}

    {* check if we need a http-equiv refresh *}
    {if $site.redirect}
    <meta http-equiv="Refresh" content="{$site.redirect.timer}; URL={$site.redirect.location}" />

    {/if}
    {foreach $site.http_equiv as $key => $item}
        <meta name="{$key|wash}" content="{$item|wash}" />

    {/foreach}
    {foreach $site.meta as $key => $item}
    {if is_set( $module_result.content_info.persistent_variable[$key] )}
        <meta name="{$key|wash}" property="og:{$key|wash}" content="{$module_result.content_info.persistent_variable[$key]|wash}" />
    {else}
        <meta name="{$key|wash}" content="{$item|wash}" />
    {/if}

    {/foreach}

    {* Prefer chrome frame on IE 8 and lower, or at least as new engine as possible *}
    <!--[if lt IE 9 ]>
        <meta http-equiv="X-UA-Compatible" content="IE=8,chrome=1" />
    <![endif]-->

    <meta name="MSSmartTagsPreventParsing" content="TRUE" />
    <meta name="generator" content="eZ Publish" />

{if $canonical_link}
    {include uri="design:canonical_link.tpl"}
{/if}

{if $enable_link}
    {include uri="design:link.tpl" enable_help=$enable_help enable_link=$enable_link}
{/if}

{if is_set(ezpagedata().persistent_variable.node_image)}

<meta property="og:image" content="http://www.sillonbol.com{$pagedata.persistent_variable.node_image}" />
{if is_set(ezpagedata().persistent_variable.site_title)}
<meta property="og:title" content="{$pagedata.persistent_variable.site_title}"/>
{/if}
<meta property="og:site_name" content="Sillonbol.com"/>

<meta property="og:locale" content="es_ES" />

<meta property="og:type" content="article" /> 

<meta property="og:type" content="article" /> 


{/if}

{/default}
