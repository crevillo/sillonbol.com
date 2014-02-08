<?php

/**
 * File containing the FeedController class
 *
 * @author carlos <crevillo@gmail.com>
 */

namespace Sillonbol\WebBundle\Controller;

use eZ\Bundle\EzPublishCoreBundle\Controller;
use Symfony\Component\HttpFoundation\Response;

class FeedController extends Controller
{
    public function indexAction()
    {
        $rootLocationId = $this->getConfigResolver()->getParameter( 'content.tree_root.location_id' );

        // Setting HTTP cache for the response to be public and with a TTL of 1 day.
        $response = new Response;
        $response->setPublic();
        $response->setSharedMaxAge( 7200 );
        // Menu will expire when top location cache expires.
        $response->headers->set( 'X-Location-Id', $rootLocationId );

        // Retrieve latest content through the ContentHelper.
        // We only want articles that are located somewhere in the tree under root location.
        // We only need last 10.
        $latestContent = $this->get( 'sillonbol.content_helper' )->getLatestContent( 0, 10 );
        
        $locationList = array();
        // Looping against search results to build $locationList
        // Both arrays will be indexed by contentId so that we can easily refer to an element in a list from another element in the other list
        foreach ( $latestContent as $contentId => $content )
        {
            $locationList[$contentId] = $this->getRepository()->getLocationService()->loadLocation( $content->contentInfo->mainLocationId );
        }

        $d = new \DateTime('NOW');

        return $this->render(
            'SillonbolWebBundle:rss:feed.html.twig',
            array(
                'latest_content' => $latestContent,
                'locationList' => $locationList,
                'last_building_date' => $d->format(\DateTime::RFC822)
            ),
            $response
        );
    }
}
