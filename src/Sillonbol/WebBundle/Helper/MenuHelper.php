<?php
/**
 * File containing the MenuHelper class.
 *
 * @author carlos <crevillo@gmail.com>
 */

namespace Sillonbol\WebBundle\Helper;

use eZ\Publish\API\Repository\Repository;
use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use eZ\Publish\API\Repository\Values\Content\Query\SortClause;
use eZ\Publish\API\Repository\Values\Content\Search\SearchResult;

/**
 * Helper for menus
 */
class MenuHelper
{
    /**
     * @var \eZ\Publish\API\Repository\Repository
     */
    private $repository;

    public function __construct( Repository $repository )
    {
        $this->repository = $repository;
    }

    /**
     * Returns Content objects that we want to display in top menu, based on $topLocationId.
     * All content objects are fetched under $topLocationId only (not in the whole tree).
     *
     * One might use $ContentTypeIdentifiers to explicitly include some content types (e.g. "article").
     *
     * @param int $topLocationId
     * @param \eZ\Publish\API\Repository\Values\Content\Query\Criterion $criterion Additional criterion for filtering.
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Content[] Content objects, indexed by their contentId.
     */
    public function getTopMenuContent( $topLocationId, Criterion $criterion = null )
    {
        $criteria = array(
            new Criterion\ParentLocationId( $topLocationId ),
            new Criterion\Visibility( Criterion\Visibility::VISIBLE )
        );

        if ( !empty( $criterion ) )
            $criteria[] = $criterion;

        $query = new Query(
            array(
                'criterion' => new Criterion\LogicalAnd( $criteria ),
                'sortClauses' => array( new SortClause\LocationPriority( Query::SORT_ASC ) )
            )
        );

        return $this->buildContentListFromSearchResult( $this->repository->getSearchService()->findContent( $query ) );
    }

    /**
     * Builds a Content list from $searchResult.
     * Returned array consists of a hash of Content objects, indexed by their ID.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Search\SearchResult $searchResult
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Content[]
     */
    private function buildContentListFromSearchResult( SearchResult $searchResult )
    {
        $contentList = array();
        foreach ( $searchResult->searchHits as $searchHit )
        {
            $contentList[$searchHit->valueObject->contentInfo->id] = $searchHit->valueObject;
        }

        return $contentList;
    }
}
