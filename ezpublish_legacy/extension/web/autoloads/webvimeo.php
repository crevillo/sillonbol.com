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

class webVimeo
{
    function __construct()
    {
    }

    function operatorList()
    {
        return array( 'webvimeo', 'webvimeo_preview' );
    }

    function namedParameterPerOperator()
    {
        return true;
    }

    function namedParameterList()
    {
        return array( 'webvimeo' => array( ), 'webvimeo_preview' => array() );
    }

    function modify( $tpl, $operatorName, $operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        switch ( $operatorName )
        {
            case 'webvimeo':
            {   
                $tpl = eZTemplate::factory();
                $tpl->setVariable( 'code', $operatorValue );
                $operatorValue = $tpl->fetch( 'design:videoplayers/vimeo.tpl' );                
            } break;
            case 'webvimeo_preview':
            {   
                $doc = simplexml_load_file( 'http://vimeo.com/api/v2/video/' . $operatorValue->attribute( 'content' ) . '.xml' );
                $url = (string)$doc->video->thumbnail_medium;
                $varDir = eZINI::instance()->variable( 'FileSettings', 'VarDir' );
                $previewPath = $varDir . '/storage/vimeo/thumb_' . $operatorValue->attribute( 'id' ) . '_' .  $operatorValue->attribute( 'version' ) . '_list.jpg';
                $handler = eZClusterFileHandler::instance( $previewPath );
                $tpl = eZTemplate::factory();
                if( !$handler->fileExists( $previewPath ) )
                {
                    $buf = eZHTTPTool::sendHTTPRequest( $url, 80, false, 'eZ Publish', false );
                    $header = false;
                    $imagebody = false;

                    if ( eZHTTPTool::parseHTTPResponse( $buf, $header, $imagebody ) )
                    {
                        $handler = eZClusterFileHandler::instance( $previewPath );
                        $handler->storeContents( $imagebody, 'image', $header['content-type'] );
                        $tpl->setVariable( 'img', $previewPath );
                    }                        
                }
                else
                {
                    $tpl->setVariable( 'img', $previewPath );
                }

                $operatorValue = $tpl->fetch( 'design:previews/vimeo.tpl' );
                         
            } break;            
        }
    }
}

?>
