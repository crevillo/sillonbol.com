<?php
class webInputParser extends eZOEInputParser
{
    public $InputTags = array(
        'section' => array( 'name' => 'section' ),
        'b'       => array( 'name' => 'strong' ),
        'bold'    => array( 'name' => 'strong' ),
        'strong'  => array( 'name' => 'strong' ),
        'i'       => array( 'name' => 'emphasize' ),
        'em'      => array( 'name' => 'emphasize' ),
        'pre'     => array( 'name' => 'literal' ),
        'div'     => array( 'nameHandler' => 'tagNameDivnImg' ),
        'u'       => array( 'nameHandler' => 'tagNameCustomHelper' ),
        'sub'       => array( 'nameHandler' => 'tagNameCustomHelper' ),
        'sup'       => array( 'nameHandler' => 'tagNameCustomHelper' ),
        'img'     => array( 'nameHandler' => 'tagNameDivnImg',
                            'noChildren' => true ),
        'h1'      => array( 'nameHandler' => 'tagNameHeader' ),
        'h2'      => array( 'nameHandler' => 'tagNameHeader' ),
        'h3'      => array( 'nameHandler' => 'tagNameHeader' ),
        'h4'      => array( 'nameHandler' => 'tagNameHeader' ),
        'h5'      => array( 'nameHandler' => 'tagNameHeader' ),
        'h6'      => array( 'nameHandler' => 'tagNameHeader' ),
        'p'       => array( 'name' => 'paragraph' ),
        'br'      => array( 'name' => 'br',
                            'noChildren' => true ),
        'span'    => array( 'nameHandler' => 'tagNameSpan' ),
        'table'   => array( 'nameHandler' => 'tagNameTable' ),
        'td'      => array( 'name' => 'td' ),
        'tr'      => array( 'name' => 'tr' ),
        'th'      => array( 'name' => 'th' ),
        'ol'      => array( 'name' => 'ol' ),
        'ul'      => array( 'name' => 'ul' ),
        'li'      => array( 'name' => 'li' ),
        'a'       => array( 'nameHandler' => 'tagNameLink' ),
        'link'    => array( 'nameHandler' => 'tagNameLink' ),
        'iframe'  => array( 'nameHandler' => 'tagNameIframe' ),
       // Stubs for not supported tags.
        'tbody'   => array( 'name' => '' ),
        'thead'   => array( 'name' => '' ),
        'tfoot'   => array( 'name' => '' )
    );

    function tagNameIframe( $tagName, &$attributes )
    {
        $name = '';
        if( preg_match( "/youtube/", $attributes['src'] ) )
        {
            $name = 'custom';
            $codeArray = explode( 'embed/', $attributes['src'] );
            $attributes['name'] = 'youtube';
            $attributes['custom:codigo'] = $codeArray[1];
            $attributes['custom:ancho'] = $attributes['width'];
            $attributes['custom:alto'] = $attributes['height'];
            unset( $attributes['src'] );
            unset( $attributes['width'] );
            unset( $attributes['height'] );
        }
        return $name;
    }

    function tagNameDivnImg( $tagName, &$attributes )
    {
        $name = '';
        if ( isset( $attributes['id'] ) )
        {
            if ( strpos( $attributes['id'], 'eZObject_' ) !== false
              || strpos( $attributes['id'], 'eZNode_' ) !== false )
            {
                // decide if inline or block embed tag
                if ( isset( $attributes['inline'] ) && $attributes['inline'] === 'true' )
                    $name = 'embed-inline';
                else
                    $name = 'embed';

                unset( $attributes['inline'] );// unset internal stuff to make sure custom attr with same name works

                if ( isset( $attributes['class'] ) )
                {
                    $attributes['class'] = self::tagClassNamesCleanup( $attributes['class'] );
                }
            }
        }

        if ( $name === '' && isset( $attributes['type'] ) && $attributes['type'] === 'custom' )
        {
            $name = 'custom';
            unset( $attributes['type'] );// unset internal stuff to make sure custom attr with same name works
            if ( $tagName === 'div' )
                $attributes['children_required'] = 'true';
            $attributes['name'] = self::tagClassNamesCleanup( $attributes['class'] );
            unset( $attributes['class'] );// unset internal stuff to make sure custom attr with same name works
        }
        
        if( $tagName === 'div' )
            $name = '';
        
        if ( $tagName == 'img' )
        {
            if ( preg_match( "/^(http|https|ftp):\/\//", $attributes['src'] ) )
            {
                if ( !preg_match( "/sillonbol\.com/", $attributes['src'] ) )
                {
                    $name = 'custom';
                    $attributes['name'] = 'externalimg';
                    $attributes['custom:url'] = $attributes['src'];
                    $attributes['custom:pie'] = '';
                    $attributes['custom:align'] = 'center';
                    unset( $attributes['src'] );
                    unset( $attributes['width'] );
                }
            }
        }

        return $name;
    }
}