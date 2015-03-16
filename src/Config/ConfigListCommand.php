<?php namespace Homesteader\Config;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigListCommand extends ConfigCommand
{

    /**
     * Configure command
     *
     * @return  void
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('config');
        $this->setDescription('Shows available config commands.');
    }

    /**
     * Initialize the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @internal param $ Symfony\Component\Console\Input\InputInterface
     * @internal param $ Symfony\Component\Console\Output\OutputInterface
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('');
        $output->writeln('The config commands allow for easy manipulation of the Homestead config file.');
        $output->writeln('');
        $output->writeln('  <comment>config:show</comment>               Outputs the current config file to terminal.');
        $output->writeln('');
        $output->writeln('  All config:new options are optional. You\'ll be prompted unless -n is set.');
        $output->writeln('');
        $output->writeln('  <comment>config:new folder</comment>         Adds synced directory set.');
        $output->writeln('    <info>--host</info>                    Path on your local system.');
        $output->writeln('    <info>--homestead</info>               Path in Homestead\'s filesystem.');
        $output->writeln('');
        $output->writeln('  <comment>config:new site</comment>           Adds site and directory.');
        $output->writeln('    <info>--domain</info>                  Local domain name of site.');
        $output->writeln('    <info>--homestead</info>               Path to public directory in Homestead.');
        $output->writeln('');
        $output->writeln('  <comment>config:new variable</comment>       Adds system variables.');
        $output->writeln('    <info>--key</info>                      Name of variable.');
        $output->writeln('    <info>--value</info>                    Value of variable.');
        $output->writeln('');
        $output->writeln('  <comment>config:new database</comment>       Adds database.');
        $output->writeln('    <info>--name</info>                     Database name.');
        $output->writeln('');
        $output->writeln('  <comment>Global Options:</comment>');
        $output->writeln('    <info>--no-interaction</info> (-n)   No interaction (for unattended use).');
        $output->writeln('    <info>--file</info>           (-f)   Custom config file path.');
        $output->writeln('');
        $output->writeln('');
    }

}