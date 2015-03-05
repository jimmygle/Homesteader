<?php namespace Homesteader\Config;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Yaml\Yaml;

class ConfigNewCommand extends Command {

	protected $input;
	protected $output;

	protected function configure()
	{
		$this
			->setName('config:new')
			->setDescription('Add a new item to the Homestead config.')
			->addArgument('key', InputArgument::OPTIONAL, 'Tpye of config to add.');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$this->input = $input;
		$this->output = $output;
		
		switch ($input->getArgument('key')) {
			case 'folder':
				$this->newFolder();
				break;
			case 'site':
				$this->newSite();
				break;
			case 'database':
				$this->newDatabase();
				break;
			case 'variable':
				$this->newVariable();
				break;
			default:
				$this->output->write('<error>Invalid entry!</error>');
				break;
		}
	}

	protected function newFolder()
	{
		$helper = $this->getHelper('question');

		$hostFolderPrompt = new Question('Path to host machine (not Homestead) directory: ');
		$hostFolder = $helper->ask($this->input, $this->output, $hostFolderPrompt);

		$guestFolderPrompt = new Question('Path to guest\'s (Homestead) directory: ');
		$guestFolder = $helper->ask($this->input, $this->output, $guestFolderPrompt);

		$this->output->writeln("About to sync <info>{$hostFolder}</info> to <info>{$guestFolder}</info>.");
		$confirmation = new ConfirmationQuestion('Continue? [y/n] ', false);
		if ( ! $helper->ask($this->input, $this->output, $confirmation)) {
			return;
		}

		$rawConfigFileContents = file_get_contents('/Users/jimmygle/.homestead/Homestead.yaml');
		$parsedConfig = Yaml::parse($rawConfigFileContents);

		$parsedConfig['folders'][] = [
			'map' => $hostFolder,
			'to' => $guestFolder
		];

		$yamlConfig = Yaml::dump($parsedConfig, 3);
		$configFileUpdated = file_put_contents('/Users/jimmygle/.homestead/Homestead.yaml', $yamlConfig);

		if ($configFileUpdated) {
			$this->output->write('<info>Homestead config file successfully updated.</info>');
		} else {
			$this->output->write('<error>Error updating the config file.</error>');
		}
	}

}