<?php

use Homestead\Config\HomesteadConfig;

class ConfigHomesteadConfigTest extends \PHPUnit_Framework_TestCase {
	
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