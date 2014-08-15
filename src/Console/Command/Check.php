<?php
namespace ProjectLint\Console\Command;

use ProjectLint\Resource\Factory;
use ProjectLint\Rule\RulesManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Finder\Finder;

class Check extends Command
{
    protected function configure()
    {
        $this->setName('check')
             ->setDescription('Checks project structure')
             ->setHelp(PHP_EOL . 'Checks project structure' . PHP_EOL);

        $this->setDefinition(
            array(
                new InputArgument(
                    'ruleset',
                    InputArgument::REQUIRED,
                    'Ruleset path'
                ),
                new InputOption(
                    'project-path',
                    'p',
                    InputOption::VALUE_REQUIRED,
                    'Project path',
                    getcwd()
                ),
            )
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $projectPath = $input->getOption('project-path');

        // Load ruleset
        $rulesManager = new RulesManager($projectPath);
        $rulesManager->loadFromFile($input->getArgument('ruleset'));

        // Iterate over project
        $finder = new Finder();
        $finder->in($projectPath)->sortByName();
        foreach ($finder as $item) {
            $resource = Factory::create($item, $projectPath);
            $rulesManager->checkRules($resource);
        }

        // Display errors
        $errors = $rulesManager->getErrors();
        foreach ($errors as $error) {
            $output->writeln(
                sprintf(
                    "%s: %s (Expected: %s, actual: %s)\n",
                    $error['resource']->getRelativePath(),
                    $error['message'],
                    $error['expectedValue'],
                    $error['actualValue']
                )
            );
        }
    }
}
