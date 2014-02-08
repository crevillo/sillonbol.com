<?php

$FunctionList = array();

$FunctionList['get_latest_comments'] = array(
    'name' => 'get_latest_comments',
    'operation_types' => array( 'read' ),
    'call_method' => array(
        'class' => 'disqusFunctionCollection',
        'method' => 'getLatestComments'
    ),
    'parameter_type' => 'standard',
    'parameters' => array( 
        array(
            'name' => 'limit',
            'type' => 'integer',
            'required' => false
        )
    )
);

?>