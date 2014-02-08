<?php

class moveUtils 
{
    function __construct()
    {
    }

    static function moveSubTree( $nid, $newParentLocation )
    {
         eZContentObjectTreeNodeOperations::move( $nid, $newParentLocation );
    }
}

