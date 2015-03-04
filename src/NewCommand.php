<?php namespace Homesteader;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Yaml\Yaml;

class NewCommand extends Command {
	
	protected function configure()
	{
		$this
			->setName('new')
			->setDescription('Add a new item to the Homestead config.')
			->addArgument('key', InputArgument::OPTIONAL, 'Tpye of config to add.');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$keyToAdd = $input->getArgument('key');
		$output->write("New config entry. {$keyToAdd}");
	}

}