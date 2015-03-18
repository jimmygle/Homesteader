<?php namespace Homesteader\Commands\Config\ConfigNew;

use Homesteader\Commands\Config\ConfigCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigNewDatabaseCommand extends ConfigCommand
{

    /**
     * Configure command
     *
     * @return void
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('config:new:database');
        $this->setDescription('Add new database to Homestead config file.');
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

        $databaseName = $this->prompt('Database name: ', 'name', true);

        $changesConfirmed = $this->confirmChanges("About to add <info>{$databaseName}</info> to Homestead config.");
        if ($changesConfirmed === false) {
            $this->outputChangesCanceled();
            return;
        }

        $this->homesteadConfig->addTo('databases', $databaseName);

        $this->homesteadConfigSave();
    }

}