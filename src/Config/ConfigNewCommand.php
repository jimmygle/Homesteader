<?php namespace Homesteader\Config;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigNewCommand extends ConfigCommand {

	/**
	 * Configure command
	 *
	 * @return  void
	 */
	protected function configure()
	{
		$this->setName('config:new');
		$this->setDescription('Add a new item to the Homestead config.');
		$this->addArgument('key', InputArgument::OPTIONAL, 'Tpye of config to add.');
	}

    /**
     * Initialize the command and determine which path to take
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
		
		switch ($this->input->getArgument('key')) {
			case 'folder':
				$this->newFolder();
				break;
			case 'site':
				$this->newSite();
				break;
			case 'database':
				$this->newDatabase();
				break;
			case ('variable'):
				$this->newVariable();
				break;
			default:
				$this->output->writeln('<error>Invalid entry!</error>');
				break;
		}
	}

	/**
	 * Add new folders config set to config file
	 *
	 * Command:
	 * homesteader config:new folder
	 *
	 * @return  void
	 */
	protected function newFolder()
	{
		$hostFolder = $this->prompt('Path to host machine (not Homestead) directory: ');
		$homesteadFolder = $this->prompt('Path to internal Homestead directory: ');

		$changesConfirmed = $this->confirmChanges("About to sync <info>{$hostFolder}</info> to <info>{$homesteadFolder}</info> in Homestead config.");
		if ($changesConfirmed === false) {
			return;
		}

		$this->homesteadConfig->addTo('folders', [
			'map' => $hostFolder,
			'to' => $homesteadFolder
		]);

		$this->homesteadConfigSave();
	}

	/**
	 * Add new sites config set to config file
	 *
	 * Command:
	 * homesteader config:new site
	 *
	 * @return  void
	 */
	protected function newSite()
	{
		$domainName = $this->prompt('Domain name: ');
		$homesteadWebRoot = $this->prompt('Path to web root in Homestead: ');

		$changesConfirmed = $this->confirmChanges("About to point <info>{$domainName}</info> to <info>{$homesteadWebRoot}</info> in Homestead config.");
		if ($changesConfirmed === false) {
			return;
		}

		$this->homesteadConfig->addTo('sites', [
			'map' => $domainName,
			'to' => $homesteadWebRoot
		]);

		$this->homesteadConfigSave();
	}

	/**
	 * Add new variables config set to config file
	 *
	 * Command:
	 * homesteader config:new var|variable
	 *
	 * @return  void
	 */
	protected function newVariable()
	{
		$variableKey = $this->prompt('Variable key: ');
		$variableValue = $this->prompt('Variable value: ');

		$changesConfirmed = $this->confirmChanges("About to add environmental variable <info>{$variableKey}</info> = <info>{$variableValue}</info> in Homestead config.");
		if ($changesConfirmed === false) {
			return;
		}

		$this->homesteadConfig->addTo('variables', [
			'key' => $variableKey,
			'value' => $variableValue
		]);

		$this->homesteadConfigSave();
	}

	/**
	 * Add new database config file
	 *
	 * Command:
	 * homesteader config:new database
	 *
	 * @return  void
	 */
	protected function newDatabase()
	{
		$databaseName = $this->prompt('Database name: ');

		$changesConfirmed = $this->confirmChanges("About to add <info>{$databaseName}</info> to Homestead config.");
		if ($changesConfirmed === false) {
			return;
		}

		$this->homesteadConfig->addTo('databases', $databaseName);

		$this->homesteadConfigSave();
	}

}