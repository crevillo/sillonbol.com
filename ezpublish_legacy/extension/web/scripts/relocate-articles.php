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
                                                         "Script para importar los tags de la web antigua.\n" ),
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
    ), 826
);

$tagsToCheck = array(
    'Fútbol' => 200,
    'Baloncesto' => 201,
    'Football' => 202,
    'Tenis' => 203,
    'Ciclismo' => 204
);

foreach( $articles as $article )
{
    $data = $article->dataMap();
    foreach ( $data['tags']->content()->attribute( 'tags' ) as $tag )
    {
        if ( in_array( $tag->attribute( 'keyword' ), array_keys( $tagsToCheck ) ) )
        {
            $destinationNodeId = $tagsToCheck[$tag->attribute( 'keyword' )];
            print $destinationNodeId . "\n";
            moveUtils::moveSubTree( $article->attribute( 'node_id'), $destinationNodeId );
            // die( $article->attribute( 'name' ) );
            break;
        }
    }
}

$cli->output( "Done." );
$script->shutdown();
?>