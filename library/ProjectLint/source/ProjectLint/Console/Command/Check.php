<?php
namespace ProjectLint\Console\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console;
use ProjectLint\Rule\RulesManager;
use Symfony\Component\Finder\Finder;
use ProjectLint\Resource\Factory;

class Check extends Console\Command\Command
{
    protected function configure()
    {
        $this->setName('check')
             ->setDescription('Checks project structure')
             ->setDefinition(
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
             )->setHelp(
                 sprintf(
					'%sChecks project structure%s',
                 PHP_EOL,
                 PHP_EOL
                 )
             );
    }

    protected function execute(Console\Input\InputInterface $input,
        Console\Output\OutputInterface $output)
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