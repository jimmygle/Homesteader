<?php namespace Homesteader\Config;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Yaml\Yaml;
use Homesteader\Config\HomesteadConfig;

class ConfigShowCommand extends Command {
	
	protected $homesteadConfig;

	protected function configure()
	{
		$this->setName('config:show')->setDescription('Show current Homestead config settings.');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$output->write((string) new HomesteadConfig);
	}

}