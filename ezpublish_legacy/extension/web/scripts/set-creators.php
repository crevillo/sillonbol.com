<?php
/**
 * Reasignamos creadores a los artículos tras la recuperación 
 * vía google Reader. 
 * Esa recuperación se hizo con el admin user para todos. 
 *
 */

require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "\n" .
                                                         "Script para reasignar autores de los artículos.\n" ),
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
    $cli->error( "No hay admin?" );
    $script->shutdown( 1 );
}
eZUser::setCurrentlyLoggedInUser( $user, $userCreatorID );

$articles = eZContentObjectTreeNode::subTreeByNodeID(
    array(
        'ClassFilterType' => 'include',
        'ClassFilterArray' => array( 'article', 'blog_post' ),
        'AttributeFilter' => array(
            'and',
            array( 'published', '>', 1355569278 ), // 15 Dic 2012
        ),
    ),
    2
);

$owners = array(
    'Roberto' => 8227,
    'Pepe' => 167,
    'merucovic' => 169,
    'joselurr' => 169,
    'Carlos' => 6763
);

foreach ( $articles as $article )
{
    if( $article->attribute( 'object' )->attribute( 'owner_id') == 14 )
    {
        $data = $article->dataMap();
        $cli->output( $article->attribute( 'name' ) );
        $cli->output( $data['autor']->content() );
        $cli->output( $article->attribute( 'object' )->attribute( 'owner_id' ) );

        foreach ( $owners as $index => $ownerID )
        {
            if( preg_match( "/$index/", $data['autor']->content() ) )
            {
                $cli->output( $ownerID );
                $article->attribute( 'object' )
                    ->setAttribute( 'owner_id', $ownerID );
                $article->attribute( 'object' )->store();
                break;
            }
        }
    }
}

$cli->output( "Done." );
$script->shutdown();
?>
