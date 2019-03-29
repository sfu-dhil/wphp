<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * WphpDefineViewsCommand command.
 */
class DefineViewsCommand extends ContainerAwareCommand
{
    /**
     * Configure the command.
     */
    protected function configure()
    {
        $this
            ->setName('wphp:define:views')
            ->setDescription('Define the views necessary for WPHP.');
        ;
    }

    /**
     * Execute the command.
     *
     * @param InputInterface $input
     *   Command input, as defined in the configure() method.
     * @param OutputInterface $output
     *   Output destination.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine');
        $sql = <<<'ENDSQL'
create or replace view title_source as select ROW_NUMBER() OVER (order by title_id, source_id, identifier) as ID, title_id, source_id, identifier FROM (
	select id as title_id, source as source_id, source_id as identifier from title where source_id is not null union 
	select id as title_id, source2 as source_id, source2_id as identifier from title where source2_id is not null union
	select id as ittle_id, source3 as source_id, source3_id as identifier from title where source3_id is not null order by title_id, source_id, identifier
) t0;
ENDSQL;

        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
    }



}
