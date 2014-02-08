<?php

/**
 * Colección de funciones para obtener datos de disqus
 *
 * @author crevillo@gmail.com
 */
class disqusFunctionCollection
{
    function __construct()
    {
        
    }

    static function getLatestComments( $limit = 10 )
    {
        $disqus = new DisqusAPI(
            'eJhtyOSua2YsOqfUoGV7OuBv4M4UvEdtZwEpnxdj3TEna5CCdM4o7dRfrFLRjHd1'
        );
        $result = $disqus->forums->listPosts(
            array( 
                'forum' => 'sillonbolcom',
                'limit' => $limit,
                'version' => '3.0'
            )
        );

        $comments = array();
        foreach( $result as $item )
        {
            $thread = $disqus->threads->details(
                array( 'thread' => $item->thread )
            );

            $comments[] = array(
                'id' => $item->id,
                'autor' => $item->author->name,
                'message' => $item->raw_message,
                'avatar' => $item->author->avatar->small->cache,
                'article_title' => $thread->title,
                'article_link' => $thread->link
            );
        }

        return array( 'result' => $comments );
    }
    
    var $secret_key;
}

?>
