<?php
namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class MyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:my-command')

            // the short description shown while running "php bin/console list"
            ->setDescription('This is a test command')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('The test command allows you...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'My Command',
            '============',
            '',
        ]);

        // outputs a message followed by a "\n"
        $output->writeln('Whoa!');

        // outputs a message without adding a "\n" at the end of the line
        $output->write('You are about to ');
        $output->write('create a user.');

        $logger = $this->getContainer()->get('monolog.logger.product');
        $logger->info('from command');
    }
}