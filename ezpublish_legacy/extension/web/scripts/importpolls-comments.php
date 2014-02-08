<?php
/**
 * File containing the importags.php script.
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

$oldsettingsini = eZINI::instance( 'oldsettings.ini' );

$olddb_conn_params = array( 
    'use_defaults' => false,
    'server' => $oldsettingsini->variable( 'OldDatabaseSettings', 'Server' ),
    'port' => $oldsettingsini->variable( 'OldDatabaseSettings', 'Port' ),
    'user' => $oldsettingsini->variable( 'OldDatabaseSettings', 'User' ),
    'database' => $oldsettingsini->variable( 'OldDatabaseSettings', 'Database' ),
    'password' => $oldsettingsini->variable( 'OldDatabaseSettings', 'Password' )
);

$olddb = eZDB::instance( 'ezmysqli', $olddb_conn_params, true );

$comments = $olddb->arrayQuery( 'SELECT nombre, 
                                     UNIX_TIMESTAMP(fecha) AS fecha,
                                     texto,
                                     id,
                                     articulo       
                              FROM polls_comentarios'
                           );

$db = eZDB::instance( 'ezmysqli', array( 'use_defaults' => true ), true );
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

$xmlDeclaration = '<?xml version="1.0" encoding="utf-8"?> <section xmlns:image="http://ez.no/namespaces/ezpublish3/image/" xmlns:xhtml="http://ez.no/namespaces/ezpublish3/xhtml/" xmlns:custom="http://ez.no/namespaces/ezpublish3/custom/"><paragraph>';

$xmlEnd = '</paragraph></section>';
$addedPolls = array();
$solr = new eZSolr();
foreach( $comments as $comment )
{
    $parent = $solr->search( '', 
        array(
            'ClassFilterType' => 'include',
            'ClassFilterArray' => array( 'poll' ),
            'Filter' => array( array( 'poll/id_antiguo', '=', $comment['articulo'] ) )
        )
    );
   
    if( $parent[0] )
    {
        $exists = eZContentObjectTreeNode::subTreeCountByNodeId( 
            array(
                'ClassFilterType' => 'include',
                'ClassFilterArray' => array( 'comment' ),
                'AttributeFilter' => array( array( 'comment/id_antiguo', '=', $comment['id'] ) )
            ), 456
        );
        
        if( !$exists )
        {
            $creatorId = 10;
            $classIdentifier = 'comment';
            $parentNodeID = $parent[0]->attribute( 'node_id' );
            
            $attributeList = array( 
                'author' => $comment['nombre'],
                'message' => $comment['texto'],
                'id_antiguo' => $comment['id'],
            );
           
            $params                     = array();
            $params['creator_id']       = $creatorID;
            $params['class_identifier'] = $classIdentifier;
            $params['parent_node_id']   = $parentNodeID;
            $params['attributes']       = $attributeList;     
            $contentObject = eZContentFunctions::createAndPublishObject( $params );
            if( $contentObject )
            {
                $contentObject->setAttribute( 'published', $comment['fecha'] );
                $contentObject->setAttribute( 'modified', $comment['fecha'] );
                $contentObject->store();
                $cli->output( $contentObject->attribute( 'name' ) );
            }
        }
    }
}


$cli->output( "Done." );
$script->shutdown();
?>
