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

$tags = $olddb->arrayQuery( 'SELECT tag FROM tags ORDER BY id' );
$db = eZDB::instance( 'ezmysqli', array( 'use_defaults' => true ), true );
print_r( $db );
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
foreach( $tags as $keyword )
{
    print !eZTagsObject::exists( 0, $keyword['tag'], 0 );
    if ( !empty( $keyword['tag'] ) && !eZTagsObject::exists( 0, $keyword['tag'], 0 ) )
    {
        
        $db->begin();

        $tag = new eZTagsObject( array( 'parent_id'   => 0,
                                        'main_tag_id' => 0,
                                        'keyword'     => $keyword['tag'],
                                        'depth'       => 1,
                                        'path_string' => '/' ) );

        $tag->store();
        $tag->setAttribute( 'path_string', $tag->attribute( 'path_string' ) . $tag->attribute( 'id' ) . '/' );
        $tag->store();
        $tag->updateModified();

        /* Extended Hook */
        if ( class_exists( 'ezpEvent', false ) )
            ezpEvent::getInstance()->filter( 'tag/add', array( 'tag' => $tag ) );

        $db->commit();
        $cli->output( $keyword['tag'] );
    }
}


$cli->output( "Done." );
$script->shutdown();
?>
