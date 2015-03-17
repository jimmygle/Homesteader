<?php namespace Homesteader\Config;

use Symfony\Component\Yaml\Yaml;

class HomesteadConfig
{

    protected $validTopLevelConfigKeys = ['keys', 'folders', 'sites', 'databases', 'variables'];

    protected $configFilePath;
    protected $configFileContents;
    protected $config;

    /**
     * Sets config file path
     *
     * @param string
     * @return \Homesteader\Config\HomesteadConfig
     */
    public function __construct($customConfigFilePath = null)
    {
        if ($customConfigFilePath !== null) {
            $this->configFilePath = $customConfigFilePath;
        } else {
            $this->findConfigFile();
        }
        $this->checkConfigFileExists();
        $this->checkConfigFileReadable();
        $this->parseConfigFileToArray();
    }

    /**
     * Finds the Homestead config file
     *
     * @return void
     */
    protected function findConfigFile()
    {
        if (isset($_SERVER['HOME'])) {
            $this->configFilePath = $_SERVER['HOME'] . DIRECTORY_SEPARATOR . '.homestead/Homestead.yaml';
        } else {
            $this->configFilePath = $_SERVER['HOMEDRIVE'] . $_SERVER['HOMEPATH'] . DIRECTORY_SEPARATOR . '.homestead/Homestead.yaml';
        }
    }

    /**
     * Verify config file exists
     *
     * @throws \ConfigFileIOException
     * @return void
     */
    protected function checkConfigFileExists()
    {
        if (file_exists($this->configFilePath) === false) {
            throw new \ConfigFileIOException("File does not exist: {$this->configFilePath}");
        }
    }

    /**
     * Verify config file is readable
     *
     * @throws \ConfigFileIOException
     * @return void
     */
    protected function checkConfigFileReadable()
    {
        if (is_readable($this->configFilePath) === false) {
            throw new \ConfigFileIOException("File is not readable: {$this->configFilePath}");
        }
    }

    /**
     * Loads homestead config file into an array
     *
     * @return void
     */
    protected function parseConfigFileToArray()
    {
        $this->readConfigFile();
        $this->config = Yaml::parse($this->configFileContents);
    }

    /**
     * Reads config file
     *
     * @throws \ConfigFileIOException
     * @return void
     */
    protected function readConfigFile()
    {
        $this->configFileContents = file_get_contents($this->configFilePath);
        if ($this->configFileContents == false) {
            throw new \ConfigFileIOException("Homestead config file is empty: {$this->configFilePath}");
        }
    }

    /**
     * Returns config as array
     *
     * @return array
     */
    public function asArray()
    {
        return $this->config;
    }

    /**
     * Returns config as raw string (YAML formatted)
     *
     * @return string
     */
    public function __toString()
    {
        return $this->asString();
    }

    /**
     * Returns config as raw string (YAML formatted)
     *
     * @return string
     */
    public function asString()
    {
        return $this->configFileContents;
    }

    /**
     * Adds nested config items within valid top level items
     *
     * @param $topLevelKey
     * @param $newConfigItem
     * @throws \ConfigFileInvalidKeyException
     * @internal param $string
     * @internal param $ string|array
     * @return void
     */
    public function addTo($topLevelKey, $newConfigItem)
    {
        if (in_array($topLevelKey, $this->validTopLevelConfigKeys) === false) {
            throw new \ConfigFileInvalidKeyException("Invalid top level key supplied: {$topLevelKey}");
        }
        $this->config[$topLevelKey][] = $newConfigItem;
    }

    /**
     * Converts config to YAML and then saves it to disk
     *
     * @return int Bytes written to disk
     */
    public function save()
    {
        $this->configFileContents = Yaml::dump($this->config, 3);
        $this->checkConfigFileWritable();
        return $this->writeConfigFile();
    }

    /**
     * Verify config file is writable
     *
     * @throws \ConfigFileIOException
     * @return void
     */
    protected function checkConfigFileWritable()
    {
        if ((bool)is_writable($this->configFilePath) === false) {
            throw new \ConfigFileIOException("File is not writable: {$this->configFilePath}");
        }
    }

    /**
     * Writes the config file to disk
     *
     * @throws \ConfigFileIOException
     * @return int Bytes written to disk
     */
    protected function writeConfigFile()
    {
        $bytesWrittenToDisk = file_put_contents($this->configFilePath, $this->configFileContents);
        return $bytesWrittenToDisk;
    }

}