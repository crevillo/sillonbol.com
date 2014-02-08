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

 function nl2p($string, $class='') { 
        $class_attr = ($class!='') ? ' class="'.$class.'"' : ''; 
        return 
            '<paragraph'.$class_attr.'>' 
            .preg_replace('#(<br\s*?/?>\s*?){2,}#', '</paragraph>'."\n".'<paragraph'.$class_attr.'>', nl2br($string, true)) 
            .'</paragraph>'; 
    } 

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

$articulos = $olddb->arrayQuery( 'SELECT titulo, subtitulo, autor, texto, blog,
                                     UNIX_TIMESTAMP(fecha) AS fecha,
                                     tags,
                                     articulos.id as id,
                                     usuario       
                              FROM articulos LEFT JOIN autores ON articulos.autor = autores.nombre
                              WHERE blog <> ""'
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

$xmlDeclaration = '<?xml version="1.0" encoding="utf-8"?> <section xmlns:image="http://ez.no/namespaces/ezpublish3/image/" xmlns:xhtml="http://ez.no/namespaces/ezpublish3/xhtml/" xmlns:custom="http://ez.no/namespaces/ezpublish3/custom/"><paragraph xmlns:tmp="http://ez.no/namespaces/ezpublish3/temporary/"><custom name="ezbbcode">';

$xmlEnd = '</custom></paragraph></section>';
$addedPolls = array();
foreach( $articulos as $articulo )
{       
    
        // buscar blog al que pertenece
        $parent = eZContentObjectTreeNode::subTreeByNodeId( 
            array(
                'ClassFilterType' => 'include',
                'ClassFilterArray' => array( 'blog' ),
                'AttributeFilter' => array( array( 'blog/nombre', '=', $articulo['blog'] ) )
            ), 2
        );

        $exists = eZContentObjectTreeNode::subTreeCountByNodeId( 
            array(
                'ClassFilterType' => 'include',
                'ClassFilterArray' => array( 'blog_post' ),
                'AttributeFilter' => array( array( 'blog_post/id_articulo', '=', $articulo['id'] ) )
            ), 2
        );
       
        if( !$exists )
        {
           
            $creatorId = 14;
            $classIdentifier = 'blog_post';
            $parentNodeID = $parent[0] ? $parent[0]->attribute( 'node_id' ) : 1523;
            
            $attributeList = array( 
                'title' => $articulo['titulo'],
                'short_title' => $articulo['subtitulo'],
                'autor' => $articulo['autor'],
                'body' => $xmlDeclaration . nl2p( $articulo['texto'] ) . $xmlEnd,
                'image' => '/home/carlos/sbold/fotos/' . $articulo['id'] . '.jpg',
                'id_articulo' => $articulo['id']
            );

            $tagsexploded = explode( ',', $articulo['tags'] );
        
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
                $contentObject->setAttribute( 'published', $articulo['fecha'] );
                $contentObject->setAttribute( 'modified', $articulo['fecha'] );
                $contentObject->store();
                $cli->output( $contentObject->attribute( 'name' ) );
            }
        
    }
}


$cli->output( "Done." );
$script->shutdown();
?>
