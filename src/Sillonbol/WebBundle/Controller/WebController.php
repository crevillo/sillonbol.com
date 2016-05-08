<?php

/**
 * File containing the WebController class
 *
 * @author carlos <crevillo@gmail.com>
 */

namespace Sillonbol\WebBundle\Controller;

use eZ\Bundle\EzPublishCoreBundle\Controller;
use eZ\Publish\Core\MVC\Symfony\View\ContentView;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use eZ\Publish\Core\Pagination\Pagerfanta\ContentSearchAdapter;
use Pagerfanta\Pagerfanta;
use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use eZ\Publish\API\Repository\Values\Content\Query\SortClause;

class WebController extends Controller
{

    /**
     * Renders the social links menu, with cache control
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function socialLinksAction()
    {
        $rootLocationId = $this->getConfigResolver()->getParameter('content.tree_root.location_id');

        // Setting HTTP cache for the response to be public and with a TTL of 1 day.
        $response = new Response;
        $response->setPublic();
        $response->setSharedMaxAge(86400);
        // Menu will expire when top location cache expires.
        $response->headers->set('X-Location-Id', $rootLocationId);

        $fb_url = $this->container->getParameter('sillonbol.sociallinks.facebook');
        $tw_url = $this->container->getParameter('sillonbol.sociallinks.twitter');
        $about_us = $this->getRepository()->getLocationService()->loadLocation(
            $this->container->getParameter('sillonbol.sociallinks.about_us.location_id')
        );
        $contact = $this->getRepository()->getLocationService()->loadLocation(
            $this->container->getParameter('sillonbol.sociallinks.contact.location_id')
        );

        return $this->render(
            'parts\\social.html.twig', array(
            'fb_url' => $fb_url,
            'tw_url' => $tw_url,
            'about_us' => $about_us,
            'contact' => $contact
        ), $response
        );
    }

    /**
     * Renders the main menu, with cache control
     *
     * @param int selected id of the location marked as active in the menu
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mainMenuAction($selected = 0)
    {
        $rootLocationId = $this->getConfigResolver()->getParameter('content.tree_root.location_id');

        // Setting HTTP cache for the response to be public and with a TTL of 1 day.
        $response = new Response;
        $response->setPublic();
        $response->setSharedMaxAge(86400);
        // Menu will expire when top location cache expires.
        $response->headers->set('X-Location-Id', $rootLocationId);

        $includeCriterion = $this->get('sillonbol.criteria_helper')
            ->generateContentTypeIncludeCriterion(
            // Get contentType identifiers we want to include from configuration (see default_settings.yml).
                $this->container->getParameter('sillonbol.top_menu.content_types_include')
            );

        $locationList = $this->get('sillonbol.menu_helper')->getTopMenuContent(
            $rootLocationId, $includeCriterion
        );

        return $this->render(
            'parts\\mainmenu.html.twig',
            array(
                'locationList' => $locationList,
                'selected' => $selected
            ),
            $response
        );
    }

    /**
     * Renders article with extra parameters that controls page elements visibility such as image and summary
     *
     * @param ContentView $view
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showArticleAction(ContentView $view)
    {
        $location = $view->getLocation();
        $viewType = $view->getViewType();
        $path_array = explode('/', $location->pathString);
        $category = $this->getRepository()->getLocationService()->loadLocation($path_array[3]);

        // set some template variables depending on the viewType
        $twigVars = array('category' => $category);

        if ($viewType == 'rss') {
            $twigVars['published_rcf822'] = $location->getContentInfo()->publishedDate->format(\DateTime::RFC822);
        }

        if (($viewType == 'search') && !empty($params['highlight'])) {
            $twigVars['highlight'] = $params['highlight'];
        }

        $view->addParameters($twigVars);

        return $view;
    }

    /**
     * Displays the list of category post
     * Note: This is a fully customized controller action, it will generate the response and call
     *       the view. Since it is not calling the ViewControler we don't need to match a specific
     *       method signature.
     *
     * @param int $locationId of a category
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function categoryListAction(ContentView $view)
    {
        $response = new Response();
        $location = $view->getLocation();

        // Setting default cache configuration (you can override it in you siteaccess config)
        $response->setSharedMaxAge($this->getConfigResolver()->getParameter('content.default_ttl'));

        // Make the response location cache aware for the reverse proxy
        $response->headers->set('X-Location-Id', $location->id);

        // Getting location and content from ezpublish dedicated services
        if ($location->invisible) {
            throw new NotFoundHttpException("Location #$location->id cannot be displayed as it is flagged as invisible.");
        }

        // Using the criteria helper (a demobundle custom service) to generate our query's criteria.
        // This is a good practice in order to have less code in your controller.
        $criteria = array();
        $criteria[] = new Criterion\Visibility(Criterion\Visibility::VISIBLE);
        $criteria[] = new Criterion\Subtree($location->pathString);
        $criteria[] = new Criterion\ContentTypeIdentifier(array('article', 'blog_post'));

        // Generating query
        $query = new Query();
        $query->query = new Criterion\LogicalAnd($criteria);
        $query->sortClauses = array(
            new SortClause\DatePublished(Query::SORT_DESC)
        );

        // Initialize pagination.
        $pager = new Pagerfanta(
            new ContentSearchAdapter($query, $this->getRepository()->getSearchService())
        );
        $pager->setMaxPerPage($this->container->getParameter('sillonbol.category.category_list.limit'));
        $pager->setCurrentPage($this->getRequest()->get('page', 1));

        $view->addParameters([
            'pagerBlog' => $pager
        ]);

        return $view;
    }

    public function articlesAction()
    {
        $response = new Response();
        $location = $this->getRepository()->getLocationService()->loadLocation(2);

        // Setting default cache configuration (you can override it in you siteaccess config)
        $response->setSharedMaxAge($this->getConfigResolver()->getParameter('content.default_ttl'));

        // Make the response location cache aware for the reverse proxy
        $response->headers->set('X-Location-Id', $location->id);

        // Getting location and content from ezpublish dedicated services
        if ($location->invisible) {
            throw new NotFoundHttpException("Location #$location->id cannot be displayed as it is flagged as invisible.");
        }

        // Using the criteria helper (a demobundle custom service) to generate our query's criteria.
        // This is a good practice in order to have less code in your controller.
        $criteria = array();
        $criteria[] = new Criterion\Visibility(Criterion\Visibility::VISIBLE);
        $criteria[] = new Criterion\Subtree($location->pathString);
        $criteria[] = new Criterion\ContentTypeIdentifier(array('article', 'blog_post'));

        // Generating query
        $query = new Query();
        $query->query = new Criterion\LogicalAnd($criteria);
        $query->sortClauses = array(
            new SortClause\DatePublished(Query::SORT_DESC)
        );

        // Initialize pagination.
        $pager = new Pagerfanta(
            new ContentSearchAdapter($query, $this->getRepository()->getSearchService())
        );
        $pager->setMaxPerPage($this->container->getParameter('sillonbol.category.category_list.limit'));
        $pager->setCurrentPage($this->getRequest()->get('page', 1));

        return $this->render(
            ':full:categoria.html.twig',
            array(
                'pagerBlog' => $pager,
                'location' => $location
            ),
            $response
        );
    }

}
