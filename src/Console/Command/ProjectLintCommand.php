<?php
namespace ProjectLint\Console\Command;

use ProjectLint\Item\ItemManager;
use ProjectLint\Report\Renderer\TextRenderer;
use ProjectLint\Rule\RuleSet;
use ProjectLint\Rule\RuleSetChecker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Logger\ConsoleLogger;

class ProjectLintCommand extends Command
{
    protected function configure()
    {
        $this->setName('projectlint')
             ->setDescription('Checks project structure')
             ->setHelp(PHP_EOL . 'Checks project layout against a ruleset' . PHP_EOL)
             ->addArgument('path', InputArgument::OPTIONAL, 'Project path', 'Current directory');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $logger = new ConsoleLogger($output);

        $projectPath = $input->getArgument('path');
        if ('Current directory' == $projectPath) {
            $projectPath = getcwd();
        }

        $ruleSet     = new RuleSet($projectPath . '/projectlint.yml', $logger);
        $itemManager = new ItemManager($projectPath);

        $checker = new RuleSetChecker($itemManager);
        $report  = $checker->check($ruleSet);

        $renderer = new TextRenderer($output);
        $renderer->render($report);

        // Set exit code
        return $report->hasViolations() ? 1 : 0;
    }
}
