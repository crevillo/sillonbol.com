<?php
/**
 * Vista rss
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package web
 * @subpackage feeds
 */

/**
 * Recoge el parámentro de la url, calculará el nodo asociado
 * y formará el rss
 */

// cache de 4 horas;
$options = array(
    'ttl' => 60*60*4,
);

// comprobar si la carpeta caché existe y si no crearla
if( !file_exists( 'var/cache/feeds' ) )
    eZDir::mkdir( 'var/cache/feeds' );

ezcCacheManager::createCache(
    'feeds',
    'var/cache/feeds/',
    'ezcCacheStorageFilePlain',
    $options 
);

$cache = ezcCacheManager::getCache( 'feeds' );

$url = str_replace( '-', '/', $Params['Feed'] );

$element = $url != '' ? eZContentObjectTreeNode::fetchByURLPath( $url ) : 
                        eZContentObjectTreeNode::fetch( 2 );

if ( $element instanceof eZContentObjectTreeNode ) 
{
    $nodeID = $element->attribute( 'node_id' );
    $myId = 'feeds' . $nodeID;
    $name = $element->attribute( 'name' );

    $solr = new eZSolr();
    $results = $solr->search(
        '',
        array( 
            'SearchSubTreeArray' => array( $nodeID ),
            'SearchLimit' => 10,
            'SearchContentClassID' => array( 16 ),
            'SortBy' => array( 'published' => 'desc' )
        )
    );
}

if ( ( $xml = $cache->restore( $myId ) ) === false )
{
    $nodes = $results['SearchResult'];

    $feed = new FeedWriter(
        $name,    //Feed Title
        $element->attribute( 'name' ) . ' en Sillonbol.com',
        'http://www.sillonbol.com/' . $url, //Feed Link
        6, //indent
        true, //Use CDATA
        null, //encoding
        false //enable validation
    );

    $feed->debug = true;
    $format = RSS_2_0;

    $feed->set_image(
        'Sillonbol.com',
        'http://www.sillonbol.com/',
        'http://www.sillonbol.com/extension/web/design/www/images/logo.png'
    );

    $feed->set_language( 'es-es' );
    $feed->set_date( date( DATE_RSS, time() ),DATE_UPDATED );  

    foreach ( $nodes as $node )
    {
        $data = $node->dataMap();
        //sacamos el título
        $title = $node->attribute( 'name' );

        $description = '';
        if ( $data['image']->hasContent() )
        {
            $img = $data['image']->content();
            $imgAlias = $img->imageAlias( 'article' );
            $imgPath = $imgAlias['url'];
            $description .= '<p><img src="http://' . $_SERVER["HTTP_HOST"]. "/" . $imgPath . '" /></p>';

            if ( $data['short_title'] )
                $description .= '<p>' . $data['short_title']->content() . '</p>';

           $description .= $data['body']->content()
                ->attribute( 'output' )
                ->attribute( 'output_text' );
        }

        $link = "http://" . $_SERVER["HTTP_HOST"]. "/" . $node->urlAlias(); 

        $feed->add_item( $title, $description, $link );
        $feed->set_date( date( DATE_RSS, $node->attribute( 'object')->attribute( 'modified' ) ), DATE_UPDATED);
        $feed->set_date( date( DATE_RSS, $node->attribute( 'object')->attribute( 'published' ) ), DATE_PUBLISHED);
    }

    $feed->set_feedConstruct( $format );
    $feed->feed_construct->construct['itemContent']['type'] = 'html';
    $xml = $feed->getXML( $format );
    $cache->store( $myId, $xml );
}


header( 'Content-Type: text/xml; charset=utf-8' );
header( 'Content-Length: '. strlen( $xml ) );

print $xml;
eZExecution::cleanExit( );
?>
