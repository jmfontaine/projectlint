<?php
namespace ProjectLint\Report\Renderer;

use ProjectLint\Report\Report;
use Symfony\Component\Console\Output\OutputInterface;

class TextRenderer
{
    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @return OutputInterface
     */
    private function getOutput()
    {
        return $this->output;
    }

    /**
     * @param OutputInterface $output
     *
     * @return $this
     */
    private function setOutput($output)
    {
        $this->output = $output;

        return $this;
    }

    public function __construct(OutputInterface $output)
    {
        $this->setOutput($output);
    }

    public function render(Report $report)
    {
        $output = $this->getOutput();

        foreach ($report->getViolations() as $violation) {
            $text = sprintf(
                'Rule "%s" violation: <info>%s</info>',
                $violation->getRule()->getName(),
                $violation->getItem()->getRelativePathname()
            );
            $output->writeln($text);
        }
    }
}
