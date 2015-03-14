<?php

use Homesteader\Config\HomesteadConfig;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamWrapper;
use org\bovigo\vfs\vfsStreamDirectory;

class ConfigHomesteadConfigTest extends \PHPUnit_Framework_TestCase {

    protected $homesteadConfigFile = '.homestead/Homestead.yaml';

    public function setUp()
    {
        vfsStreamWrapper::register();
        $root = new vfsStreamDirectory('.homestead');
        vfsStreamWrapper::setRoot($root);
        $this->homesteadConfigFile = vfsStream::url('.homestead/Homestead.yaml');
        $defaultHomesteadConfig = file_get_contents(dirname(__FILE__) . '/DefaultHomestead.yaml');
        file_put_contents($this->homesteadConfigFile, $defaultHomesteadConfig);
    }

    public function tearDown()
    {
        if (file_exists($this->homesteadConfigFile)) {
            unlink($this->homesteadConfigFile);
        }
    }

    public function testVirtualFilesystemSetupHappened()
    {
        $this->assertFileExists($this->homesteadConfigFile);
    }


    public function testConfigFileOpensAsString()
    {
        $configFile = new HomesteadConfig($this->homesteadConfigFile);
        $this->assertStringStartsWith('---', (string) $configFile);
        $this->assertStringStartsWith('---', $configFile->asString());
    }

    public function testConfigFileOpensAsArrayAndDataIsValid()
    {
        $configFile = new HomesteadConfig($this->homesteadConfigFile);
        $this->assertArrayHasKey('ip', $configFile->asArray());
        $this->assertArrayHasKey('memory', $configFile->asArray());
        $this->assertArrayHasKey('cpus', $configFile->asArray());
        $this->assertArrayHasKey('authorize', $configFile->asArray());
        $this->assertArrayHasKey('keys', $configFile->asArray());
        $this->assertArrayHasKey('folders', $configFile->asArray());
        $this->assertArrayHasKey('sites', $configFile->asArray());
        $this->assertArrayHasKey('databases', $configFile->asArray());
        $this->assertArrayHasKey('variables', $configFile->asArray());
    }

} 