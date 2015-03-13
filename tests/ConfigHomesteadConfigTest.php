<?php

use Homesteader\Config\HomesteadConfig;
use org\bovigo\vfs\vfsStream;

class ConfigHomesteadConfigTest extends \PHPUnit_Framework_TestCase {

    protected $homesteadConfigFile;

    public function setUp()
    {
        $homesteadPath = '.homestead';
        vfsStream::setup($homesteadPath);
        $this->homesteadConfigFile = vfsStream::url("$homesteadPath/Homestead.yaml");
    }

    public function testConfigFileOpensAsString()
    {
        $configFile = new HomesteadConfig($this->homesteadConfigFile);
        $this->assertStringStartsWith('---', (string) $configFile);
        $this->assertStringStartsWith('---', $configFile->asString());
    }

    public function testConfigFileOpensAsArray()
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