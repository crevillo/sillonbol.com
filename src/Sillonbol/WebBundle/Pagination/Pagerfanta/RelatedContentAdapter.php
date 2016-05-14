<?php

namespace Sillonbol\WebBundle\Pagination\Pagerfanta;

use eZ\Publish\API\Repository\SearchService;
use eZ\Publish\API\Repository\Values\Content\Query;
use Netgen\TagsBundle\API\Repository\Values\Content\Query\Criterion\TagKeyword;
use Netgen\TagsBundle\API\Repository\Values\Tags\Tag;
use Netgen\TagsBundle\Core\Pagination\Pagerfanta\TagAdapterInterface;
use Pagerfanta\Adapter\AdapterInterface;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use Netgen\TagsBundle\Core\Pagination\Pagerfanta\RelatedContentAdapter as NetgenRelatedContentAdapter;

/**
 * Pagerfanta adapter for content related to a tag.
 * Will return results as content objects.
 */
class RelatedContentAdapter extends NetgenRelatedContentAdapter
{
    /**
     * Returns the number of results.
     *
     * @return int The number of results.
     */
    public function getNbResults()
    {
        if (!$this->tag instanceof Tag) {
            return 0;
        }

        if (!isset($this->nbResults)) {
            $this->nbResults = $this->tagsService->getRelatedContentCount($this->tag);
        }

        return $this->nbResults;
    }

    /**
     * Returns an slice of the results.
     *
     * @param int $offset The offset.
     * @param int $length The length.
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Content[]
     */
    public function getSlice($offset, $length)
    {
        if (!$this->tag instanceof Tag) {
            return array();
        }

        $relatedContent = $this->tagsService->getRelatedContent($this->tag, $offset, $length);

        if (!isset($this->nbResults)) {
            $this->nbResults = $this->tagsService->getRelatedContentCount($this->tag);
        }

        return $relatedContent;
    }

    /**
     * Build default keyword using TagKeyword criterion
     *
     * @return Query
     */
    private function buildQuery()
    {
        $query = new Query();
        $query->query = new Criterion\LogicalAnd(
            array(
                new Criterion\Visibility(Criterion\Visibility::VISIBLE),
                new Criterion\ContentTypeIdentifier('article', 'blog_post'),
                new TagKeyword('=', $this->tag->keyword)
            )
        );

        $query->sortClauses = array(new Query\SortClause\DatePublished(Query::SORT_DESC));

        return $query;
    }
}
