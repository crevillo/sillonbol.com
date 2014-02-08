<?php
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish Website Interface
// SOFTWARE RELEASE: 1.9.0
// COPYRIGHT NOTICE: Copyright (C) 1999-2011 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//  This program is free software; you can redistribute it and/or
//  modify it under the terms of version 2.0  of the GNU General
//  Public License as published by the Free Software Foundation.
//
//  This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of version 2.0 of the GNU General
//  Public License along with this program; if not, write to the Free
//  Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//  MA 02110-1301, USA.
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

class webTwitter
{
    function __construct()
    {
    }

    function operatorList()
    {
        return array( 'parsetext', 'parsetime' );
    }

    function namedParameterPerOperator()
    {
        return true;
    }

    function namedParameterList()
    {
        return array( 'parsetext' => array( ), 'parsetime' => array( ) );
    }

    function modify( $tpl, $operatorName, $operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        switch ( $operatorName )
        {
            case 'parsetext':
            {       
               
               $data = Twitter_Autolink::create($operatorValue)
  ->setNoFollow(false)
  ->addLinks();
                $operatorValue = $data;
       
            } break;

            case 'parsetime':
            {
                $operatorValue = $this->TimeSince( $operatorValue );
            }
        }
    }

    public function Timesince($original) {
    // array of time period chunks
    $chunks = array(
    array(60 * 60 * 24 * 365 , 'año'),
    array(60 * 60 * 24 * 30 , 'mes'),
    array(60 * 60 * 24 * 7, 'sem'),
    array(60 * 60 * 24 , 'día'),
    array(60 * 60 , 'hora'),
    array(60 , 'min'),
    array(1 , 'seg'),
    );
 
    $today = time(); /* Current unix time  */
    $since = $today - strtotime( $original );
 
    // $j saves performing the count function each time around the loop
    for ($i = 0, $j = count($chunks); $i < $j; $i++) {
 
    $seconds = $chunks[$i][0];
    $name = $chunks[$i][1];
 
    // finding the biggest chunk (if the chunk fits, break)
    if (($count = floor($since / $seconds)) != 0) {
        break;
    }
    }
 
    $print = ($count == 1) ? '1 '.$name : "$count {$name}s";
 
    if ($i + 1 < $j) {
    // now getting the second item
    $seconds2 = $chunks[$i + 1][0];
    $name2 = $chunks[$i + 1][1];
 
    // add second item if its greater than 0
    if (($count2 = floor(($since - ($seconds * $count)) / $seconds2)) != 0) {
        $print .= ($count2 == 1) ? ', 1 '.$name2 : " $count2 {$name2}s";
    }
    }
    return 'hace ' . $print;
}

    
}

?>    

