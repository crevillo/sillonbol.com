<?php

$FunctionList = array();

$FunctionList['get_tweets'] = array( 'name'            => 'get_tweets',
                              'operation_types' => array( 'read' ),
                              'call_method'     => array( 'class'  => 'tweetsFunctionCollection',
                                                          'method' => 'getTweets' ),
                              'parameter_type'  => 'standard',
                              'parameters'      => array( array( 'name'     => 'limit',
                                                                             'type'     => 'integer',
                                                                             'required' => false ) ) );

?>
