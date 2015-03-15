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

    public function testItShouldThrowExceptionForConfigFileNotFound()
    {
        $this->setExpectedException('ConfigFileIOException', 'File does not exist: invalidFileName.yaml');
        new HomesteadConfig('invalidFileName.yaml');
    }

    // @todo  this is not a very good way to test -- figure out a better way
    public function testItShouldHandleDefaultConfigFile()
    {
        if (@file_exists($_SERVER['HOME'] . DIRECTORY_SEPARATOR . '.homestead/Homestead.yaml') OR @file_exists($_SERVER['HOMEDRIVE'] . $_SERVER['HOMEPATH'] . DIRECTORY_SEPARATOR . '.homestead/Homestead.yaml')) {
            // default config file exists
            $configFile = new HomesteadConfig();
            $this->assertArrayHasKey('ip', $configFile->asArray());
            $this->assertArrayHasKey('memory', $configFile->asArray());
            $this->assertArrayHasKey('cpus', $configFile->asArray());
            $this->assertArrayHasKey('authorize', $configFile->asArray());
            $this->assertArrayHasKey('keys', $configFile->asArray());
            $this->assertArrayHasKey('folders', $configFile->asArray());
            $this->assertArrayHasKey('sites', $configFile->asArray());
            $this->assertArrayHasKey('databases', $configFile->asArray());
            $this->assertArrayHasKey('variables', $configFile->asArray());
        } else {
            // default config file does not exist on filesystem
            $this->setExpectedException('ConfigFileIOException');
            new HomesteadConfig();
        }
    }

    public function testItShouldThrowExceptionForAddingInvalidKey()
    {
        $this->setExpectedException('ConfigFileInvalidKeyException');
        $configFile = new HomesteadConfig($this->homesteadConfigFilePath);
        $configFile->addTo('bogus', 'foobar');
    }

    public function testItShouldThrowExceptionOnUnreadableFile()
    {
        $this->homesteadConfigFile->chmod(000);
        $this->setExpectedException('ConfigFileIOException', "File is not readable: {$this->homesteadConfigFilePath}");
        new HomesteadConfig($this->homesteadConfigFilePath);
    }

    public function testItShouldThrowExceptionOnUnwritableFile()
    {
        $this->homesteadConfigFile->chmod(0444);
        $this->setExpectedException('ConfigFileIOException', "File is not writable: {$this->homesteadConfigFilePath}");
        $configFile = new HomesteadConfig($this->homesteadConfigFilePath);
        $configFile->save();
    }

    public function testItShouldThrowExceptionOnEmptyConfigFile()
    {
        $this->homesteadConfigFile->setContent('');
        $this->setExpectedException('ConfigFileIOException', "Homestead config file is empty: {$this->homesteadConfigFilePath}");
        new HomesteadConfig($this->homesteadConfigFilePath);
    }

// @todo  Add tests for addTo() method
// @todo  Add tests for save() method

} 