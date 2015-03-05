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
	 * @return  void
	 */
	public function __construct($customConfigFilePath = null)
	{
		if ($customConfigFilePath !== null) {
			$this->configFilePath = $customConfigFilePath;
		} else {
			$this->findConfigFile();
		}
		$this->checkConfigFileExists();
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
			$this->configFilePath = $_SERVER['HOME'] . '/.homestead/Homestead.yaml';
		} else {
			$this->configFilePath = $_SERVER['HOMEDRIVE'] . $_SERVER['HOMEPATH'] . DIRECTORY_SEPARATOR . './homestead/Homestead.yaml';
		}
	}

	/**
	 * Verify config file exists
	 *
	 * @return  void
	 */
	protected function checkConfigFileExists()
	{
		if (file_exists($this->configFilePath) === false) {
			throw new Exception("File does not exist: {$this->configFilePath}");
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
		return $this->writeConfigFile();
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
			throw new Exception("Unable to write to file: {$this->configFilePath}");
		}
		return $bytesWrittenToDisk;
	}

}