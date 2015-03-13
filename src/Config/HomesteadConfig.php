<?php namespace Homesteader\Config;

use Symfony\Component\Yaml\Yaml;

class HomesteadConfig {

	protected $validTopLevelConfigKeys = ['keys', 'folders', 'sites', 'databases', 'variables'];

	protected $configFilePath;
	protected $configFileContents;
	protected $config;

    /**
     * Sets config file path
     *
     * @param  string
     * @throws Exception
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
	 * @return  void
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
	 * @return  void
	 */
	protected function checkConfigFileExists()
	{
        echo file_get_contents($this->configFilePath);
		if (file_exists($this->configFilePath) === false) {
			throw new Exception("File does not exist: {$this->configFilePath}");
		}
	}

	/**
	 * Verify config file is readable
	 *
	 * @return  void
	 */
	protected function checkConfigFileReadable()
	{
		if (is_readable($this->configFilePath) === false) {
			throw new Exception("File is not readable: {$this->configFilePath}");
		}
	}

	/**
	 * Loads homestead config file into an array
	 *
	 * @return  void
	 */
	protected function parseConfigFileToArray()
	{
		$this->readConfigFile();
		$this->config = Yaml::parse($this->configFileContents);
	}

	/**
	 * Reads config file
	 *
	 * @return  void
	 */
	protected function readConfigFile()
	{
		$this->configFileContents = file_get_contents($this->configFilePath);
		if ($this->configFileContents === false) {
			throw new Exception("Unable to read homestead config file: {$this->configFilePath}");
		}
		if ($this->configFileContents == false) {
			throw new Exception("Contents of homestead config file are empty: {$this->configFilePath}");
		}
	}

	/**
	 * Returns config as array
	 *
	 * @return  array
	 */
	public function asArray()
	{
		return $this->config;
	}

	/**
	 * Returns config as raw string (YAML formatted)
	 *
	 * @return  string
	 */
	public function __toString()
	{
		return $this->asString();
	}

	/**
	 * Returns config as raw string (YAML formatted)
	 *
	 * @return  string
	 */
	public function asString()
	{
		return $this->configFileContents;
	}

	/**
	 * Adds nested config items within valid top level items
	 *
	 * @param  string
	 * @param  string|array
	 * @return  void
	 */
	public function addTo($topLevelKey, $newConfigItem)
	{
		if (in_array($topLevelKey, $this->validTopLevelConfigKeys) === false) {
			throw new Exception("Invalid top level key supplied: {$topLevelKey}");
		}
		$this->config[$topLevelKey][] = $newConfigItem;
		return;
	}

	/**
	 * Converts config to YAML and then saves it to disk
	 *
	 * @return  int  bytes written to disk
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
	 * @return  void
	 */
	protected function checkConfigFileWritable()
	{
		if ((bool) is_writable($this->configFilePath) === false) {
			throw new Exception("File is not writable: {$this->configFilePath}");
		}
	}

	/**
	 * Writes the config file to disk
	 *
	 * @return int  bytes written to disk
	 */
	protected function writeConfigFile()
	{
		$bytesWrittenToDisk = file_put_contents($this->configFilePath, $this->configFileContents, LOCK_EX);
		if ($bytesWrittenToDisk === false) {
			throw new Exception("Config was not saved to file: {$this->configFilePath}");
		}
		return $bytesWrittenToDisk;
	}

}