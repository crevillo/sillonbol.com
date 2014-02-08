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

$contentini = eZINI::instance( 'ezoe.ini' );
print_r( $contentini->variable( 'EditorLayout', 'Buttons' ) );


$cli->output( "Done." );
$script->shutdown();
?>
