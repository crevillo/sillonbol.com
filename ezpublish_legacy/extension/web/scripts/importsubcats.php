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

$subcats = $olddb->arrayQuery( 'SELECT DISTINCT( categoria ) FROM subcategorias' );
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
foreach( $subcats as $subcat )
{
    $exists = eZContentObjectTreeNode::subTreeCountByNodeId( 
        array(
            'ClassFilterType' => 'include',
            'ClassFilterArray' => array( 'subcategoria' ),
            'AttributeFilter' => array( array( 'name', '=', $subcat['categoria'] ) )
        ), 2
    );
    if( !$exists )
    {
        $creatorId = $userCreatorID;
        $classIdentifier = 'subcategoria';
        $parentNodeID = 2;
        $attributeList = array( 
            'nombre' => $subcat['categoria'],
            'tags' => '0|#' . $subcat['categoria'] .  '|#0'
        );
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

$olddb = eZDB::instance( 'ezmysqli', $olddb_conn_params, true );

$subcats = $olddb->arrayQuery( 'SELECT categoria, subcategoria, orden FROM subcategorias' );
$db = eZDB::instance( 'ezmysqli', array( 'use_defaults' => true ), true );
print_r( $subcats );
foreach( $subcats as $subcat )
{

    //buscar padre
    $parent = eZContentObjectTreeNode::subTreeByNodeId( 
        array(
            'ClassFilterType' => 'include',
            'ClassFilterArray' => array( 'subcategoria' ),
            'AttributeFilter' => array( array( 'name', '=', $subcat['categoria'] ) )
        ), 2
    ); 
    //comprobar si ya había
    $exists = eZContentObjectTreeNode::subTreeCountByNodeId( 
        array(
            'ClassFilterType' => 'include',
            'ClassFilterArray' => array( 'subcategoria' ),
            'AttributeFilter' => array( array( 'name', '=', $subcat['subcategoria'] ) )
        ), $parent[0]->attribute( 'node_id' )
    );
    if( !$exists )
    {
            
        //a subir
        $creatorId = $userCreatorID;
        $classIdentifier = 'subcategoria';
        $parentNodeID = $parent[0]->attribute( 'node_id' );
        $attributeList = array( 
            'nombre' => $subcat['subcategoria'],
            'tags' => '0|#' . $subcat['subcategoria'] .  '|#0'
        );
        $params                     = array();
        $params['creator_id']       = $creatorID;
        $params['class_identifier'] = $classIdentifier;
        $params['parent_node_id']   = $parentNodeID;
        $params['attributes']       = $attributeList;

        $contentObject = eZContentFunctions::createAndPublishObject( $params );
        if( $contentObject )
        {
            $cli->output( $contentObject->attribute( 'name' ) );
            $db->query( 'UPDATE ezcontentobject_tree SET priority = ' . $subcat['orden'] . ' WHERE node_id = ' .  $contentObject->attribute( 'main_node_id' ) );
        }
    }
}

$cli->output( "Done." );
$script->shutdown();
?>
