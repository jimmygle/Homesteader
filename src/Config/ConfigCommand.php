<?php namespace Homesteader\Config;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Homesteader\Config\HomesteadConfig;

class ConfigCommand extends Command {

	protected $input;
	protected $output;
	protected $homesteadConfig;
	protected $questionHelper;

    /**
     * Add command configuration available to all config commands
     *
     * @return void
     */
    protected function configure()
    {
        $this->addOption('file', 'f', InputOption::VALUE_OPTIONAL, 'Specifies custom path to Homestead config file.', null);
    }

	/**
	 * Initializer for all config commands
	 *
	 * @param  Symfony\Component\Console\Input\InputInterface
	 * @param  Symfony\Component\Console\Output\OutputInterface
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$this->input = $input;
		$this->output = $output;
		$this->homesteadConfig = new HomesteadConfig($input->getOption('file'));
		$this->questionHelper = $this->getHelper('question');
	}

	/**
	 * Prompts for input
	 *
	 * @param  string
	 * @return  string
	 */
	protected function prompt($promptText)
	{
		$prompt = new Question($promptText);
		return $this->questionHelper->ask($this->input, $this->output, $prompt);
	}

	/**
	 * Outputs summary of changes and prompts for confirmation
	 *
	 * @param  string
	 * @param  string
	 * @return  bool
	 */
	protected function confirmChanges($changeSummary, $confirmationText = 'Continue? [y/n] ')
	{
		$this->output->writeln($changeSummary);
		$confirmation = new ConfirmationQuestion($confirmationText, false);
		return (bool) $this->questionHelper->ask($this->input, $this->output, $confirmation);
	}

	/**
	 * Attempts to save the homestead config file
	 *
	 * @return  void
	 */
	protected function homesteadConfigSave()
	{
		try {
			$this->homesteadConfig->save();
		} catch (Exception $e) {
			$this->output->writeln("<error>{$e->getMessage()}</error>");
		}

		$this->output->writeln('<info>Homestead config file successfully updated.</info>');
	}

}