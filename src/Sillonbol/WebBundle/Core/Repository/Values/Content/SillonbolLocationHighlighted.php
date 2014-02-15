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
     * Content associated to our new object
     *
     * @var \eZ\Publish\API\Repository\Values\Content\Content
     */
    protected $content;

    public function getContent()
    {
        return $this->content;
    }
}
