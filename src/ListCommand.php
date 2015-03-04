<?php namespace Homesteader;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Yaml\Yaml;

class ListCommand extends Command {
	
	protected function configure()
	{
		$this->setName('list')->setDescription('List current Homestead configurations.');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$rawConfig = file_get_contents('/Users/jimmygle/.homestead/Homestead.yaml');
		$configContents = Yaml::parse($rawConfig);
		$table = (new Table($output));
		$tableRows = [];
		foreach ($configContents as $configKey => $configValue) {
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