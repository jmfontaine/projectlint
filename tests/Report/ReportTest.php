<?php
namespace ProjectLint\Test\Report;

use ProjectLint\Item\Item;
use ProjectLint\Report\Report;
use ProjectLint\Rule\Rule;
use Symfony\Component\Finder\SplFileInfo;

class ReportTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function violationsCanBeAdded()
    {
        $rule  = new Rule('Dummy Rule', 'item.type == file');
        $item  = new Item(new SplFileInfo('/a/fake/path/dummy.php', 'path/', 'path/dummy.php'), '/a/fake');

        $report = new Report();
        $report->addViolation($rule, $item);

        $violations = $report->getViolations();

        $this->assertCount(1, $violations);
        $this->assertTrue($report->hasViolations());
        $this->assertSame($rule, $violations[0]->getRule());
        $this->assertSame($item, $violations[0]->getItem());
    }
}
