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
class SillonbolSearchHitAdapter implements AdapterInterface
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

    /**
     * @var array
     */
    private $defaultSearchParams;

    /**
     * Constructor
     *
     * @param \eZ\Publish\Core\MVC\Legacy\Kernel $legacyKernel
     * @param string $searchTerm
     */
    public function __construct( Kernel $legacyKernel, $searchTerm )
    {
        $this->legacyKernel = $legacyKernel;
        $this->searchTerm = $searchTerm;
        $this->defaultSearchParams = array(
            'query' => $this->searchTerm,
            'as_objects' => false,
            'class_id' => array( 'article', 'blog_post' ),
            'fields_to_return' => array( 'id', 'highlight' )
        );
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

        $searchResults = $this->doSearch( $this->defaultSearchParams + array( 'limit' => 0 ) );

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
        $sort = array(
            'score' => 'desc',
            'published' => 'desc',
        );

        
        $searchParams = $this->defaultSearchParams + array(
            'sort' => $sort,
            'offset' => $offset,
            'limit' => $length
        );

        $searchResults = $this->doSearch( $searchParams );
        print_r( $searchResults );

        if ( !isset( $this->nbResults ) )
        {
            $this->nbResults = $searchResults['SearchCount'];
        }
        
        return $searchResults['SearchResult'];
    }

    /**
     * Executes the eZFind query via callback function
     * 
     * @param array $searchParams
     * @return array eZFindResult
     */
    private function doSearch( array $searchParams )
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
