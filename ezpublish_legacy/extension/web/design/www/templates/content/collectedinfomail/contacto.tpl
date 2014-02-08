{set-block scope=root variable=subject}Formulario de Contacto sillonbol.com{/set-block}
{set-block scope=root variable=email_sender}hola@sillonbol.com{/set-block}
{set-block scope=root variable=email_receiver}hola@sillonbol.com{/set-block}
CAMPOS DEL FORMULARIO:
{foreach $collection.attributes as $attribute}
{$attribute.contentclass_attribute_name|wash()}:{attribute_result_gui view=info attribute=$attribute}
{/foreach}

