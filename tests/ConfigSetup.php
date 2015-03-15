<?php require_once __DIR__.'/../vendor/autoload.php';

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamWrapper;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamContent;

class ConfigSetup extends \PHPUnit_Framework_TestCase
{

    const VFS_ROOT = '.homestead';
    const VFS_CONFIG_FILENAME = 'Homestead.yaml';
    const DEFAULT_CONFIG_FILENAME = 'DefaultHomestead.yaml'; // assumed same directory as this class

    protected $homesteadConfigFilePath;
    protected $homesteadConfigFile;
    protected $rootDirectory;

    public function setUp()
    {
        $this->createVirtualFilesystem();
        $this->createMockConfigFileFromDefault();
    }

    protected function createVirtualFilesystem()
    {
        vfsStreamWrapper::register();
        $this->rootDirectory = new vfsStreamDirectory(static::VFS_ROOT);
        vfsStreamWrapper::setRoot($this->rootDirectory);
    }

    protected function createMockConfigFileFromDefault()
    {
        $this->homesteadConfigFilePath = vfsStream::url(static::VFS_ROOT . '/' . static::VFS_CONFIG_FILENAME);
        $this->homesteadConfigFile = vfsStream::newFile(static::VFS_CONFIG_FILENAME)->at($this->rootDirectory)->setContent($this->getDefaultConfigFileContents());
    }

    protected function getDefaultConfigFileContents()
    {
        return file_get_contents(__DIR__ . '/' . static::DEFAULT_CONFIG_FILENAME);
    }

    public function testMockConfigFileExists()
    {
        $this->assertFileExists($this->homesteadConfigFilePath);
    }

    public function testMockConfigFileContentsAreRight()
    {
        $this->assertEquals($this->getDefaultConfigFileContents(), file_get_contents($this->homesteadConfigFilePath));
    }

    public function tearDown()
    {
        if (file_exists($this->homesteadConfigFilePath)) {
            unlink($this->homesteadConfigFilePath);
        }
    }

    protected function getInputStream($input)
    {
        $stream = fopen('php://memory', 'r+', false);
        fputs($stream, $input);
        rewind($stream);
        return $stream;
    }

}