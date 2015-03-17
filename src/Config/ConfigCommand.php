<?php namespace Homesteader\Config;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class ConfigCommand extends Command
{

    protected $input;
    protected $output;
    protected $homesteadConfig;
    protected $questionHelper;

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
     */
    protected function prompt($promptText, $optionKeyOfDefault = null, $isRequired = false)
    {
        $answer = $this->getDefaultAnswerFromOption($optionKeyOfDefault);

        if ($this->input->isInteractive()) {
            $answer = $this->displayPromptAndGetAnswerFromInput($promptText, $answer);
        }

        if ($isRequired === true && $answer == false) {
            throw new \MissingValueException('Value required for prompt: "' . $promptText . '"');
        }

        return $answer;
    }

    /**
     * Gets default value from option if it's set
     *
     * @param string
     * @return mixed
     */
    protected function getDefaultAnswerFromOption($option)
    {
        try {
            return $this->input->getOption($option);
        } catch (\InvalidArgumentException $e) {
            return false;
        }
    }

    /**
     * Display prompt and get answer from input
     *
     * @param string
     * @param string|bool
     * @return mixed
     */
    protected function displayPromptAndGetAnswerFromInput($promptText, $answer)
    {
        if ($answer != false) {
            $promptText = $promptText . '[' . $answer . ']: ';
        }
        $prompt = new Question($promptText, $answer);
        return $this->questionHelper->ask($this->input, $this->output, $prompt);
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
            return (bool)$this->questionHelper->ask($this->input, $this->output, $confirmation);
        }
        return true;
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