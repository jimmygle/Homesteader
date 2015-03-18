<?php namespace Homesteader\Commands\Config\ConfigNew;

use Homesteader\Commands\Config\ConfigCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigNewSiteCommand extends ConfigCommand
{

    /**
     * Configure command
     *
     * @return void
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('config:new:site');
        $this->setDescription('Add new site to Homestead config file.');
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

        $domainName = $this->prompt('Domain name: ', 'domain', true);
        $homesteadWebRoot = $this->prompt('Path to web root in Homestead: ', 'homestead', true);

        $changesConfirmed = $this->confirmChanges("About to point <info>{$domainName}</info> to <info>{$homesteadWebRoot}</info> in Homestead config.");
        if ($changesConfirmed === false) {
            $this->outputChangesCanceled();
            return;
        }

        $this->homesteadConfig->addTo('sites', [
            'map' => $domainName,
            'to' => $homesteadWebRoot
        ]);

        $this->homesteadConfigSave();
    }

}