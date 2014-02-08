<?php
$url = $Params['url'];
$oldsettingsini = eZINI::instance( 'oldsettings.ini' );
$module = $Params['Module'];
$olddb_conn_params = array( 
    'use_defaults' => false,
    'server' => $oldsettingsini->variable( 'OldDatabaseSettings', 'Server' ),
    'port' => $oldsettingsini->variable( 'OldDatabaseSettings', 'Port' ),
    'user' => $oldsettingsini->variable( 'OldDatabaseSettings', 'User' ),
    'database' => $oldsettingsini->variable( 'OldDatabaseSettings', 'Database' ),
    'password' => $oldsettingsini->variable( 'OldDatabaseSettings', 'Password' ),
);

$olddb = eZDB::instance( 'ezmysqli', $olddb_conn_params, true );

$results = $olddb->arrayQuery( "SELECT id FROM articulos WHERE url = '{$url}'" );
if( $results[0]['id'] )
{
    $solr = new eZSolr();
    $query = $solr->search( 
        '', array(
        'SearchContentClassId' => array( 'article', 'blog_post' ),
        'Filter' => 'attr_id_articulo_s:' . $results[0]['id']
        )
    );
    if( $query['SearchCount'] > 0 )
    {
        $node = $query['SearchResult'][0];
        
        return $module->redirectTo( '/' . $node->attribute( 'url_alias' ) );
    }
}

eZExecution::cleanExit();
?>
