<?php
namespace ProjectLint\Test;

use org\bovigo\vfs\content\LargeFileContent;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamFile;

abstract class ProjectLintTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var vfsStreamDirectory
     */
    protected $mockFileSystem;

    /**
     * @var string
     */
    protected $mockFileSystemRootPath;

    protected function initializeMockFileSystem()
    {
        if (null !== $this->mockFileSystem) {
            return;
        }

        $rootDirectory = vfsStream::setup('root');

        $this->createBinDirectory($rootDirectory);
        $this->createBuildDirectory($rootDirectory);
        $this->createSrcDirectory($rootDirectory);
        $this->createVendorDirectory($rootDirectory);

        $this->mockFileSystem         = $rootDirectory;
        $this->mockFileSystemRootPath = vfsStream::url($rootDirectory->getName());
    }

    protected function createBinDirectory(vfsStreamDirectory $rootDirectory)
    {
        $directory = new vfsStreamDirectory('bin');
        $rootDirectory->addChild($directory);

        $checkFile = new vfsStreamFile('check.sh', 0755);
        $checkFile->lastAccessed(mktime(23, 10, 23, 12, 14, 1977))
                  ->lastAttributeModified(mktime(23, 10, 23, 12, 14, 1977))
                  ->lastModified(mktime(23, 10, 23, 12, 14, 1977));
        $directory->addChild($checkFile);

        $runFile = new vfsStreamFile('run.sh');
        $runFile->chown(vfsStream::OWNER_USER_1)
                ->chgrp(vfsStream::GROUP_USER_1)
                ->withContent(LargeFileContent::withKilobytes(3));

        $directory->addChild($runFile);
    }

    protected function createBuildDirectory(vfsStreamDirectory $rootDirectory)
    {
        $directory = new vfsStreamDirectory('build');
        $rootDirectory->addChild($directory);
    }

    protected function createSrcDirectory(vfsStreamDirectory $rootDirectory)
    {
        $directory = new vfsStreamDirectory('src');
        $rootDirectory->addChild($directory);

        $subDirectory = new vfsStreamDirectory('Item');
        $directory->addChild($subDirectory);
        $subDirectory->addChild(new vfsStreamFile('Item.php'));
        $subDirectory->addChild(new vfsStreamFile('ItemManager.php'));
    }

    protected function createVendorDirectory(vfsStreamDirectory $rootDirectory)
    {
        $directory = new vfsStreamDirectory('vendor');
        $rootDirectory->addChild($directory);

        $directory->addChild(new vfsStreamFile('autoload.php'));

        $subDirectory = new vfsStreamDirectory('composer');
        $directory->addChild($subDirectory);
        $subDirectory->addChild(new vfsStreamFile('autoload_classmap.php'));
        $subDirectory->addChild(new vfsStreamFile('autoload_namespaces.php'));
        $subDirectory->addChild(new vfsStreamFile('autoload_psr4.php'));
        $subDirectory->addChild(new vfsStreamFile('autoload_real.php'));
        $subDirectory->addChild(new vfsStreamFile('ClassLoader.php'));
        $subDirectory->addChild(new vfsStreamFile('installed_paths.php'));
        $subDirectory->addChild(new vfsStreamFile('installed.json'));

        $subDirectory = new vfsStreamDirectory('psr');
        $directory->addChild($subDirectory);
        $secondLevelSubDirectory = new vfsStreamDirectory('log');
        $subDirectory->addChild($secondLevelSubDirectory);
        $secondLevelSubDirectory->addChild(new vfsStreamFile('.gitignore'));
        $secondLevelSubDirectory->addChild(new vfsStreamFile('composer.json'));
        $secondLevelSubDirectory->addChild(new vfsStreamFile('LICENSE'));
        $secondLevelSubDirectory->addChild(new vfsStreamFile('README.md'));
        $thirdLevelSubDirectory = new vfsStreamDirectory('Psr');
        $secondLevelSubDirectory->addChild($thirdLevelSubDirectory);
        $fourthLevelSubDirectory = new vfsStreamDirectory('Log');
        $thirdLevelSubDirectory->addChild($fourthLevelSubDirectory);
        $fourthLevelSubDirectory->addChild(new vfsStreamFile('InvalidArgumentException.php'));
        $fourthLevelSubDirectory->addChild(new vfsStreamFile('LoggerInterface.php'));
        $fourthLevelSubDirectory->addChild(new vfsStreamFile('LogLevel.php'));
        $fourthLevelSubDirectory->addChild(new vfsStreamFile('NullLogger.php'));
    }

    /**
     * @return vfsStreamDirectory
     */
    protected function getMockFileSystem()
    {
        if (null === $this->mockFileSystem) {
            $this->initializeMockFileSystem();
        }

        return $this->mockFileSystem;
    }

    /**
     * @return string
     */
    protected function getMockFileSystemRootPath()
    {
        if (null === $this->mockFileSystemRootPath) {
            $this->initializeMockFileSystem();
        }

        return $this->mockFileSystemRootPath;
    }
}
