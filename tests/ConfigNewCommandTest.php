<?php require_once __DIR__.'/ConfigSetup.php';

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Application;
use Homesteader\Config\ConfigNewCommand;

class ConfigNewCommandTest extends ConfigSetup {

    public function testItShouldOutputRawConfigFile()
    {
        $application = new Application();
        $application->add(new ConfigNewCommand());

        $command = $application->find('config:show');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['--file' => $this->homesteadConfigFilePath]);

        $this->assertStringStartsWith('---', $commandTester->getDisplay(), 'Homestead config file does not begin with "ip: "');
    }

}