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

$blogs = $olddb->arrayQuery( 'SELECT * FROM blogs' );
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
foreach( $blogs as $blog )
{
    $exists = eZContentObjectTreeNode::subTreeCountByNodeId( 
        array(
            'ClassFilterType' => 'include',
            'ClassFilterArray' => array( 'blog' ),
            'AttributeFilter' => array( array( 'name', '=', $blog['nombre'] ) )
        ), 2
    );
    if( !$exists )
    {
        $creatorId = $userCreatorID;
        $classIdentifier = 'blog';
        $parentNodeID = 262;
        $attributeList = array( 
            'nombre' => $blog['nombre'],
            'titulo' => $blog['titulo'],
            'description' => $xmlDeclaration . $blog['descripcion'] . $xmlEnd,
            'foto' => '/home/carlos/workspace/sillonblogold/imagenes/blog/' . $blog['foto'] . '.jpg',
            'id_antiguo' => $blog['id']
        );
        $tagsexploded = explode( ',', $blog['tag_defecto'] );
        
        $tagsstring = '';
        $tagsids = array();
        $tagskeywords = array();
        $tagsparents = array();
        foreach( $tagsexploded as $tag )
        {
            $tagsids[] = '0';
            $tagskeywords[] = trim( $tag );
            $tagsparents[] = 0;
        }
        if( count( $tagsids ) )
            $tagsstring = implode( '|#', $tagsids ) . '|#' . implode( '|#', $tagskeywords ) . '|#' . implode( '|#', $tagsparents );
        $cli->output( $tagsstring );
        $attributeList['tags'] = $tagsstring;

        $params                     = array();
        $params['creator_id']       = $creatorID;
        $params['class_identifier'] = $classIdentifier;
        $params['parent_node_id']   = $parentNodeID;
        $params['attributes']       = $attributeList;

        $contentObject = eZContentFunctions::createAndPublishObject( $params );
        if( $contentObject )
        {
            $cli->output( $contentObject->attribute( 'name' ) );
        }
    }
}


$cli->output( "Done." );
$script->shutdown();
?>
