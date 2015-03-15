<?php namespace Homesteader\Config;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigNewCommand extends ConfigCommand {

	/**
	 * Configure command
	 *
	 * @return void
	 */
	protected function configure()
	{
        parent::configure();
		$this->setName('config:new');
		$this->setDescription('Add a new item to the Homestead config.');
		$this->addArgument('key', InputArgument::OPTIONAL, 'Type of config to add. [folder|site|database|variable]');
	    $this->addOption('host', null, InputArgument::OPTIONAL, 'Path on your local machine.');
        $this->addOption('homestead', null, InputArgument::OPTIONAL, 'Path in Homestead\'s filesystem.');
        $this->addOption('domain', null, InputArgument::OPTIONAL, 'Local domain name of site.');
        $this->addOption('key', null, InputArgument::OPTIONAL, 'Name of variable.');
        $this->addOption('value', null, InputArgument::OPTIONAL, 'Value of variable.');
        $this->addOption('name', null, InputArgument::OPTIONAL, 'Database name.');
    }

    /**
     * Initialize the command and determine which path to take
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
			case 'variable':
				$this->newVariable();
				break;
            case 'list':
                $this->listKeys();
                break;
			default:
				$helpCommand = $this->getApplication()->find('config:new');
                $input = new ArrayInput(['key' => 'list', '--file' => $input->getOption('file')]);
                $helpCommand->run($input, $output);
				break;
		}
	}

	/**
	 * Add new folders config set to config file
	 *
	 * Command:
	 * homesteader config:new folder
	 *
	 * @return void
	 */
	protected function newFolder()
	{
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

	/**
	 * Add new sites config set to config file
	 *
	 * Command:
	 * homesteader config:new site
	 *
	 * @return void
	 */
	protected function newSite()
	{
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

	/**
	 * Add new variables config set to config file
	 *
	 * Command:
	 * homesteader config:new var|variable
	 *
	 * @return void
	 */
	protected function newVariable()
	{
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

	/**
	 * Add new database config file
	 *
	 * Command:
	 * homesteader config:new database
	 *
	 * @return void
	 */
	protected function newDatabase()
	{
		$databaseName = $this->prompt('Database name: ', 'name', true);

		$changesConfirmed = $this->confirmChanges("About to add <info>{$databaseName}</info> to Homestead config.");
		if ($changesConfirmed === false) {
            $this->outputChangesCanceled();
			return;
		}

		$this->homesteadConfig->addTo('databases', $databaseName);

		$this->homesteadConfigSave();
	}

    protected function listKeys()
    {
        $this->output->writeln('');
        $this->output->writeln('Adds new config options to the Homestead config file.');
        $this->output->writeln('');
        $this->output->writeln('  Available options are included with example values.');
        $this->output->writeln('');
        $this->output->writeln('  <comment>config:new folder</comment>       Adds synced directory set');
        $this->output->writeln('    <info>--host</info>                /path/to/host/dir');
        $this->output->writeln('    <info>--homestead</info>           /path/on/homestead/to/dir');
        $this->output->writeln('');
        $this->output->writeln('  <comment>config:new site</comment>         Adds site and directory');
        $this->output->writeln('    <info>--domain</info>              domaintoadd.com');
        $this->output->writeln('    <info>--homestead</info>           /path/on/homestead/to/public/');
        $this->output->writeln('');
        $this->output->writeln('  <comment>config:new variable</comment>     Adds variable');
        $this->output->writeln('    <info>--key</info>                 VAR');
        $this->output->writeln('    <info>--value</info>               VALUE');
        $this->output->writeln('');
        $this->output->writeln('  <comment>config:new database</comment>     Adds database');
        $this->output->writeln('    <info>--name</info>                db_name');
        $this->output->writeln('');
        $this->output->writeln('  Add -n for no interaction.');
        $this->output->writeln('');
    }

}