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

$users = $olddb->arrayQuery( 'SELECT usuario,password FROM usuarios WHERE admin=1' );
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
foreach( $users as $usuario )
{
    $userByEmail = eZUser::fetchByEmail( $usuario['usuario']. '@sillonbol.com' );
    if( !$userByEmail )
    {
        $creatorId = $userCreatorID;
        $classIdentifier = 'user';
        $parentNodeID = 147;
        $attributeList = array( 
            'first_name' => $usuario['usuario'],
            'user_account' => $usuario['usuario'] . '|' . $usuario['usuario']. '@sillonbol.com|'.$usuario['password'] . '|md5_password|1'
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

$users = $olddb->arrayQuery( 'SELECT usuario,password FROM usuarios WHERE admin=0' );
$db = eZDB::instance( 'ezmysqli', array( 'use_defaults' => true ), true );

foreach( $users as $usuario )
{
    $userByEmail = eZUser::fetchByEmail( $usuario['usuario']. '@sillonbol.com' );
    if( !$userByEmail )
    {
        $creatorId = $userCreatorID;
        $classIdentifier = 'user';
        $parentNodeID = 172;
        $attributeList = array( 
            'first_name' => $usuario['usuario'],
            'user_account' => $usuario['usuario'] . '|' . $usuario['usuario']. '@sillonbol.com|'.$usuario['password'] . '|md5_password|1'
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


$cli->output( "Done." );
$script->shutdown();
?>
