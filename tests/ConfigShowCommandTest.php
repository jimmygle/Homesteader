<?php require_once __DIR__.'/ConfigSetup.php';

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Application;
use Homesteader\Config\ConfigShowCommand;

class ConfigShowCommandTest extends ConfigSetup {
	
	public function testItShouldOutputRawConfigFile()
	{
		$application = new Application();
		$application->add(new ConfigShowCommand());

		$command = $application->find('config:show');
		$commandTester = new CommandTester($command);
		$commandTester->execute(['--file' => $this->homesteadConfigFilePath]);

		$this->assertStringStartsWith('---', $commandTester->getDisplay(), 'Homestead config file does not begin with "ip: "');
	}

}