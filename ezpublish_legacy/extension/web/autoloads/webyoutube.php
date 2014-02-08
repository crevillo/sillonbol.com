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

class webYouTube
{
    function __construct()
    {
    }

    function operatorList()
    {
        return array( 'webyoutube', 'webyoutube_preview' );
    }

    function namedParameterPerOperator()
    {
        return true;
    }

    function namedParameterList()
    {
        return array( 'webyoutube' => array(), 'webyoutube_preview' => array() );
    }

    function modify( $tpl, $operatorName, $operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        switch ( $operatorName )
        {
            case 'webyoutube':
            {                  
                $tpl = eZTemplate::factory();
                $tpl->setVariable( 'code', $operatorValue );
                $operatorValue = $tpl->fetch( 'design:videoplayers/youtube.tpl' );                
            } break;            
            case 'webyoutube_preview':
            {
                $newIncludePath = array();
                $newIncludePath[] = '.';
                $newIncludePath[] = 'extension/web/classes';
                $newIncludePath[] = get_include_path();
                $newIncludePath = implode( PATH_SEPARATOR, $newIncludePath );
                set_include_path( $newIncludePath );
                $yt = new Zend_Gdata_YouTube();
                try
                {
                    $video = $yt->getVideoEntry( $operatorValue->attribute( 'content' ) );
                    $thumbs = $video->getVideoThumbnails();
                    $url = $thumbs[0]['url'];
                    $varDir = eZINI::instance()->variable( 'FileSettings', 'VarDir' );
                    $previewPath = $varDir . '/storage/youtube/thumb_' . $operatorValue->attribute( 'id' ) . '_' .  $operatorValue->attribute( 'version' ) . '_list.jpg';
                    $handler = eZClusterFileHandler::instance( $previewPath );
                    $tpl = eZTemplate::factory();
                    if( !$handler->fileExists( $previewPath ) )
                    {
                        $buf = eZHTTPTool::sendHTTPRequest( $url, 80, false, 'eZ Publish', false );
                        
                        $header = false;
                        $imagebody = false;
                        if ( eZHTTPTool::parseHTTPResponse( $buf, $header, $imagebody ) )
                        {
                            $savedImgPath = $varDir . '/storage/original/youtube/thumb_' . $operatorValue->attribute( 'id' ) . '_' .  $operatorValue->attribute( 'version' ) . '.jpg';
                            $handler = eZClusterFileHandler::instance( $savedImgPath );
                            $handler->storeContents( $imagebody, 'image', $header['content-type'] );
                        }                        
                        $img = eZImageManager::instance();
                        $img->readINISettings();
                        $img->convert( $savedImgPath, $previewPath, "list" );
                        $tpl->setVariable( 'img', $previewPath['url'] );
                    }
                    else
                    {
                        $tpl->setVariable( 'img', $previewPath );
                    }               
                    
                   
                }
                catch( Exception $e )
                {
                    $varDir = eZINI::instance()->variable( 'FileSettings', 'VarDir' );
                    $previewPath = $varDir . '/storage/youtube/thumb_' . $operatorValue->attribute( 'id' ) . '_' .  $operatorValue->attribute( 'version' ) . '_list.gif';
                    $handler = eZClusterFileHandler::instance( $previewPath );
                    $tpl = eZTemplate::factory();
                    if( !$handler->fileExists( $previewPath ) )
                    {
                        $contents = eZClusterFileHandler::instance( 'extension/web/design/www/images/imagen-no-disponible.gif' )->fetchContents();                
                        $handler = eZClusterFileHandler::instance( $previewPath );
                        $handler->storeContents( $contents, 'image', 'image/gif' );
                        $tpl->setVariable( 'img', $previewPath );
                    }
                    else
                    {
                        $tpl->setVariable( 'img', $previewPath );
                    } 
                }
                $operatorValue  = $tpl->fetch( 'design:previews/youtube.tpl' );
                
                
            }
        }
    }
}

?>
