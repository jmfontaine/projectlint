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

        // Absolute Path
        $data[] = array(
            'bin/check.sh',
            'item.absPath == "vfs://root/bin/check.sh"',
            true,
        );
        $data[] = array(
            'bin/check.sh',
            'item.absPath == "vfs://root/foo/check.sh"',
            false,
        );

        // Absolute Directory Path
        $data[] = array(
            'bin/check.sh',
            'item.absDir == "vfs://root/bin"',
            true,
        );
        $data[] = array(
            'bin/check.sh',
            'item.absDir == "vfs://root/foo"',
            false,
        );

        // Atime
        $data[] = array(
            'bin/check.sh',
            sprintf('item.atime == %d', mktime(23, 10, 23, 12, 14, 1977)),
            true,
        );
        $data[] = array(
            'bin/check.sh',
            sprintf('item.atime == %d', mktime(10, 42, 14, 11, 6, 1980)),
            false,
        );

        // Ctime
        $data[] = array(
            'bin/check.sh',
            sprintf('item.ctime == %d', mktime(23, 10, 23, 12, 14, 1977)),
            true,
        );
        $data[] = array(
            'bin/check.sh',
            sprintf('item.ctime == %d', mktime(10, 42, 14, 11, 6, 1980)),
            false,
        );

        // Depth
        $data[] = array(
            'bin/check.sh',
            'item.depth == 1',
            true,
        );
        $data[] = array(
            'bin/check.sh',
            'item.depth == 2',
            false,
        );
        $data[] = array(
            'vendor/psr/log/PSR/Log/InvalidArgumentException.php',
            'item.depth == 5',
            true,
        );

        // Extension
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

        // Group ID
        // KLUDGE: vfsStream doesn't allow using string as owner so we must rely on some magical constants
        $data[] = array(
            'bin/run.sh',
            sprintf('item.groupId == %d', vfsStream::GROUP_USER_1),
            true,
        );
        $data[] = array(
            'bin/run.sh',
            sprintf('item.groupId == %d', vfsStream::GROUP_USER_2),
            false,
        );

        // Mtime
        $data[] = array(
            'bin/check.sh',
            sprintf('item.mtime == %d', mktime(23, 10, 23, 12, 14, 1977)),
            true,
        );
        $data[] = array(
            'bin/check.sh',
            sprintf('item.mtime == %d', mktime(10, 42, 14, 11, 6, 1980)),
            false,
        );

        // Owner ID
        // KLUDGE: vfsStream doesn't allow using string as owner so we must rely on some magical constants
        $data[] = array(
            'bin/run.sh',
            sprintf('item.ownerId == %d', vfsStream::OWNER_USER_1),
            true,
        );
        $data[] = array(
            'bin/run.sh',
            sprintf('item.ownerId == %d', vfsStream::OWNER_USER_2),
            false,
        );

        // Name
        $data[] = array(
            'bin/check.sh',
            'item.name == "check.sh"',
            true,
        );
        $data[] = array(
            'bin/check.sh',
            'item.name == "foo.sh"',
            false,
        );

        // Path
        $data[] = array(
            'bin/check.sh',
            'item.path == "bin/check.sh"',
            true,
        );
        $data[] = array(
            'bin/check.sh',
            'item.path == "foo/check.sh"',
            false,
        );

        // Perms
        // KLUDGE: For now ProjectLint only supports integer permissions (Vs. octal representation)
        $data[] = array(
            'bin/check.sh',
            'item.perms == 33261',
            true,
        );
        $data[] = array(
            'bin/check.sh',
            'item.perms == 33206',
            false,
        );

        // Size
        $data[] = array(
            'bin/run.sh',
            'item.size <= 4096',
            true,
        );
        $data[] = array(
            'bin/run.sh',
            'item.size < 1024',
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
