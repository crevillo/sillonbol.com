<?php

class webServerFunctions extends ezjscServerFunctions
{
    public static function comment( $args )
    {
        $http = eZHTTPTool::instance();
        $name = $http->postVariable( 'name' );
        $message = $http->postVariable( 'message' );
        $parentNodeID = $http->postVariable( 'node_id' );

        $params = array();
        $attributeList = array();
        $attributeList['author'] = $name;
        $attributeList['message'] = $message;        
        $params['creator_id']       = eZUser::currentUser()->attribute( 'contentobject_id' );
        $params['class_identifier'] = 'comment';
        $params['parent_node_id']   = $parentNodeID;
        $params['attributes']       = $attributeList;       

        $contentObject = eZContentFunctions::createAndPublishObject( $params );

        if( $contentObject )
        {
            $tpl = eZTemplate::factory();
            $tpl->setVariable( 'comment', $contentObject->attribute( 'main_node' ) );
            $comment = $tpl->fetch( 'design:ezjscore/comment.tpl' );
            $comments_header = $contentObject->attribute( 'main_node' )->attribute( 'parent' )->attribute( 'children_count' );
            $comments_header .= $comments_header != 1 ? ' comentarios' : ' comentario';
            //Enviamos mail
            $tpl = eZTemplate::factory();
            $tpl->setVariable( 'name', $name );
            $tpl->setVariable( 'message', $message );
            $tpl->setVariable( 'comment', $contentObject->attribute( 'main_node' ) );
            $body = $tpl->fetch( 'design:mails/comment.tpl' );
            $mail = new eZMail();
            $mail->setReceiver( 'hola@sillonbol.com' );
            $mail->setSender( 'hola@sillonbol.com' );
            $mail->setSubject( 'Comentario en Sillonbol.com' );
            $mail->setBody( $body );
            $mailResult = eZMailTransport::send( $mail );
            return array(
                'comment' => $comment,
                'comments_header' => $comments_header,
                'node_id' => $contentObject->attribute( 'main_node_id' )
            );
        }

        
    }
}

?>
