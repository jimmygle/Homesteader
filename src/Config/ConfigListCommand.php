<?php namespace Homesteader\Config;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Yaml\Yaml;
use Homesteader\Config\HomesteadConfig;

class ConfigListCommand extends Command {
	
	protected $homesteadConfig;

	protected function configure()
	{
		$this->setName('config:list')->setDescription('List current Homestead config settings.');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$this->homesteadConfig = new HomesteadConfig;

		$table = (new Table($output));
		$tableRows = [];
		foreach ($this->homesteadConfig->asArray() as $configKey => $configValue) {
			$tableRow = [$configKey];
			if (is_array($configValue)) {
				$tableCell = '';
				foreach ($configValue as $k => $v) {
					if (is_array($v)) {
						foreach ($v as $l => $q) {
							$tableCell .= $q . ' => ';
						}
						$tableCell = rtrim($tableCell, ' => ');
					} else {
						$tableCell .= $v;
					}
				}
				array_push($tableRow, $tableCell);
			} else {
				array_push($tableRow, $configValue);
			}
			$tableRows[] = $tableRow;
		}
		$table->setRows($tableRows)->render();
	}

}