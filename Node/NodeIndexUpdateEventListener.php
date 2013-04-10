<?php

namespace Kunstmaan\SearchBundle\Node;

use Kunstmaan\NodeBundle\Event\NodeEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

class NodeIndexUpdateEventListener {

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function onPostPublish(NodeEvent $event)
    {
        $this->index($event);
    }

    public function onPostPersist(NodeEvent $event)
    {
        $this->index($event);
    }

    private function index(NodeEvent $event)
    {
        $nodeSearchConfiguration = $this->container->get('kunstmaan_search.searchconfiguration.node');
        $nodeSearchConfiguration->indexNodeTranslation($event->getNodeTranslation());
    }

    public function onPostDelete(NodeEvent $event)
    {
        $this->delete($event);
    }

    public function onPostUnPublish(NodeEvent $event)
    {
        $this->delete($event);
    }

    /**
     * @param NodeEvent $event
     */
    public function delete(NodeEvent $event)
    {
        $nodeSearchConfiguration = $this->container->get('kunstmaan_search.searchconfiguration.node');
        $nodeSearchConfiguration->deleteNodeTranslation($event->getNodeTranslation());
    }

}