<?php
/**
 * File containing the eZFindResultAdapter class.
 *
 * @author crevillo <crevillo@gmail.com>
 */

namespace Sillonbol\WebBundle\Pagination\Pagerfanta;

use eZ\Publish\Core\Repository\ContentService;
use eZ\Publish\Core\MVC\Legacy\Kernel;
use Sillonbol\WebBundle\Pagination\Pagerfanta\SillonbolSearchHitAdapter;
use Sillonbol\WebBundle\Core\Repository\Values\Content\SillonbolLocationHighlighted;

/**
 * Pagerfanta adapter for eZ Publish content search.
 * Will return results as SillonbolLocationHighlighted objects.
 */
class SillonbolSearchAdapter extends SillonbolSearchHitAdapter
{
    /**
     * @var eZ\Publish\Core\Repository\ContentService
     */
    protected $contentService;

    public function __construct( Kernel $legacyKernel, $searchTerm, ContentService $contentService )
    {
        parent::__construct( $legacyKernel, $searchTerm );
        $this->contentService = $contentService;
    }

    /**
     * Returns a slice of the results as Content objects.
     *
     * @param integer $offset The offset.
     * @param integer $length The length.
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Content The slice.
     */
    public function getSlice( $offset, $length )
    {
        $list = array();
        foreach ( parent::getSlice( $offset, $length ) as $hit )
        {
            $content = $this->contentService->loadContent( $hit['id'] );
            $list[] = new SillonbolLocationHighlighted(
                array(
                    'content' => $content,
                    'highlight' => $hit['highlight']
                )
            );
        }

        return $list;
    }
}
