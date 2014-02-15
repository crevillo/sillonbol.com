<?php

/**
 * File containing the SearchController class
 *
 * @author carlos <crevillo@gmail.com>
 */

namespace Sillonbol\WebBundle\Controller;

use eZ\Bundle\EzPublishCoreBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sillonbol\WebBundle\Pagination\Pagerfanta\SillonbolSearchAdapter;
use Pagerfanta\Pagerfanta;

class SearchController extends Controller
{
    /**
     * Display search results for a given term
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $response = new Response();

        // Setting default cache configuration (you can override it in you siteaccess config)
        $response->setSharedMaxAge( $this->getConfigResolver()->getParameter( 'content.default_ttl' ) );

        $request = $this->getRequest();
        $searchText = $request->get( 'SearchText' );

        // Initialize pagination.
        $pager = new Pagerfanta(
            new SillonbolSearchAdapter(
                $this->getLegacyKernel(),
                $searchText,
                $this->getRepository()->getContentService()
            )
        );
        $pager->setMaxPerPage( $this->container->getParameter( 'sillonbol.category.category_list.limit' ) );
        $pager->setCurrentPage( $request->get( 'page', 1 ) );

        return $this->render(
            'SillonbolWebBundle:search:results.html.twig',
            array(
                'pagerBlog' => $pager,
                'searchPhrase' => $searchText,
            ),
            $response
        );
    }
}
