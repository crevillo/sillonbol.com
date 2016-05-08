<?php

$Module = array( 'name'            => 'Redirects',
                 'variable_params' => true );

$ViewList = array();
$ViewList['articles'] = array(
    'functions' => array( 'redirect' ),
    'script' => 'articles.php',
    'params'                  => array( 'url' )
);

$FunctionList = array();
$FunctionList['redirect'] = array();
?>
