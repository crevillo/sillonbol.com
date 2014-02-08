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

$xmlDeclaration = '<?xml version="1.0" encoding="utf-8"?> <section xmlns:image="http://ez.no/namespaces/ezpublish3/image/" xmlns:xhtml="http://ez.no/namespaces/ezpublish3/xhtml/" xmlns:custom="http://ez.no/namespaces/ezpublish3/custom/"><section><paragraph>';

$xmlEnd = '</paragraph></section></section>';

$olddb_conn_params = array( 
    'use_defaults' => false,
    'server' => $oldsettingsini->variable( 'OldDatabaseSettings', 'Server' ),
    'port' => $oldsettingsini->variable( 'OldDatabaseSettings', 'Port' ),
    'user' => $oldsettingsini->variable( 'OldDatabaseSettings', 'User' ),
    'database' => $oldsettingsini->variable( 'OldDatabaseSettings', 'Database' ),
    'password' => $oldsettingsini->variable( 'OldDatabaseSettings', 'Password' )
);

$olddb = eZDB::instance( 'ezmysqli', $olddb_conn_params, true );

$categorias = $olddb->arrayQuery( 'SELECT id_categoria, categoria FROM enlaces_categorias' );
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


foreach( $categorias as $categoria )
{
    $exists = eZContentObjectTreeNode::subTreeCountByNodeId( 
        array(
            'ClassFilterType' => 'include',
            'ClassFilterArray' => array( 'carpeta_enlaces' ),
            'AttributeFilter' => array( array( 'carpeta_enlaces/id_antiguo', '=', $categoria['id_categoria'] ) )
        ), 2
    );
    if( !$exists )
    {
        $creatorId = $userCreatorID;
        $classIdentifier = 'carpeta_enlaces';
        $parentNodeID = 375;
        $attributeList = array( 
            'nombre' => $categoria['categoria'],
            'id_antiguo' => $categoria['id_categoria'],
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

$subcategorias = $olddb->arrayQuery( 'SELECT id_categoria, subcategoria, id_subcategoria FROM enlaces_subcategorias' );
$db = eZDB::instance( 'ezmysqli', array( 'use_defaults' => true ), true );
foreach( $subcategorias as $subcategoria )
{
    // find parent
    $parent = eZContentObjectTreeNode::subTreeByNodeId( 
        array(
            'ClassFilterType' => 'include',
            'ClassFilterArray' => array( 'carpeta_enlaces' ),
            'Depth' => 3,
            'AttributeFilter' => array( array( 'carpeta_enlaces/id_antiguo', '=', $subcategoria['id_categoria'] ) )
        ), 375
    );
    if( $parent[0] )
    {
        $exists = eZContentObjectTreeNode::subTreeCountByNodeId( 
            array(
                'ClassFilterType' => 'include',
                'ClassFilterArray' => array( 'carpeta_enlaces' ),
                'Depth' => 4,
                'AttributeFilter' => array( array( 'carpeta_enlaces/id_antiguo', '=', $subcategoria['id_subcategoria'] ) )
            ), $parent[0]->attribute( 'node_id' )
        );
        if( !$exists )
        {
            $creatorId = $userCreatorID;
            $classIdentifier = 'carpeta_enlaces';
            $parentNodeID = $parent[0]->attribute( 'node_id' );
            $attributeList = array( 
                'nombre' => $subcategoria['subcategoria'],
                'id_antiguo' => $subcategoria['id_subcategoria'],
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
    
}

$olddb = eZDB::instance( 'ezmysqli', $olddb_conn_params, true );

$enlaces = $olddb->arrayQuery( 'SELECT id_subcategoria,nombre, url, comentario,url_reciproco,email,id FROM enlaces' );

$db = eZDB::instance( 'ezmysqli', array( 'use_defaults' => true ), true );
foreach( $enlaces as $enlace )
{
    // find parent
    $parent = eZContentObjectTreeNode::subTreeByNodeId( 
        array(
            'ClassFilterType' => 'include',
            'ClassFilterArray' => array( 'carpeta_enlaces' ),
            'Depth' => 4,
            'AttributeFilter' => array( array( 'carpeta_enlaces/id_antiguo', '=', $enlace['id_subcategoria'] ) )
        ), 375
    );

    if( $parent[0] )
    {
        $exists = eZContentObjectTreeNode::subTreeCountByNodeId( 
            array(
                'ClassFilterType' => 'include',
                'ClassFilterArray' => array( 'link' ),
                'AttributeFilter' => array( array( 'link/id_antiguo', '=', $enlace['id'] ) )
            ), $parent[0]->attribute( 'node_id' )
        );

        if( !$exists  )
        {
            $creatorId = $userCreatorID;
            $classIdentifier = 'link';
            $parentNodeID = $parent[0]->attribute( 'node_id' );
            $attributeList = array( 
                'name' => $enlace['nombre'],
                'email' => $enlace['email'],
                'location' => $enlace['url'] . '|' . $enlace['nombre'],
                'url_reciproco' => $enlace['url_reciproco'],
                'description' => $xmlDeclaration . $enlace['comentario'] . $xmlEnd,
                'id_antiguo' => $enlace['id'],
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
    
}


$cli->output( "Done." );
$script->shutdown();
?>
