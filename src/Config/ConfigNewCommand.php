<?php namespace Homesteader\Config;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Helper\Table;

class ConfigNewCommand extends Command {

	protected $input;
	protected $output;
	protected $homesteadConfig;

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
		$this->homesteadConfig = new HomesteadConfig;
		
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
			case ('variable' || 'var'):
				$this->newVariable();
				break;
			default:
				$this->output->writeln('<error>Invalid entry!</error>');
				break;
		}
	}

	protected function newFolder()
	{
		$helper = $this->getHelper('question');

		$hostFolderPrompt = new Question('Path to host machine (not Homestead) directory: ');
		$hostFolder = $helper->ask($this->input, $this->output, $hostFolderPrompt);

		$homesteadFolderPrompt = new Question("Path to internal Homestead directory: ");
		$homesteadFolder = $helper->ask($this->input, $this->output, $homesteadFolderPrompt);

		$this->output->writeln("About to sync <info>{$hostFolder}</info> to <info>{$homesteadFolder}</info> in Homestead config.");
		$confirmation = new ConfirmationQuestion('Continue? [y/n] ', false);
		if ( ! $helper->ask($this->input, $this->output, $confirmation)) {
			return;
		}

		$this->homesteadConfig->addTo('folders', [
			'map' => $hostFolder,
			'to' => $homesteadFolder
		]);

		try {
			$this->homesteadConfig->save();
		} catch (Exception $e) {
			$this->output->writeln("<error>{$e->getMessage()}</error>");
		}

		$this->output->writeln('<info>Homestead config file successfully updated.</info>');
	}

	protected function newSite()
	{
		$helper = $this->getHelper('question');

		$domainNamePrompt = new Question('Domain name: ');
		$domainName = $helper->ask($this->input, $this->output, $domainNamePrompt);

		$homesteadWebRootPrompt = new Question('Path to web root in Homestead: ');
		$homesteadWebRoot = $helper->ask($this->input, $this->output, $homesteadWebRootPrompt);

		$this->output->writeln("About to point <info>{$domainName}</info> to <info>{$homesteadWebRoot}</info> in Homestead config.");
		$confirmation = new ConfirmationQuestion('Continue? [y/n] ', false);
		if ( ! $helper->ask($this->input, $this->output, $confirmation)) {
			return;
		}

		$this->homesteadConfig->addTo('sites', [
			'map' => $domainName,
			'to' => $homesteadWebRoot
		]);

		try {
			$this->homesteadConfig->save();
		} catch (Exception $e) {
			$this->output->writeln("<error>{$e->getMessage()}</error>");
		}

		$this->output->writeln('<info>Homestead config file successfully updated.</info>');
	}

	protected function newDatabase()
	{
		$helper = $this->getHelper('question');

		$databaseNamePrompt = new Question('Database name: ');
		$databaseName = $helper->ask($this->input, $this->output, $databaseNamePrompt);

		$this->output->writeln("About to add <info>{$databaseName}</info> to Homestead config.");
		$confirmation = new ConfirmationQuestion('Continue? [y/n] ', false);
		if ( ! $helper->ask($this->input, $this->output, $confirmation)) {
			return;
		}

		$this->homesteadConfig->addTo('databases', $databaseName);

		try {
			$this->homesteadConfig->save();
		} catch (Exception $e) {
			$this->output->writeln("<error>{$e->getMessage()}</error>");
		}

		$this->output->writeln('<info>Homestead config file successfully updated.</info>');
	}

	protected function newVariable()
	{
		$helper = $this->getHelper('question');

		$variableKeyPrompt = new Question('Variable key: ');
		$variableKey = $helper->ask($this->input, $this->output, $variableKeyPrompt);

		$variableValuePrompt = new Question('Variable value: ');
		$variableValue = $helper->ask($this->input, $this->output, $variableValuePrompt);

		$this->output->writeln("About to add environmental variable <info>{$variableKey}</info> = <info>{$variableValue}</info> in Homestead config.");
		$confirmation = new ConfirmationQuestion('Continue? [y/n] ', false);
		if ( ! $helper->ask($this->input, $this->output, $confirmation)) {
			return;
		}

		$this->homesteadConfig->addTo('variables', [
			'key' => $variableKey,
			'value' => $variableValue
		]);

		try {
			$this->homesteadConfig->save();
		} catch (Exception $e) {
			$this->output->writeln("<error>{$e->getMessage()}</error>");
		}

		$this->output->writeln('<info>Homestead config file successfully updated.</info>');
	}

}