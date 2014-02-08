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

$articles = eZContentObjectTreeNode::subTreeByNodeId(
    array(
        'ClassFilterType' => 'include',
        'ClassFilterArray' => array( 'article', 'blog_post' )
    ), 2
);

foreach( $articles as $article )
{
    $data = $article->dataMap();

    if( $data['body']->attribute( 'content' )->attribute( 'output' )->outputText() == '' )
        $cli->output( $article->attribute( 'name' ) );
    //else
    //    $cli->output( '.' );

}

$cli->output( "Done." );
$script->shutdown();
?>
