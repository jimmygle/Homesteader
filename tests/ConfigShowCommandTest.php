<?php

use Homesteader\Config\ConfigShowCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ConfigShowCommandTest extends \PHPUnit_Framework_TestCase {
	
	public function testItOutputsRawConfigFile()
	{
		$application = new Application();
		$application->add(new ConfigShowCommand());

		$command = $application->find('config:show');
		$commandTester = new CommandTester($command);
		$commandTester->execute([]);

		$this->assertStringStartsWith('ip: ', $commandTester->getDisplay(), 'Homestead config file does not begin with "ip: "');
	}

}