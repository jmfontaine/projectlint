<?php
namespace ProjectLint\Test\Report;

use ProjectLint\Item\Item;
use ProjectLint\Report\Violation;
use ProjectLint\Rule\Rule;
use Symfony\Component\Finder\SplFileInfo;

class ViolationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function argumentsPassedToConstructorCanBeRetrievedViaGetters()
    {
        $rule = new Rule('Dummy Rule', 'item.type == file');
        $item = new Item(new SplFileInfo('/a/fake/path/dummy.php', 'path/', 'path/dummy.php'), '/a/fake');

        $violation = new Violation($rule, $item);

        $this->assertSame($rule, $violation->getRule());
        $this->assertSame($item, $violation->getItem());
    }
}
