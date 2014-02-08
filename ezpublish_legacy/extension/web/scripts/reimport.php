<?php
/**
 * File containing the reimport.php script.
 *
 */

require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "\n" .
                                                         "Script para importar los tags de la web antigua.\n" ),
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );
$script->startup();

$scriptOptions = $script->getOptions();
$script->initialize();

$data = simplexml_load_file("http://www.google.com/reader/public/atom/user/08297133034773582494/state/com.google/starred?n=100");

$ini = eZINI::instance();
// Get user's ID who can remove subtrees. (Admin by default with userID = 14)
$userCreatorID = $ini->variable( "UserSettings", "UserCreatorID" );
$user = eZUser::fetch( $userCreatorID );
if ( !$user )
{
    $cli->error( "Subtree remove Error!\nCannot get user object by userID = '$userCreatorID'.\n(See site.ini[UserSettings].UserCreatorID)" );
    $script->shutdown( 1 );
}
eZUser::setCurrentlyLoggedInUser( $user, $userCreatorID );

$xmlDeclaration = '<?xml version="1.0" encoding="utf-8"?> <section xmlns:image="http://ez.no/namespaces/ezpublish3/image/" xmlns:xhtml="http://ez.no/namespaces/ezpublish3/xhtml/" xmlns:custom="http://ez.no/namespaces/ezpublish3/custom/"><paragraph xmlns:tmp="http://ez.no/namespaces/ezpublish3/temporary/">';

$xmlEnd = '</section>';

$creatorID = 14;
$classIdentifier = 'article';
$parentNodeID = 826;

foreach( $data->entry as $item )
{
    $parser = new webInputParser();
    $parser->setParseLineBreaks( true );

    $document = $parser->process( $item->summary );

    // Create XML structure
    $xmlString = eZXMLTextType::domString( $document );

    $attributeList = array( 
        'title' => $item->title,
        'autor' => $item->author->name,
        'body' => $xmlString,
    );

    $params                     = array();
    $params['creator_id']       = $creatorID;
    $params['class_identifier'] = $classIdentifier;
    $params['parent_node_id']   = $parentNodeID;
    $params['attributes']       = $attributeList;

    $contentObject = eZContentFunctions::createAndPublishObject( $params );
    if( $contentObject )
    {
        $contentObject->setAttribute( 'published', strtotime( $item->published ) );
        $contentObject->setAttribute( 'modified', strtotime( $item->published ) );
        $contentObject->store();
        $cli->output( $contentObject->attribute( 'name' ) );
    }
}
$cli->output( "Done." );
$script->shutdown();
?>