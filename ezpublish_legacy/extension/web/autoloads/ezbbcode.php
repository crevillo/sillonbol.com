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

class eZBBCode
{
    function __construct()
    {
    }

    function operatorList()
    {
        return array( 'bbcodetohtml' );
    }

    function namedParameterPerOperator()
    {
        return true;
    }

    function namedParameterList()
    {
        return array( 'bbcodetohtml' => array( ) );
    }

    function modify( $tpl, $operatorName, $operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        switch ( $operatorName )
        {
            case 'bbcodetohtml':
            {       
                // ezpublish has converted the output to html, and so some html tags
                // probably have been added. so we need to parse to bbcode.
                $operatorValue = $this->html2bbcode( $operatorValue );
                $new_rule = Array(
                    'simple_start' => '<p>',
                    'simple_end' => '</p>',
                );
                $div_rule = Array(
                    'simple_start' => '<div>',
                    'simple_end' => '</div>',
                );
                
                $bbcode = new BBCode;
                $bbcode->setIgnoreNewLines( 'true' );
                $bbcode->AddRule('youtube',  array(
                    'mode' => BBCODE_MODE_ENHANCED,
                    'template' => '<div class="wysiwyg-youtube"><iframe width="560" height="315" src="http://www.youtube.com/embed/{$_content}" frameborder="0"></iframe></div>'
                    )
                );
                $bbcode->AddRule('img',  array(
                    'mode' => BBCODE_MODE_ENHANCED,
                    'template' => '<div class="wysiwyg-img"><img src="{$_content}" /></div>'
                    )
                );
                $bbcode->AddRule('ima',  array(
                    'mode' => BBCODE_MODE_ENHANCED,
                    'template' => '<div class="wysiwyg-img"><img src="{$_content}" /></div>'
                    )
                );
                $bbcode->AddRule('p', $new_rule);
                $bbcode->AddRule('capa',  array(
                    'mode' => BBCODE_MODE_ENHANCED,
                    'template' => '<div>{$_content}</div>'
                    )
                );
                $operatorValue = $bbcode->parse( $operatorValue );
               
       
            } break;
        }
    }

    function html2bbcode($text) {
    $htmltags = array(
        '/\<p\>(.*?)\<\/p\>/is',
        '/\<b\>(.*?)\<\/b\>/is',
        '/\<i\>(.*?)\<\/i\>/is',
        '/\<u\>(.*?)\<\/u\>/is',
        '/\<ul.*?\>(.*?)\<\/ul\>/is',
        '/\<li\>(.*?)\<\/li\>/is',
        '/\<img(.*?) src=\"(.*?)\" alt=\"(.*?)\" title=\"Smile(y?)\" \/\>/is',        // some smiley
        '/\<img(.*?) src=\"http:\/\/(.*?)\" (.*?)\>/is',
        '/\<img(.*?) src=\"(.*?)\" alt=\":(.*?)\" .*? \/\>/is',                       // some smiley
        '/\<div class=\"quotecontent\"\>(.*?)\<\/div\>/is',
        '/\<div class=\"codecontent\"\>(.*?)\<\/div\>/is',
        '/\<div class=\"quotetitle\"\>(.*?)\<\/div\>/is',
        '/\<div class=\"codetitle\"\>(.*?)\<\/div\>/is',
        '/\<cite.*?\>(.*?)\<\/cite\>/is',
        '/\<blockquote.*?\>(.*?)\<\/blockquote\>/is',
        '/\<div\>(.*?)\<\/div\>/is',
        '/\<code\>(.*?)\<\/code\>/is',
        '/\<br(.*?)\>/is',
        '/\<strong\>(.*?)\<\/strong\>/is',
        '/\<em\>(.*?)\<\/em\>/is',
        '/\<a href=\"mailto:(.*?)\"(.*?)\>(.*?)\<\/a\>/is',
        '/\<a .*?href=\"(.*?)\"(.*?)\>http:\/\/(.*?)\<\/a\>/is',
        '/\<a .*?href=\"(.*?)\"(.*?)\>(.*?)\<\/a\>/is',
        '/\<img(.*?) src=\"(.*?)\" alt=\"(.*?)\" \/\>/is',
        '/\<div class=\"(.*?)\"\>(.*?)\<\/div\>/is'
    );
 
    $bbtags = array(
        '[p]$1[/p]',        
        '[b]$1[/b]',
        '[i]$1[/i]',
        '[u]$1[/u]',
        '[list]$1[/list]',
        '[*]$1',
        '$3',
        '[img]http://$2[/img]',
        ':$3',
        '\[quote\]$1\[/quote\]',
        '\[code\]$1\[/code\]',
        '',
        '',
        '',
        '\[quote\]$1\[/quote\]',
        '\[diveee\]$1\[/diveee\]',
        '\[code\]$1\[/code\]',
        "\n",
        '[b]$1[/b]',
        '[i]$1[/i]',
        '[email=$1]$3[/email]',
        '[url]$1[/url]',
        '[url=$1]$3[/url]',
        '[img alt="$3"]$2[/img]',
        '[capa class="$1"]$2[/capa]'
    );
 
    $text = str_replace ("\n", ' ', $text);
    $ntext = preg_replace ($htmltags, $bbtags, $text);
    $ntext = preg_replace ($htmltags, $bbtags, $ntext);
 
    // for too large text and cannot handle by str_replace
    if (!$ntext) {
        $ntext = str_replace(array('<br>', '<br />'), "\n", $text);
        $ntext = str_replace(array('<strong>', '</strong>'), array('[b]', '[/b]'), $ntext);
        $ntext = str_replace(array('<em>', '</em>'), array('[i]', '[/i]'), $ntext);
    }
 
    $ntext = strip_tags($ntext);
    $ntext = trim(html_entity_decode($ntext,ENT_QUOTES,'UTF-8'));
    return $ntext;
}
}

?>
