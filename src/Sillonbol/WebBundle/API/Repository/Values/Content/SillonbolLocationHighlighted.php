<?php
/**
 * File containing the Sillonbol\WebBundle\API\Repository\Values\Content\SillonbolLocationHighlighted class.
 *
 * @author crevillo <crevillo@gmail.com>
 */

namespace Sillonbol\WebBundle\API\Repository\Values\Content;

use eZ\Publish\API\Repository\Values\ValueObject;

/**
 * This class is the union of a location and the highlighted text associated
 * with it in a eZ Find search Result
 *
 * @property-read \eZ\Publish\API\Repository\Values\Content\Location $location calls getLocation()
 * @property-read string $highlighted_text The highlighted text associated with the location in a eZFind result
 */
abstract class SillonbolLocationHighlighted extends ValueObject 
{
    /**
     * Returns the content info of the content object of this location
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Location
     */
    abstract public function getLocation();

    /**
     * The highlighted text
     *
     * @var string
     */
    protected $highlight;

}
