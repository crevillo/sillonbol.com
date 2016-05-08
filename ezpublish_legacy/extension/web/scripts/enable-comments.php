<?php
/**
 * File containing the locate-articles.php script.
 * 
 * In our previous version we added the locations under one unique folder, 
 * because how the site were build before moved to eZ Publish
 * 
 * Now we prefer to have separate folder for articles. We used tags
 * for relocated them
 *
 */

require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "\n" .
                                                         "Script para habilitar comentarios y que
                                                             así cargue disqus.\n" ),
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );
$script->startup();

$scriptOptions = $script->getOptions();
$script->initialize();

$ini = eZINI::instance();
// Get user's ID who can remove subtrees. (Admin by default with userID = 14)
$userCreatorID = $ini->variable( "UserSettings", "UserCreatorID" );
$user = eZUser::fetch( $userCreatorID );
if ( !$user )
{
    $cli->error( "Admin user not found" );
    $script->shutdown( 1 );
}
eZUser::setCurrentlyLoggedInUser( $user, $userCreatorID );

$articles = eZContentObjectTreeNode::subTreeByNodeId(
    array(
        'ClassFilterType' => 'include',
        'ClassFilterArray' => array( 'article' )
    ), 2
);


foreach( $articles as $article )
{
    $data = $article->dataMap();
    $cli->output( $data['comments']->attribute( 'data_int' ) );
    $data['comments']->setAttribute( 'data_int', 1 );
    $data['comments']->store();
}

$cli->output( "Done." );
$script->shutdown();
?>