<?php namespace Homesteader\Config;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigShowCommand extends ConfigCommand {

	/**
	 * Configure command
	 *
	 * @return  void
	 */
	protected function configure()
	{
		$this->setName('config:show');
		$this->setDescription('Show current Homestead config settings.');
	}

    /**
     * Initialize the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @internal param $ Symfony\Component\Console\Input\InputInterface
     * @internal param $ Symfony\Component\Console\Output\OutputInterface
     * @return  void
     */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		parent::execute($input, $output);

		$this->outputRawConfig();		
	}

	/**
	 * Output raw homestead config
	 *
	 * @return  void
	 */
	protected function outputRawConfig()
	{
		$this->output->write((string)$this->homesteadConfig);	
	}

}