<?php
/**
 * File containing the ContentSearchHitAdapter class.
 *
 * @copyright Copyright (C) 1999-2014 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace Sillonbol\WebBundle\Pagination\Pagerfanta;

use Pagerfanta\Adapter\AdapterInterface;
use eZ\Publish\Core\MVC\Legacy\Kernel;
use eZFunctionHandler;

/**
 * Pagerfanta adapter for eZ Publish content search.
 * Will return results as SearchHit objects.
 */
class eZFindResultHitAdapter implements AdapterInterface
{
    /**
     * @var eZ\Publish\Core\MVC\Legacy\Kernel
     */
    private $legacyKernel;

    /**
     * @var int
     */
    private $nbResults;

    /**
     * @var string
     */
    private $searchTerm;

    public function __construct( Kernel $legacyKernel, $searchTerm )
    {
        $this->legacyKernel = $legacyKernel;
        $this->searchTerm = $searchTerm;
    }

    /**
     * Returns the number of results.
     *
     * @return integer The number of results.
     */
    public function getNbResults()
    {
        if ( isset( $this->nbResults ) )
        {
            return $this->nbResults;
        }

        $searchPhrase = $this->searchTerm;

        $searchParams = array(
            'query' => $searchPhrase,
            'as_objects' => false,
            'limit' => 0
        );

        $searchResults = $this->doSearch( $searchParams );

        return $this->nbResults = $searchResults['SearchCount'];
    }

    /**
     * Returns as slice of the results, as SearchHit objects.
     *
     * @param integer $offset The offset.
     * @param integer $length The length.
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Search\SearchHit The slice.
     */
    public function getSlice( $offset, $length )
    {
        $searchPhrase = $this->searchTerm;
        $sort = array(
            'score' => 'desc',
            'published' => 'desc',
        );

        $searchParams = array(
            'query' => $searchPhrase,
            'sort' => $sort,
            'as_objects' => false,
            'offset' => $offset,
            'limit' => $limit
        );

        $searchResults = $this->doSearch( $searchParams );

        if ( !isset( $this->nbResults ) )
        {
            $this->nbResults = $searchResults['SearchCount'];
        }
        
        return $searchResults['SearchResult'];
    }

    private function doSearch( $searchParams )
    {
        return $this->legacyKernel->runCallback(
            function () use ( $searchParams )
            {
                return eZFunctionHandler::execute(
                    'ezfind',
                    'search',
                    $searchParams
                );
            }
        );
    }
}