{* DO NOT EDIT THIS FILE! Use an override template instead. *}
<div class="grid_12">
                                                   <div class="box">
                                                   		<div class="box-padding">
<div class="message-warning">
<h2>{"Object is unavailable"|i18n("design/standard/error/kernel")}</h2>
<p>{"The object you requested is not currently available."|i18n("design/standard/error/kernel")}</p>
<p>{"Possible reasons for this are"|i18n("design/standard/error/kernel")}:</p>
<ul>
    <li>{"The id or name of the object was misspelled, try changing it."|i18n("design/standard/error/kernel")}</li>
    <li>{"The object is no longer available on the site."|i18n("design/standard/error/kernel")}</li>
</ul>
</div>

{if $embed_content}

{$embed_content}
{/if}
</div></div></div>
