<?php 

use Homesteader\Config\ConfigNewCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ConfigNewCommandTest extends \PHPUnit_Framework_TestCase {

    public function testNewFolderCommandAddsFolderToConfigFile()
    {
//        $application = new Application();
//        $application->add(new ConfigNewCommand());
//
//        $command = $application->find('config:new');
//        $commandTester = new CommandTester($command);
//        $commandTester->execute(['folder']);

        // $this->assertStringStartsWith('ip: ', $commandTester->getDisplay(), 'Homestead config file does not begin with "ip: "');
    }

}
