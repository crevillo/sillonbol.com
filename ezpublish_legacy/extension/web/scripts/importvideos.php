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

$videos = $olddb->arrayQuery( 'SELECT id,nombre,descripcion,url, UNIX_TIMESTAMP(fecha) AS fecha, tags FROM videos' );

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

$xmlDeclaration = '<?xml version="1.0" encoding="utf-8"?> <section xmlns:image="http://ez.no/namespaces/ezpublish3/image/" xmlns:xhtml="http://ez.no/namespaces/ezpublish3/xhtml/" xmlns:custom="http://ez.no/namespaces/ezpublish3/custom/"><paragraph xmlns:tmp="http://ez.no/namespaces/ezpublish3/temporary/">';
$xmlEnd='</paragraph></section>';
$addedPolls = array();
foreach( $videos as $video )
{
        print_r( $video );
        $exists = eZContentObjectTreeNode::subTreeCountByNodeId( 
            array(
                'ClassFilterType' => 'include',
                'ClassFilterArray' => array( 'video' ),
                'AttributeFilter' => array( array( 'video/id_antiguo', '=', $video['id'] ) )
            ), 2
        );
        
        if( !$exists )
        {
            $matches = array();
            preg_match('#<object[^>]+>.+?http://www.youtube.com/v/([A-Za-z0-9\-_]+).+?</object>#s', $video['url'], $matches);
            $youtubecode = $matches[1];
            $creatorId = 14;
            $classIdentifier = 'video';
            $parentNodeID = 2541;
            
            $attributeList = array( 
                'nombre' => $video['nombre'],
                'descripcion' => $xmlDeclaration . nl2p( $video['descripcion'] ) . $xmlEnd,
                'youtube' => $youtubecode,
                'id_antiguo' => $video['id']
            );

            $tagsexploded = explode( ',', $video['tags'] );
        
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
                $contentObject->setAttribute( 'published', $video['fecha'] );
                $contentObject->setAttribute( 'modified', $video['fecha'] );
                $contentObject->store();
                $cli->output( $contentObject->attribute( 'name' ) );
            }
        
    }
}


$cli->output( "Done." );
$script->shutdown();
?>
