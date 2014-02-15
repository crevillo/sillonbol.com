<?php
/**
 * File containing the eZFindResultAdapter class.
 *
 * @author crevillo <crevillo@gmail.com>
 */

namespace Sillonbol\WebBundle\Pagination\Pagerfanta;

use eZ\Publish\Core\Repository\LocationService;
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
     * @var eZ\Publish\Core\Repository\LocationService
     */
    protected $locationService;

    public function __construct( Kernel $legacyKernel, $searchTerm, LocationService $locationService )
    {
        parent::__construct( $legacyKernel, $searchTerm );
        $this->locationService = $locationService;
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
            $location = $this->locationService->loadLocation( $hit['main_node_id'] );
            $list[] = new SillonbolLocationHighlighted(
                array(
                    'location' => $location,
                    'highlight' => $hit['highlight']
                )
            );
        }

        return $list;
    }
}

