<?php
/**
 * Created by PhpStorm.
 * User: ruud
 * Date: 03/03/14
 * Time: 16:10
 */

namespace Kunstmaan\SearchBundle\Provider;


use Elastica\Client;
use Elastica\Document;
use Elastica\Index;

class ElasticaProvider implements SearchProviderInterface
{
    private $client;
    private $nodes;

    public function getClient()
    {
        if (!$this->client instanceof Client) {
            $this->client = new Client(array(
                $this->nodes
            ));
        }

        return $this->client;
    }

    public function getName()
    {
        return 'Elastica';
    }

    public function createIndex($indexName)
    {
        return new Index($this->getClient(), $indexName);

    }

    public function getIndex($indexName)
    {
        return $this->getClient()->getIndex($indexName);
    }

    public function createDocument($uid, $data, $type = '', $index = '')
    {
        return new Document($uid, $data, $type, $index);
    }

    public function addDocument($indexName, $indexType, $data, $uid)
    {
        $doc = $this->createDocument($uid, $data);
        $this->getClient()->getIndex($indexName)->getType($indexType)->addDocument($doc);
    }

    public function addDocuments($docs)
    {
        $this->getClient()->addDocuments($docs);
    }

    public function deleteDocument($indexName, $indexType, $uid)
    {
        $ids = array($uid);
        $this->deleteDocuments($indexName, $indexType, $ids);
    }

    public function deleteDocuments($indexName, $indexType, array $ids)
    {
        $index = $this->getIndex($indexName);
        $type = $index->getType($indexType);
        $this->getClient()->deleteIds($ids, $index, $type);
    }

    public function deleteIndex($indexName)
    {
        $this->getIndex($indexName)->delete();
    }

    public function addNode($host, $port)
    {
        $this->nodes[] = array('host' => $host, 'port' => $port);
    }
}