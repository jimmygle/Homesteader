<?php require_once __DIR__.'/ConfigSetup.php';

use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Application;
use Homesteader\Config\ConfigNewCommand;
use Homesteader\Config\HomesteadConfig;

class ConfigNewCommandTest extends ConfigSetup {

    // @todo refactor these tests... lots of repetition

    protected $app;

    public function setUp()
    {
        parent::setUp();
        $this->app = new Application();
        $this->app->add(new ConfigNewCommand());
    }

    public function testItShouldCreateNewFolder()
    {
        $command = $this->app->find('config:new');
        $commandTester = new CommandTester($command);
        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream(
            "/Users/user/Projects/TestAppName\n" .
            "/home/vagrant/TestAppName\n" .
            "y\n"
        ));
        $commandTester->execute([
            'key' => 'folder',
            '--file' => $this->homesteadConfigFilePath
        ]);

        $savedConfigFile = new HomesteadConfig($this->homesteadConfigFilePath);
        $savedConfigFile = $savedConfigFile->asArray();

        $this->assertStringEndsWith("Homestead config file successfully updated.\n", $commandTester->getDisplay());
        $this->assertCount(9, $savedConfigFile);
        $this->assertCount(2, $savedConfigFile['folders']);
        $this->assertEquals('/Users/user/Projects/TestAppName', $savedConfigFile['folders'][1]['map']);
        $this->assertEquals('/home/vagrant/TestAppName', $savedConfigFile['folders'][1]['to']);
    }

    public function testItShouldCancelChangesWhenOnNewFolder()
    {
        $command = $this->app->find('config:new');
        $commandTester = new CommandTester($command);
        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream(
            "/Users/user/Projects/TestAppName\n" .
            "/home/vagrant/TestAppName\n" .
            "n\n"
        ));
        $commandTester->execute([
            'key' => 'folder',
            '--file' => $this->homesteadConfigFilePath
        ]);

        $savedConfigFile = new HomesteadConfig($this->homesteadConfigFilePath);
        $savedConfigFile = $savedConfigFile->asString();

        $this->assertStringEndsWith("Changes not applied. Exiting.\n", $commandTester->getDisplay());
        $this->assertEquals($this->getDefaultConfigFileContents(), $savedConfigFile);
    }

    public function testItShouldCreateNewFolderWithNoInteraction()
    {
        $command = $this->app->find('config:new');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'key' => 'folder',
            '--file' => $this->homesteadConfigFilePath,
            '--host' => '/Users/user/Projects/TestAppName',
            '--homestead' => '/home/vagrant/TestAppName'
        ], ['interactive' => false]);

        $savedConfigFile = new HomesteadConfig($this->homesteadConfigFilePath);
        $savedConfigFile = $savedConfigFile->asArray();

        $this->assertStringEndsWith("Homestead config file successfully updated.\n", $commandTester->getDisplay());
        $this->assertCount(9, $savedConfigFile);
        $this->assertCount(2, $savedConfigFile['folders']);
        $this->assertEquals('/Users/user/Projects/TestAppName', $savedConfigFile['folders'][1]['map']);
        $this->assertEquals('/home/vagrant/TestAppName', $savedConfigFile['folders'][1]['to']);
    }

    public function testItShouldCreateNewSite()
    {
        $command = $this->app->find('config:new');
        $commandTester = new CommandTester($command);
        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream(
            "test-app.local\n" .
            "/home/vagrant/test-app\n" .
            "y\n"
        ));
        $commandTester->execute([
            'key' => 'site',
            '--file' => $this->homesteadConfigFilePath
        ]);

        $savedConfigFile = new HomesteadConfig($this->homesteadConfigFilePath);
        $savedConfigFile = $savedConfigFile->asArray();

        $this->assertStringEndsWith("Homestead config file successfully updated.\n", $commandTester->getDisplay());
        $this->assertCount(9, $savedConfigFile);
        $this->assertCount(2, $savedConfigFile['sites']);
        $this->assertEquals('test-app.local', $savedConfigFile['sites'][1]['map']);
        $this->assertEquals('/home/vagrant/test-app', $savedConfigFile['sites'][1]['to']);
    }

    public function testItShouldCreateNewSiteWithNoInteraction()
    {
        $command = $this->app->find('config:new');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'key' => 'site',
            '--file' => $this->homesteadConfigFilePath,
            '--domain' => 'test-app.local',
            '--homestead' => '/home/vagrant/test-app'
        ], ['interactive' => false]);

        $savedConfigFile = new HomesteadConfig($this->homesteadConfigFilePath);
        $savedConfigFile = $savedConfigFile->asArray();

        $this->assertStringEndsWith("Homestead config file successfully updated.\n", $commandTester->getDisplay());
        $this->assertCount(9, $savedConfigFile);
        $this->assertCount(2, $savedConfigFile['sites']);
        $this->assertEquals('test-app.local', $savedConfigFile['sites'][1]['map']);
        $this->assertEquals('/home/vagrant/test-app', $savedConfigFile['sites'][1]['to']);
    }

    public function testItShouldCancelChangesWhenOnNewSite()
    {
        $command = $this->app->find('config:new');
        $commandTester = new CommandTester($command);
        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream(
            "test-app.local\n" .
            "/home/vagrant/test-app\n" .
            "n\n"
        ));
        $commandTester->execute([
            'key' => 'site',
            '--file' => $this->homesteadConfigFilePath
        ]);

        $savedConfigFile = new HomesteadConfig($this->homesteadConfigFilePath);
        $savedConfigFile = $savedConfigFile->asString();

        $this->assertStringEndsWith("Changes not applied. Exiting.\n", $commandTester->getDisplay());
        $this->assertEquals($this->getDefaultConfigFileContents(), $savedConfigFile);
    }

    public function testItShouldCreateNewVariable()
    {
        $command = $this->app->find('config:new');
        $commandTester = new CommandTester($command);
        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream(
            "TEST_VAR\n" .
            "test_value\n" .
            "y\n"
        ));
        $commandTester->execute([
            'key' => 'variable',
            '--file' => $this->homesteadConfigFilePath
        ]);

        $savedConfigFile = new HomesteadConfig($this->homesteadConfigFilePath);
        $savedConfigFile = $savedConfigFile->asArray();

        $this->assertStringEndsWith("Homestead config file successfully updated.\n", $commandTester->getDisplay());
        $this->assertCount(9, $savedConfigFile);
        $this->assertCount(2, $savedConfigFile['variables']);
        $this->assertEquals('TEST_VAR', $savedConfigFile['variables'][1]['key']);
        $this->assertEquals('test_value', $savedConfigFile['variables'][1]['value']);
    }

    public function testItShouldCreateNewVariableWithNoInteraction()
    {
        $command = $this->app->find('config:new');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'key' => 'variable',
            '--file' => $this->homesteadConfigFilePath,
            '--key' => 'TEST_VAR',
            '--value' => 'test_value'
        ], ['interactive' => false]);

        $savedConfigFile = new HomesteadConfig($this->homesteadConfigFilePath);
        $savedConfigFile = $savedConfigFile->asArray();

        $this->assertStringEndsWith("Homestead config file successfully updated.\n", $commandTester->getDisplay());
        $this->assertCount(9, $savedConfigFile);
        $this->assertCount(2, $savedConfigFile['variables']);
        $this->assertEquals('TEST_VAR', $savedConfigFile['variables'][1]['key']);
        $this->assertEquals('test_value', $savedConfigFile['variables'][1]['value']);
    }

    public function testItShouldCancelChangesWhenOnNewVariable()
    {
        $command = $this->app->find('config:new');
        $commandTester = new CommandTester($command);
        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream(
            "TEST_VAR\n" .
            "test_value\n" .
            "n\n"
        ));
        $commandTester->execute([
            'key' => 'variable',
            '--file' => $this->homesteadConfigFilePath
        ]);

        $savedConfigFile = new HomesteadConfig($this->homesteadConfigFilePath);
        $savedConfigFile = $savedConfigFile->asString();

        $this->assertStringEndsWith("Changes not applied. Exiting.\n", $commandTester->getDisplay());
        $this->assertEquals($this->getDefaultConfigFileContents(), $savedConfigFile);
    }

    public function testItShouldCreateNewDatabase()
    {
        $command = $this->app->find('config:new');
        $commandTester = new CommandTester($command);
        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream(
            "test_db\n" .
            "y\n"
        ));
        $commandTester->execute([
            'key' => 'database',
            '--file' => $this->homesteadConfigFilePath
        ]);

        $savedConfigFile = new HomesteadConfig($this->homesteadConfigFilePath);
        $savedConfigFile = $savedConfigFile->asArray();

        $this->assertStringEndsWith("Homestead config file successfully updated.\n", $commandTester->getDisplay());
        $this->assertCount(9, $savedConfigFile);
        $this->assertCount(2, $savedConfigFile['databases']);
        $this->assertEquals('test_db', $savedConfigFile['databases'][1]);
    }

    public function testItShouldCreateNewDatabaseWithNoInteraction()
    {
        $command = $this->app->find('config:new');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'key' => 'database',
            '--file' => $this->homesteadConfigFilePath,
            '--name' => 'test_db'
        ], ['interactive' => false]);

        $savedConfigFile = new HomesteadConfig($this->homesteadConfigFilePath);
        $savedConfigFile = $savedConfigFile->asArray();

        $this->assertStringEndsWith("Homestead config file successfully updated.\n", $commandTester->getDisplay());
        $this->assertCount(9, $savedConfigFile);
        $this->assertCount(2, $savedConfigFile['databases']);
        $this->assertEquals('test_db', $savedConfigFile['databases'][1]);
    }

    public function testItShouldCancelChangesWhenOnNewDatabase()
    {
        $command = $this->app->find('config:new');
        $commandTester = new CommandTester($command);
        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream(
            "test_db\n" .
            "n\n"
        ));
        $commandTester->execute([
            'key' => 'database',
            '--file' => $this->homesteadConfigFilePath
        ]);

        $savedConfigFile = new HomesteadConfig($this->homesteadConfigFilePath);
        $savedConfigFile = $savedConfigFile->asString();

        $this->assertStringEndsWith("Changes not applied. Exiting.\n", $commandTester->getDisplay());
        $this->assertEquals($this->getDefaultConfigFileContents(), $savedConfigFile);
    }

    public function testItShouldHandleInvalidKeyEntry()
    {
        $command = $this->app->find('config:new');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'key' => 'ksdfsl',
            '--file' => $this->homesteadConfigFilePath
        ]);

        $this->assertStringStartsWith(PHP_EOL . "Adds new config options to the Homestead config file.", $commandTester->getDisplay());
    }

    public function testItShouldHandleNoKeyEntry()
    {
        $command = $this->app->find('config:new');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            '--file' => $this->homesteadConfigFilePath
        ]);

        $this->assertStringStartsWith(PHP_EOL . "Adds new config options to the Homestead config file.", $commandTester->getDisplay());
    }

}