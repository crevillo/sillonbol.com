<?php
/**
 * This class export our comments to disqus
 *
 * @author crevillo@gmail.com
 */

namespace Disqus\Export\Exporter;
use Disqus\Export\ExporterInterface;
use Disqus\Export\Thread;
use Disqus\Export\Comment;
use \eZContentObjectTreeNode;
use \eZContentObject;
use \eZSys;
use \DateTime;
use \DateTimeZone;

class SbComments implements ExporterInterface
{
    /**
     * Array of commented contentobject ids
     *
     * @var array
     */
    private $contentObjectIds = array();

    private $rowIndex = 0;

    private $rowCount;

    public function __construct()
    {

    }

    /**
     * Returns the exporter human readable name.
     *
     * @return string
     */
    public function getName()
    {
        return 'sB Comments';
    }

    /**
     * Initializes the exporter.
     *
     * @return void
     */
    public function initialize()
    {
        $articles = eZContentObjectTreeNode::subTreeByNodeID(
            array(
                'ClassFilterType' => 'include',
                'ClassFilterArray' => array( 'article', 'blog_post' ),
                'SortBy' => array( 'published', 'asc' )
            ), 2
        );

        foreach ( $articles as $article )
        {
            // if article has children add its contentobject id
            // to the array
            $ncomments = eZContentObjectTreeNode::subTreeCountByNodeID(
                 array(
                    'ClassFilterType' => 'include',
                    'ClassFilterArray' => array( 'comment' )
                 ),
                 $article->attribute( 'node_id' )
            );
            if ( $ncomments > 0 )
                 $this->contentObjectIds[] = $article->attribute( 'contentobject_id' );
        }

        $this->rowCount = count( $this->contentObjectIds );
    }

    /**
     * Returns the total number of comments to export.
     *
     * @return int
     */
    public function getCommentsCount()
    {
        return eZContentObjectTreeNode::subTreeCountByNodeID(
            array(
                'ClassFilterType' => 'include',
                'ClassFilterArray' => array( 'comment' )
            ), 2
        );
    }

    /**
     * Returns the total number of threads
     *
     * @return int
     */
    public function getThreadsCount()
    {
        return $this->rowCount;
    }


    /**
     * Returns the next thread to export comments from.
     * This thread object might reflect an eZ Publish content object.
     *
     * If there is no more thread to process, this method will return false.
     *
     * @return \Disqus\Export\Thread|false
     */
    public function getNextThread()
    {
        if ( $this->rowIndex < $this->rowCount )
        {
            $contentObject = eZContentObject::fetch( $this->contentObjectIds[$this->rowIndex] );

            // Building the Thread object
            // title and content properties get the same content since $thread->content is not really important
            $thread = new Thread;
            $thread->title = $thread->content = $contentObject->name();
            $thread->identifier = $contentObject->attribute( 'id' );
            $thread->link = $this->generateThreadLinkByContentObject( $contentObject );
            $thread->postDate = new DateTime(
                '@' . $contentObject->attribute( 'published' ),
                new DateTimeZone( 'gmt' )
            );

            $this->rowIndex++;

            return $thread;
        }

        return false;
    }

    /**
     * Generates absolute link for thread (content object), taking care of SSL zones when applyable
     *
     * @param eZContentObject $contentObject Content object to generate link for
     * @return string
     */
    protected function generateThreadLinkByContentObject( eZContentObject $contentObject )
    {
        $protocol = 'http://';
        $host = 'www.sillonbol.com';
        $portString = '';

        return $protocol . $host . $portString . eZSys::indexDir( false ) . '/' . $contentObject->mainNode()->urlAlias();
    }

    /**
     * Returns all comments for provided $thread object as an array of {@link \Disqus\Export\Comment objects}
     *
     * @return \Disqus\Export\Comment[]
     */
    public function getCommentsByThread( Thread $thread )
    {
        $sbcomments = eZContentObjectTreeNode::subTreeByNodeID(
            array(
                'ClassFilterType' => 'include',
                'ClassFilterArray' => array( 'comment' ),
            ),
            eZContentObject::fetch( $thread->identifier )->mainNodeID()
        );

        foreach ( $sbcomments as $sbcomment )
        {
            $comments[] = $this->buildCommentFromSbComment( $sbcomment );
        }

        unset( $sbcomments );
        return $comments;
    }

        /**
     * @param \ezContentObjectTreeNode $szcomment
     * @return \Disqus\Export\Comment
     */
    protected function buildCommentFromSbComment( ezContentObjectTreeNode $sbcomment )
    {
        $comment = new Comment;
        $comment->id = $sbcomment->attribute( 'contentobject_id' );
        $comment->authorName = $sbcomment->attribute( 'author' );
        $comment->date = new DateTime(
            '@' . $sbcomment->attribute( 'object' )->attribute( 'modified' ),
            new DateTimeZone( 'gmt' )
        );
        $comment->content = $sbcomment->attribute( 'message' );
        $comment->isApproved = $sbcomment->attribute( 'status' ) == 1;

        return $comment;
    }


    /**
     * Final method called at the end of the export process.
     *
     * @return void
     */
    public function cleanup()
    {

    }
}

?>
