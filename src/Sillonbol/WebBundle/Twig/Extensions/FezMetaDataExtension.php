<?php

namespace Sillonbol\WebBundle\Twig\Extensions;

use eZ\Publish\Core\Persistence\Database\DatabaseHandler;
use eZ\Publish\Core\MVC\ConfigResolverInterface;
use Stash\Pool;
use Twig_Extension;
use Twig_Environment;
use Twig_SimpleFunction;
use Twig_Template;

class FezMetaDataExtension extends Twig_Extension
{
    /**
     * Connection
     *
     * @var mixed
     */
    protected $dbHandler;

    protected $pool;

    public function __construct(
        Pool $pool
    )
    {
        $this->pool = $pool;
    }

    /**
     * Set database handler
     *
     * @param mixed $dbHandler
     *
     * @return void
     * @throws \RuntimeException if $dbHandler is not an instance of
     *         {@link \eZ\Publish\Core\Persistence\Database\DatabaseHandler}
     */
    public function setConnection( $dbHandler )
    {
        // This obviously violates the Liskov substitution Principle, but with
        // the given class design there is no sane other option. Actually the
        // dbHandler *should* be passed to the constructor, and there should
        // not be the need to post-inject it.
        if ( !$dbHandler instanceof DatabaseHandler )
        {
            throw new \RuntimeException( "Invalid dbHandler passed" );
        }

        $this->dbHandler = $dbHandler;
    }

    /**
     * Returns the active connection
     *
     * @throws \RuntimeException if no connection has been set, yet.
     *
     * @return \eZ\Publish\Core\Persistence\Database\DatabaseHandler
     */
    protected function getConnection()
    {
        if ( $this->dbHandler === null )
        {
            throw new \RuntimeException( "Missing database connection." );
        }
        return $this->dbHandler;
    }

    public function getName()
    {
        return 'eflweb.metadata';
    }

    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction(
                'ez_fezmetadata',
                array( $this, 'getMetadata' ),
                array( 'is_safe' => array( 'html' ) )
            ),
        );
    }

    /**
     * Devuelve los metadatas para el objeto pasado .
     *
     * @param int $contentInfoId
     * @return array
     */
    public function getMetaData( $contentInfoId )
    {


            $metadata = array();

            $dbHandler = $this->getConnection();

            $query = $dbHandler->createSelectQuery();
            $query->select( "meta_name", "meta_value" )
                  ->from( $dbHandler->quoteTable( "fezmeta_data" ) )
                  ->where(
                      $query->expr->lAnd(
                          $query->expr->eq(
                              $dbHandler->quoteColumn( "contentobject_id" ),
                              $contentInfoId
                          )
                      )
                  );

            $statement = $query->prepare();
            $statement->execute();

            foreach ($statement->fetchAll() as $row) {
                foreach ($row as $key => $val) {
                    $metadata[$row['meta_name']] = $row['meta_value'];
                }
            }


        return $metadata;
    }
}
