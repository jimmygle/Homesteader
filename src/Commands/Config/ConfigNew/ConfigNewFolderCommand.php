<?php namespace Homesteader\Commands\Config\ConfigNew;

use Homesteader\Commands\Config\ConfigCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigNewFolderCommand extends ConfigCommand
{

    /**
     * Configure command
     *
     * @return void
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('config:new:folder');
        $this->setDescription('Add new folder set to Homestead config file.');
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

        $hostFolder = $this->prompt('Path to host machine (not Homestead) directory: ', 'host', true);
        $homesteadFolder = $this->prompt('Path to internal Homestead directory: ', 'homestead', true);

        $changesConfirmed = $this->confirmChanges("About to sync <info>{$hostFolder}</info> to <info>{$homesteadFolder}</info> in Homestead config.");
        if ($changesConfirmed === false) {
            $this->outputChangesCanceled();
            return;
        }

        $this->homesteadConfig->addTo('folders', [
            'map' => $hostFolder,
            'to' => $homesteadFolder
        ]);

        $this->homesteadConfigSave();
    }

}