<?php require_once __DIR__.'/ConfigSetup.php';

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Application;
use Homesteader\Config\ConfigListCommand;

class ConfigListCommandTest extends ConfigSetup {

    public function testItShouldOutputListOfOptions()
    {
        $application = new Application();
        $application->add(new ConfigListCommand());

        $command = $application->find('config');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            '--file' => $this->homesteadConfigFilePath
        ]);

        $this->assertStringStartsWith(PHP_EOL . 'The config commands allow for easy manipulation of the Homestead config file.', $commandTester->getDisplay());
    }

}