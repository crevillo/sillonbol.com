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

$videos = $olddb->arrayQuery( 'SELECT id,nombre,descripcion,url, UNIX_TIMESTAMP(fecha) AS fecha, tags FROM videos ORDER BY id DESC' );

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
       
        $exists = eZContentObjectTreeNode::subTreeByNodeId( 
            array(
                'ClassFilterType' => 'include',
                'ClassFilterArray' => array( 'video' ),
                'AttributeFilter' => array( array( 'video/id_antiguo', '=', $video['id'] ) )
            ), 2
        );
        
        if( $exists[0] )
        {

            $matches = array();
            preg_match('#<object[^>]+>.+?http://www.youtube.com/v/([A-Za-z0-9\-_]+).+?</object>#s', $video['url'], $matches);
            $youtubecode = $matches[1];
            if( empty( $youtubecode ) )
            {
                preg_match('#(\.be/|/embed/|/v/|/watch\?v=)([A-Za-z0-9_-]{5,11})#', $video['url'], $matches);
if(isset($matches[2]) && $matches[2] != '')
     $youtubecode = $matches[2];
            }
            
            
            $attributeList = array( 
                'youtube' => $youtubecode,
                'vimeo' => ''
            );

            
           
            $params                     = array();
           
            $params['attributes']       = $attributeList;    
            print_r( $exists[0]->attribute( 'content_object' ) ); 
            $result = eZContentFunctions::updateAndPublishObject( eZContentObject::fetch( $exists[0]->attribute( 'contentobject_id' )  ), $params );
            if( $result )
            {
               
                $cli->output( $youtubecode );
            }
        
    }
}


$cli->output( "Done." );
$script->shutdown();
?>
