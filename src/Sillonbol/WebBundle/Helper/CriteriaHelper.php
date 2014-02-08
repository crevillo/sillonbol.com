<?php
/**
 * File containing the CriterionHelper class.
 *
 * @copyright Copyright (C) 1999-2014 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace Sillonbol\WebBundle\Helper;

use eZ\Publish\API\Repository\Values\Content\Location;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use eZ\Publish\Core\Base\Exceptions\InvalidArgumentType;
use DateTime;
use DateInterval;

/**
 * Helper class for building criteria easily.
 */
class CriteriaHelper
{
    /**
     * Generates an include criterion based on contentType identifiers.
     *
     * @param array $excludeContentTypeIdentifiers
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Query\Criterion\LogicalAnd
     */
    public function generateContentTypeIncludeCriterion( array $includeContentTypeIdentifiers )
    {
        $includeCriterion = array();
        foreach ( $includeContentTypeIdentifiers as $contentTypeIdentifier )
        {
            $includeCriterion[] = new Criterion\ContentTypeIdentifier( $contentTypeIdentifier );
        }

        return new Criterion\LogicalAnd( $includeCriterion );
    }

}
