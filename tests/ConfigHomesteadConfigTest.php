<?php require_once __DIR__.'/ConfigSetup.php';

use Homesteader\Config\HomesteadConfig;

class ConfigHomesteadConfigTest extends ConfigSetup {

    public function testConfigFileOpensAsString()
    {
        $configFile = new HomesteadConfig($this->homesteadConfigFilePath);
        $this->assertStringStartsWith('---', (string) $configFile);
        $this->assertStringStartsWith('---', $configFile->asString());
    }

    public function testConfigFileOpensAsArrayAndDataIsValid()
    {
        $configFile = new HomesteadConfig($this->homesteadConfigFilePath);
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

// @todo  Add tests for addTo() method
// @todo  Add tests for save() method

} 