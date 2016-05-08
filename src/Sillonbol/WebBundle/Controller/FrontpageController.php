<?php

/**
 * File containing the FrontpageController class
 *
 * @author carlos <crevillo@gmail.com>
 */

namespace Sillonbol\WebBundle\Controller;

use eZ\Bundle\EzPublishLegacyBundle\Controller;
use Symfony\Component\HttpFoundation\Response;

class FrontpageController extends Controller
{
    /**
     * Renders the main block in the home
     * Will be the last three published articles
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mainBlockAction()
    {
        $rootLocationId = $this->getConfigResolver()->getParameter('content.tree_root.location_id');

        // Setting HTTP cache for the response to be public and with a TTL of 1 day.
        $response = new Response;
        $response->setPublic();
        $response->setSharedMaxAge(86400);
        // Menu will expire when top location cache expires.
        $response->headers->set('X-Location-Id', $rootLocationId);

        // Retrieve latest content through the ContentHelper.
        // We only want articles that are located somewhere in the tree under root location.
        $latestContent = $this->get('sillonbol.content_helper')->getLatestContent();

        $locationList = array();
        // Looping against search results to build $locationList
        // Both arrays will be indexed by contentId so that we can easily refer to an element in a list from another element in the other list
        foreach ($latestContent as $contentId => $content) {
            $locationList[$contentId] = $this->getRepository()->getLocationService()->loadLocation($content->contentInfo->mainLocationId);
        }

        return $this->render(
            ':frontpage:mainblock.html.twig',
            array(
                'latest_content' => $latestContent,
                'locationList' => $locationList
            ),
            $response
        );
    }

    public function secondaryBlockAction()
    {
        $rootLocationId = $this->getConfigResolver()->getParameter('content.tree_root.location_id');

        // Setting HTTP cache for the response to be public and with a TTL of 1 day.
        $response = new Response;
        $response->setPublic();
        $response->setSharedMaxAge(86400);
        // Menu will expire when top location cache expires.
        $response->headers->set('X-Location-Id', $rootLocationId);

        // Retrieve latest content through the ContentHelper.
        // We only want articles that are located somewhere in the tree under root location.
        $latestContent = $this->get('sillonbol.content_helper')->getLatestContent(3, 4);

        $locationList = array();
        // Looping against search results to build $locationList
        // Both arrays will be indexed by contentId so that we can easily refer to an element in a list from another element in the other list
        foreach ($latestContent as $contentId => $content) {
            $locationList[$contentId] = $this->getRepository()->getLocationService()->loadLocation($content->contentInfo->mainLocationId);
        }

        return $this->render(
            ':frontpage:secondary.html.twig',
            array(
                'latest_content' => $latestContent,
                'locationList' => $locationList
            ),
            $response
        );
    }

    public function commentsBlockAction($limit = 6)
    {
        $response = new Response;
        $response->setPublic();
        $response->setSharedMaxAge(3600);

        $comments = $this->getLegacyKernel()->runCallback(
            function () use ($limit) {
                return \disqusFunctionCollection::getLatestComments($limit);
            },
            false
        );
        return $this->render(
            ':frontpage:disquscomments.html.twig',
            array(
                'comments' => $comments
            ),
            $response
        );
    }

    public function tagsBlockAction()
    {
        $response = new Response;
        $response->setPublic();
        $response->setSharedMaxAge(86400);

        $tags = $this->getLegacyKernel()->runCallback(
            function () {
                $solr = new \eZSolr();
                $rs = $solr->search('',
                    array(
                        'Facet' => array(
                            array(
                                'field' => 'attr_tags_lk',
                                'sort' => 'count',
                                'limit' => 0
                            )
                        ),
                        'SearchContentClassID' => array('article', 'blog_post'),
                        'SearchLimit' => 1

                    )
                );

                $facets = $rs['SearchExtras']->attribute('facet_fields');

                $tags = $facets[0]['countList'];

                foreach ($tags as $key => $value) {
                    $size = $minFontSize + (($value - $minCount) * $step);
                    $tagCloud[] = array(
                        'font_size' => $size,
                        'count' => $value,
                        'tag' => $key
                    );
                }
                return $tagCloud;
            },
            false
        );

        return $this->render(
            ':frontpage:tagcloud.html.twig',
            array(
                'tags' => $tags
            ),
            $response
        );
    }
}
