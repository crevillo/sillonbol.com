{def $collection = cond( $collection_id, fetch( content, collected_info_collection, hash( collection_id, $collection_id ) ),
                          fetch( content, collected_info_collection, hash( contentobject_id, $node.contentobject_id ) ) )}

{set-block scope=global variable=title}{'Form %formname'|i18n( 'design/ezwebin/collectedinfo/form', , hash( '%formname', $node.name|wash() ) )}{/set-block}

<article class="grid_12">
                                                   <div class="box">
                                                   		<div class="box-padding">
                                                        	<h1 class="f-s color-3 text-shadow">{$object.name|wash}</h1>
<div class="wysiwyg">

<p>{'Thank you for your feedback.'|i18n( 'design/ezwebin/collectedinfo/form' )}</p>

{if $error}

{if $error_existing_data}
<p>{'You have already submitted this form. The data you entered was:'|i18n('design/ezwebin/collectedinfo/form')}</p>
{/if}

{/if}

{foreach $collection.attributes as $attribute}

<p><strong>{$attribute.contentclass_attribute_name|wash}:</strong> {attribute_result_gui view=info attribute=$attribute} </p>

{/foreach}


<a href={$node.parent.url|ezurl}>{'Volver a la página principal'|i18n('design/ezwebin/collectedinfo/form')}</a>

</div></div>
</div>

</article>

