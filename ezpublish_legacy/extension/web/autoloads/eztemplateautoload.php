<?php

$eZTemplateOperatorArray = array();

$eZTemplateOperatorArray[] = array( 'script' => 'extension/web/autoloads/ezbbcode.php',
                                    'class' => 'eZBBCode',
                                    'operator_names' => array( 'bbcodetohtml' ) );

$eZTemplateOperatorArray[] = array( 'script' => 'extension/web/autoloads/webtwitter.php',
                                    'class' => 'webTwitter',
                                    'operator_names' => array( 'parsetext', 'parsetime' ) );

$eZTemplateOperatorArray[] = array( 'script' => 'extension/web/autoloads/webtagcloud.php',
                                    'class' => 'webTagCloud',
                                    'operator_names' => array( 'webtagcloud' ) );

$eZTemplateOperatorArray[] = array( 'script' => 'extension/web/autoloads/webyoutube.php',
                                    'class' => 'webYouTube',
                                    'operator_names' => array( 'webyoutube', 'webyoutube_preview' ) );

$eZTemplateOperatorArray[] = array( 'script' => 'extension/web/autoloads/webvimeo.php',
                                    'class' => 'webVimeo',
                                    'operator_names' => array( 'webvimeo', 'webvimeo_preview' ) );
?>