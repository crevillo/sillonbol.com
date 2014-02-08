{ezscript_require( array( 'ezjsc::jquery', 'ezjsc::jqueryio', 'contacto/contacto.js' ) )}
                                               <article class="grid_12">
                                                   <div class="box">
                                                   		<div class="box-padding">
                                                        	<h1 class="f-s color-3 text-shadow">{$node.name}</h1>
<div class="wysiwyg">
                                                                {$node.data_map.intro.content.output.output_text}
                                                            </div>

<div id="contact-form">

    {include name=Validation uri='design:content/collectedinfo_validation.tpl'
                 class='message-warning'
                 validation=$validation collection_attributes=$collection_attributes}
    <form method="post" action={"content/action"|ezurl}>
    {attribute_view_gui attribute=$node.data_map.nombre}
    {attribute_view_gui attribute=$node.data_map.tu_e_mail}
    {attribute_view_gui attribute=$node.data_map.comment}
    {attribute_view_gui attribute=$node.data_map.captcha}
    <div class="content-action">
            <input type="submit" name="ActionCollectInformation" value="{"Send form"|i18n("design/ezwebin/full/feedback_form")}" id="contact-form-send" />
            <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
            <input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
            <input type="hidden" name="ViewMode" value="full" />
        </div>
        </form>
</div>
</div>
</div>
</article>
