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
        $this->output->writeln('  <comment>config:show</comment>             Outputs the current config file to terminal.');
        $this->output->writeln('');
        $this->output->writeln('  Available options are included with example values.');
        $this->output->writeln('');
        $this->output->writeln('  <comment>config:new folder</comment>       Adds synced directory set.');
        $this->output->writeln('    <info>--host</info>      (-h)      /path/to/host/dir');
        $this->output->writeln('    <info>--homestead</info> (-g)      /path/on/homestead/to/dir');
        $this->output->writeln('');
        $this->output->writeln('  <comment>config:new site</comment>         Adds site and directory.');
        $this->output->writeln('    <info>--domain</info>    (-d)      domaintoadd.com');
        $this->output->writeln('    <info>--homestead</info> (-g)      /path/on/homestead/to/public/');
        $this->output->writeln('');
        $this->output->writeln('  <comment>config:new variable</comment>     Adds variable.');
        $this->output->writeln('    <info>--key</info>       (-k)      VAR');
        $this->output->writeln('    <info>--value</info>     (-v)      VALUE');
        $this->output->writeln('');
        $this->output->writeln('  <comment>config:new database</comment>     Adds database.');
        $this->output->writeln('    <info>--name</info>      (-n)      db_name');
        $this->output->writeln('');
        $this->output->writeln('  Add -n for no interaction.');
        $this->output->writeln('');
    }

}