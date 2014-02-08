<?php
/**
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package web
 */

/*
 * Definimos una única función
 */
$Module = array(
    'name' => 'rss',
    'variable_params' => true,
    'function' => array(
        'script' => 'rss.php',
        'params' => array( 'Feed' ),
        'functions' => array( 'rss' )
    )
);

$ViewList = array();

$FunctionList = array();
$FunctionList['rss'] = array();
?>
