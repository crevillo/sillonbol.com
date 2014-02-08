<?php

class tweetsFunctionCollection {
    
    var $wrapper;

    function __construct()
    {
    }

    function getTweets( $limit = 6 )
    {
        $twitter = new Twitter(
            'YMB1DAZ3vyzjIcG4OpvWog', 
            'PCBmtfqLfj7X0DLKsJpaSEbAfSO6pq0cRBXJoSIzlBs'
        );
        $twitter->setOAuthToken('16978640-MSXRph5C3Yz0yFM1eCj0hbMdH4PqNeDTpLyzZrVmo');
        $twitter->setOAuthTokenSecret('WsS0fcEcSm0v0krtZlLiKjDMksICvYIKJdeEHGsSM0');
        return array(
            'result' =>  $twitter->statusesUserTimeline( null, 'Sillonbolcom', null, null, $limit, null, false, true ) );
    }
}

?>
