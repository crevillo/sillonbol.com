{ezscript_require( array( 'ezjsc::jquery', 'ezjsc::jqueryio', 'block/tweets/jquery.timeago.js', 'block/tweets/jquery.timeago.es.js', 'block/tweets/sb.tweets.js') )} 
<div class="wrapper indent-bot">
                                                        <div class="bg-white">
                                                            <div class="padding-1">
                                                                <div class="wrapper">
                                                                    <h2 class="color-3 text-shadow">En Twitter...</h2>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="wrapper">
{def $tweets = fetch( 'tweets', 'get_tweets', hash( 'limit', 6 ) ))}

{foreach $tweets as $index => $tweet}

    <div class="tweet clearfix{if eq($index, $tweets|count|sub(1))} last{/if}">
       <div class="image"><img src="{$tweet.user.profile_image_url}" /></div>
       <div class="tweet-content">
            <p><strong>{$tweet.user.name}</strong> <span class="screen_name">@{$tweet.user.screen_name}</span></p>
            <p>{$tweet.text|parsetext()}</p>
            <p class="links">
                <a href="http://twitter.com/Sillonbolcom/status/{$tweet.id_str}"><abbr class="timeago" title="{$tweet.created_at|strtotime()|datetime( 'custom', '%Y-%m-%dT%H:%i:%s+01:00' )}">{$tweet.created_at|parsetime}</abbr></a> ·
                <a href="http://twitter.com/intent/tweet?in_reply_to={$tweet.id_str}">responder</a> ·
                <a href="http://twitter.com/intent/retweet?tweet_id={$tweet.id_str}">retwitear</a> ·
                <a href="http://twitter.com/intent/favorite?tweet_id={$tweet.id_str}">favorito</a>
            </p>
       </div>
    </div>
{/foreach}
                                                        
                                                    </div>
