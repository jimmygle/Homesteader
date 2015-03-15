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
     * @param InputInterface $input
     * @param OutputInterface $output
     * @internal param $ Symfony\Component\Console\Input\InputInterface
     * @internal param $ Symfony\Component\Console\Output\OutputInterface
     * @return void
     */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$this->input = $input;
		$this->output = $output;
		$this->homesteadConfig = new HomesteadConfig($input->getOption('file'));
		$this->questionHelper = $this->getHelper('question');
	}

    /**
     * Prompts for input if interaction enabled with option name default
     *
     * @param $promptText
     * @param string
     * @param bool $isRequired
     * @throws \MissingValueException
     * @internal param $bool
     * @return string
     * @todo  refactor this
     */
	protected function prompt($promptText, $optionKeyOfDefault = null, $isRequired = false)
	{
        try {
            $defaultAnswer = $this->input->getOption($optionKeyOfDefault);
        } catch (\InvalidArgumentException $e) {
            $defaultAnswer = false;
        }

        if ($this->input->isInteractive()) {
            if ($defaultAnswer != false) {
                $promptText = $promptText . '[' . $defaultAnswer . ']: ';
            }
            $prompt = new Question($promptText);
            $answer = $this->questionHelper->ask($this->input, $this->output, $prompt);
        } else {
            $answer = $defaultAnswer;
        }

        if ($isRequired === true && $answer == false) {
            throw new \MissingValueException('Value required for prompt: "' . $promptText . '"');
        }

        return $answer;
	}

	/**
	 * Outputs summary of changes and prompts for confirmation, or assumes yes if non-interactive
	 *
	 * @param  string
	 * @param  string
	 * @return  bool
	 */
	protected function confirmChanges($changeSummary, $confirmationText = 'Continue? [y/n] ')
	{
        if ($this->input->isInteractive()) {
            $this->output->writeln($changeSummary);
            $confirmation = new ConfirmationQuestion($confirmationText, false);
            return (bool) $this->questionHelper->ask($this->input, $this->output, $confirmation);
        }
        return true;
	}

    /**
     * Outputs message of change confirmation being canceled
     *
     * @return void
     */
    public function outputChangesCanceled()
    {
        $this->output->writeln("<info>Changes not applied. Exiting.</info>");
    }

	/**
	 * Attempts to save the homestead config file
	 *
	 * @return void
	 */
	protected function homesteadConfigSave()
	{
		$this->homesteadConfig->save();

		$this->output->writeln('<info>Homestead config file successfully updated.</info>');
	}

}