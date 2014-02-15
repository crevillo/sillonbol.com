<?php
/**
 * File containing the Sillonbol\WebBundle\API\Repository\Values\Content\SillonbolLocationHighlighted class.
 *
 * @author crevillo <crevillo@gmail.com>
 */

namespace Sillonbol\WebBundle\Core\Repository\Values\Content;

use Sillonbol\WebBundle\API\Repository\Values\Content\SillonbolLocationHighlighted as APISillonbolLocationHighlighted;

class SillonbolLocationHighlighted extends APISillonbolLocationHighlighted 
{
    /**
     * Location associated to our new object
     *
     * @var \eZ\Publish\API\Repository\Values\Content\Location
     */
    protected $location;

    public function getLocation()
    {
        return $this->location;
    }
}
