<?php namespace Homesteader\Commands\Config\ConfigNew;

use Homesteader\Commands\Config\ConfigCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigNewVariableCommand extends ConfigCommand
{

    /**
     * Configure command
     *
     * @return void
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('config:new:variable');
        $this->setDescription('Add new variable to Homestead config file.');
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

        $variableKey = $this->prompt('Variable key: ', 'key', true);
        $variableValue = $this->prompt('Variable value: ', 'value', true);

        $changesConfirmed = $this->confirmChanges("About to add environmental variable <info>{$variableKey}</info> = <info>{$variableValue}</info> in Homestead config.");
        if ($changesConfirmed === false) {
            $this->outputChangesCanceled();
            return;
        }

        $this->homesteadConfig->addTo('variables', [
            'key' => $variableKey,
            'value' => $variableValue
        ]);

        $this->homesteadConfigSave();
    }

}