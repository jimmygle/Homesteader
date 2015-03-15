<?php namespace Homesteader\Config;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigListCommand extends ConfigCommand {

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
        parent::execute($input, $output);

        $this->output->writeln('');
        $this->output->writeln('The config commands allow for easy manipulation of the Homestead config file.');
        $this->output->writeln('');
        $this->output->writeln('  <comment>config:show</comment>               Outputs the current config file to terminal.');
        $this->output->writeln('');
        $this->output->writeln('  All config:new options are optional. You\'ll be prompted unless -n is set.');
        $this->output->writeln('');
        $this->output->writeln('  <comment>config:new folder</comment>         Adds synced directory set.');
        $this->output->writeln('    <info>--host</info>           (-o)     Path on your local system.');
        $this->output->writeln('    <info>--homestead</info>      (-g)     Path in Homestead\'s filesystem.');
        $this->output->writeln('');
        $this->output->writeln('  <comment>config:new site</comment>           Adds site and directory.');
        $this->output->writeln('    <info>--domain</info>         (-d)     Local domain name of site.');
        $this->output->writeln('    <info>--homestead</info>      (-g)     Path to public directory in Homestead.');
        $this->output->writeln('');
        $this->output->writeln('  <comment>config:new variable</comment>       Adds system variables.');
        $this->output->writeln('    <info>--key</info>            (-k)      Name of variable.');
        $this->output->writeln('    <info>--value</info>          (-a)      Value of variable.');
        $this->output->writeln('');
        $this->output->writeln('  <comment>config:new database</comment>       Adds database.');
        $this->output->writeln('    <info>--name</info>           (-b)      Database name.');
        $this->output->writeln('');
        $this->output->writeln('  <comment>Global Options:</comment>');
        $this->output->writeln('    <info>--no-interaction</info> (-n)   No interaction (for unattended use).');
        $this->output->writeln('    <info>--file</info>           (-f)   Custom config file path.');
        $this->output->writeln('');
        $this->output->writeln('');
    }

}