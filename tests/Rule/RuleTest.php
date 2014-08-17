<?php
namespace ProjectLint\Test\Rule;

use org\bovigo\vfs\vfsStream;
use ProjectLint\Item\Item;
use ProjectLint\Report\Report;
use ProjectLint\Rule\Rule;
use ProjectLint\Test\ProjectLintTestCase;
use Symfony\Component\Finder\SplFileInfo;

class RuleTest extends ProjectLintTestCase
{
    public function extensionProvider()
    {
        $this->initializeMockFileSystem();

        $data = array();

        $data[] = array(
            'bin/check.sh',
            'item.extension == "sh"',
            true,
        );

        $data[] = array(
            'bin/check.sh',
            'item.extension == "php"',
            false,
        );

        return $data;
    }

    public function typeProvider()
    {
        $this->initializeMockFileSystem();

        $data = array();

        $data[] = array(
            'bin/check.sh',
            'item.type == "file"',
            true,
        );

        $data[] = array(
            'bin',
            'item.type == "file"',
            false,
        );

        return $data;
    }

    public function casesProvider()
    {
        // KLUDGE: Merge all providers data to work around PHPUnit limitation of one provider per test method
        return array_merge(
            $this->extensionProvider(),
            $this->typeProvider()
        );
    }

    /**
     * @test
     * @dataProvider casesProvider
     */
    public function checkRule($filePath, $expression, $shouldSucceed)
    {
        $file = new SplFileInfo(
            vfsStream::url('root/' . $filePath),
            dirname($filePath),
            basename($filePath)
        );

        $item   = new Item($file, vfsStream::url('root'));
        $report = new Report();
        $rule   = new Rule('Rule name', $expression);
        $rule->check($item, $report);

        $this->assertSame($shouldSucceed, !$report->getViolations());
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Unknown property: foo
     */
    public function checkRuleUsingInvalidItemPropertyThrowsAnException()
    {
        $file = new SplFileInfo(
            vfsStream::url('root/bin/check.sh'),
            'bin',
            'bin/check.sh'
        );

        $item   = new Item($file, vfsStream::url('root'));
        $report = new Report();
        $rule   = new Rule('Rule name', 'item.foo == "bar"');
        $rule->check($item, $report);
    }

    /**
     * @test
     * @expectedException Symfony\Component\ExpressionLanguage\SyntaxError
     * @expectedExceptionMessage Unexpected character """ around position 12.
     */
    public function checkRuleUsingInvalidExpressionThrowsAnException()
    {
        $file = new SplFileInfo(
            vfsStream::url('root/bin/check.sh'),
            'bin',
            'bin/check.sh'
        );

        $item   = new Item($file, vfsStream::url('root'));
        $report = new Report();

        // Note the missing double quote after bar
        $rule = new Rule('Rule name', 'item.foo == "bar');
        $rule->check($item, $report);
    }
}
