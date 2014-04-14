<?php

namespace Kunstmaan\SearchBundle\Provider;

/**
 * Interface for a SearchProvider
 */
interface SearchProviderInterface
{
    /**
     * Returns a unique name for the SearchProvider
     *
     * @return string
     */
    public function getName();

    /**
     * Return the client object
     *
     * @return mixed
     */
    public function getClient();

    /**
     * Create an index
     *
     * @param string $indexName Name of the index
     */
    public function createIndex($indexName);

    /**
     * Return the index object
     *
     * @param $indexName
     * @return mixed
     */
    public function getIndex($indexName);

    /**
     * Create a document
     *
     * @param $uid
     * @param $data
     * @param string $type
     * @param string $index
     * @return mixed
     */
    public function createDocument($uid, $data, $type = '', $index = '');

    /**
     * Add a document to the index
     *
     * @param string $indexName Name of the index
     * @param string $indexType Type of the index to add the document to
     * @param array  $doc       The document to index
     * @param        $uid       Unique ID for this document, this will allow the document to be overwritten by new data instead of being duplicated
     */
    public function addDocument($indexName, $indexType, $doc, $uid);

    /**
     * Add a collection of documents at once
     *
     * @param $docs
     * @return mixed
     */
    public function addDocuments($docs);

    /**
     * delete a document from the index
     *
     * @param string $indexName Name of the index
     * @param string $indexType Type of the index the document is located
     * @param        $uid       Unique ID of the document to be delete
     */
    public function deleteDocument($indexName, $indexType, $uid);

    /**
     * Delete an index
     *
     * @param $indexName    Name of the index to delete
     */
    public function deleteIndex($indexName);
}
