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
 * Helper for content
 */
class ContentHelper
{
    /**
     * @var \eZ\Publish\API\Repository\Repository
     */
    private $repository;

    public function __construct( Repository $repository )
    {
        $this->repository = $repository;
    }

    public function getLatestContent( $offset = 0, $limit = 3 )
    {
        $criteria = array(
            new Criterion\Subtree( '/1/2' ),
            new Criterion\Visibility( Criterion\Visibility::VISIBLE ),
            new Criterion\ContentTypeIdentifier( 'article' )
        );

        $query = new Query(
            array(
                'criterion' => new Criterion\LogicalAnd( $criteria ),
                'sortClauses' => array( new SortClause\DatePublished( Query::SORT_DESC ) )
            )
        );
        $query->offset = $offset;
        $query->limit = $limit;

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
