<?php

namespace Sillonbol\WebBundle\Core\Repository;

use eZ\Publish\API\Repository\Values\Content\Query\Criterion\LogicalAnd;
use Netgen\TagsBundle\Core\Repository\TagsService as NetgenTagsService;
use Netgen\TagsBundle\API\Repository\Values\Tags\Tag;
use eZ\Publish\Core\Base\Exceptions\UnauthorizedException;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\ContentId;
use eZ\Publish\API\Repository\Values\Content\Query;

class TagsService extends NetgenTagsService
{
    /**
     * @inheritdoc
     */
    public function getRelatedContentCount(Tag $tag)
    {
        if ($this->hasAccess('tags', 'read') !== true) {
            throw new UnauthorizedException('tags', 'read');
        }

        $spiTagInfo = $this->tagsHandler->loadTagInfo($tag->id);
        $relatedContentIds = $this->tagsHandler->loadRelatedContentIds($spiTagInfo->id);
        if (empty($relatedContentIds)) {
            return 0;
        }

        $searchResult = $this->repository->getSearchService()->findContent(
            new Query(
                array(
                    'limit' => 0,
                    'filter' => new LogicalAnd(
                        array(
                            new ContentId($relatedContentIds),
                            new Query\Criterion\Visibility(Query\Criterion\Visibility::VISIBLE),
                            new Query\Criterion\ContentTypeIdentifier(
                                array(
                                    'article',
                                    'blog_post'
                                )
                            )
                        )
                    ),

                )
            )
        );

        return $searchResult->totalCount;
    }

    /**
     * @inheritdoc
     */
    public function getRelatedContent(Tag $tag, $offset = 0, $limit = -1, $returnContentInfo = false)
    {
        if ($this->hasAccess('tags', 'read') !== true) {
            throw new UnauthorizedException('tags', 'read');
        }

        $spiTagInfo = $this->tagsHandler->loadTagInfo($tag->id);
        $relatedContentIds = $this->tagsHandler->loadRelatedContentIds($spiTagInfo->id);
        if (empty($relatedContentIds)) {
            return array();
        }

        $method = 'findContent';
        if ($returnContentInfo) {
            $method = 'findContentInfo';
        }

        $searchResult = $this->repository->getSearchService()->$method(
            new Query(
                array(
                    'offset' => $offset,
                    'limit' => $limit > 0 ? $limit : 10000,
                    'filter' => new LogicalAnd(
                        array(
                            new ContentId($relatedContentIds),
                            new Query\Criterion\Visibility(Query\Criterion\Visibility::VISIBLE),
                            new Query\Criterion\ContentTypeIdentifier(
                                array(
                                    'article',
                                    'blog_post'
                                )
                            )
                        )
                    ),
                    'sortClauses' => array(new Query\SortClause\DatePublished(Query::SORT_DESC))
                )
            )
        );

        $content = array();
        foreach ($searchResult->searchHits as $searchHit) {
            $content[] = $searchHit->valueObject;
        }

        return $content;
    }
}
