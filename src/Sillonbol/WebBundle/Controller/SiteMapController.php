<?php
/**
 * Created by PhpStorm.
 * User: carlosrevillo
 * Date: 16/02/15
 * Time: 15:36
 */

namespace Sillonbol\WebBundle\Controller;

use eZ\Bundle\EzPublishCoreBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use eZ\Publish\API\Repository\Values\Content\LocationQuery;
use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\API\Repository\Values\Content\Query\SortClause;

class SiteMapController extends Controller
{

    public function siteMapAction( Request $request )
    {
	
        $includeCriterion = $this->get('sillonbol.criteria_helper')
                ->generateContentTypeIncludeCriterion(
                // Get contentType identifiers we want to include from configuration (see default_settings.yml).
                $this->container->getParameter('sillonbol.top_menu.content_types_include')
        );

        $contentList = $this->get('sillonbol.menu_helper')->getTopMenuContent( 2, $includeCriterion );   

        $menuList = array();
        // Looping against search results to build $locationList
        // Both arrays will be indexed by contentId so that we can easily refer to an element in a list from another element in the other list
        // See page_topmenu.html.twig
        foreach ($contentList as $contentId => $content) {
            $menuList[$contentId] = $this->getRepository()->getLocationService()->loadLocation($content->contentInfo->mainLocationId);
        }

	$location = $this->getRepository()->getLocationService()->loadLocation( 2 );
        $query = new Query();

        $query->limit = 200;

        $query->query = new Criterion\LogicalAnd(
            array(
                new Criterion\Visibility( Criterion\Visibility::VISIBLE ),
                new Criterion\Subtree( $location->pathString ),
                new Criterion\ContentTypeIdentifier(
                    array( 'article' )
                )
            )
        );

        $query->sortClauses = array( new SortClause\DateModified( Query::SORT_DESC ) );

        $articleList = $this->getRepository()->getSearchService()->findContent( $query )->searchHits;

	$articles = array();

	foreach ($articleList as $contentId => $article) {
            $content = $article->valueObject;
            $articles[$contentId] = $this->getRepository()->getLocationService()->loadLocation($content->contentInfo->mainLocationId);
        }


	$response = $this->render(
            'SillonbolWebBundle:sitemap:sitemap.xml.twig',
            array(
               'home' => $this->getRepository()->getContentService()->loadContent(57),
               'menuList' => $menuList,
	       'articles' => $articles
            )
        );

        $response->setPublic();
        $response->setMaxAge( 86400 );
        $response->setSharedMaxAge( 86400 );
        $response->headers->set( 'X-Location-Id', 2 );

        return $response;
    }
}
