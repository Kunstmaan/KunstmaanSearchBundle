<?php

namespace Kunstmaan\SearchBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to populate all indexes. Use the 'full' argument when you want to delete and add all indexes again
 *
 * It will load the SearchConfigurationChain and call the populateIndex() method on each SearchConfiguration
 */
class PopulateIndexCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('kuma:search:populate')
            ->addArgument('full', InputArgument::OPTIONAL, 'Delete and create new index(es) before populating')
            ->setDescription('Populate the index(es)')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getArgument('full')) {
            $deleteCommand = $this->getApplication()->find('kuma:search:delete');
            $deleteCommand->execute(new ArrayInput(array()), $output);
            $setupCommand = $this->getApplication()->find('kuma:search:setup');
            $setupCommand->execute(new ArrayInput(array()), $output);
        }
        $searchConfigurationChain = $this->getContainer()->get('kunstmaan_search.search_configuration_chain');
        foreach ($searchConfigurationChain->getConfigurations() as $alias => $searchConfiguration) {
            $searchConfiguration->populateIndex();
            $output->writeln('Index populated : ' . $alias);
        }
    }

}
