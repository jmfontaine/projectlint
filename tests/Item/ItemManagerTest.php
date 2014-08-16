<?php
namespace ProjectLint\Test\Item;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamFile;
use ProjectLint\Item\ItemManager;

class ItemManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ItemManager
     */
    private $itemManager;

    /**
     * @return ItemManager
     */
    private function getItemManager()
    {
        return $this->itemManager;
    }

    /**
     * @param ItemManager $itemManager
     *
     * @return $this
     */
    private function setItemManager(ItemManager $itemManager)
    {
        $this->itemManager = $itemManager;

        return $this;
    }

    private function createBinDirectory(vfsStreamDirectory $rootDirectory)
    {
        $directory = new vfsStreamDirectory('bin');
        $rootDirectory->addChild($directory);

        $directory->addChild(new vfsStreamFile('check.sh'));
        $directory->addChild(new vfsStreamFile('run.sh'));
    }

    private function createBuildDirectory(vfsStreamDirectory $rootDirectory)
    {
        $directory = new vfsStreamDirectory('build');
        $rootDirectory->addChild($directory);
    }

    private function createSrcDirectory(vfsStreamDirectory $rootDirectory)
    {
        $directory = new vfsStreamDirectory('src');
        $rootDirectory->addChild($directory);

        $subDirectory = new vfsStreamDirectory('Item');
        $directory->addChild($subDirectory);
        $subDirectory->addChild(new vfsStreamFile('Item.php'));
        $subDirectory->addChild(new vfsStreamFile('ItemManager.php'));
    }

    private function createVendorDirectory(vfsStreamDirectory $rootDirectory)
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

    public function setUp()
    {
        $rootDirectory = vfsStream::setup('root');

        $this->createBinDirectory($rootDirectory);
        $this->createBuildDirectory($rootDirectory);
        $this->createSrcDirectory($rootDirectory);
        $this->createVendorDirectory($rootDirectory);

        $rootPath = vfsStream::url($rootDirectory->getName());
        $this->setItemManager(new ItemManager($rootPath));
    }

    public function combinationsProvider()
    {
        $data = array();

        $data[] = array(
            array(),
            array(),
            array(
                'bin/check.sh',
                'bin/run.sh',
                'src/Item/Item.php',
                'src/Item/ItemManager.php',
                'vendor/autoload.php',
                'vendor/composer/autoload_classmap.php',
                'vendor/composer/autoload_namespaces.php',
                'vendor/composer/autoload_psr4.php',
                'vendor/composer/autoload_real.php',
                'vendor/composer/ClassLoader.php',
                'vendor/composer/installed_paths.php',
                'vendor/composer/installed.json',
                'vendor/psr/log/composer.json',
                'vendor/psr/log/LICENSE',
                'vendor/psr/log/README.md',
                'vendor/psr/log/Psr/Log/InvalidArgumentException.php',
                'vendor/psr/log/Psr/Log/LoggerInterface.php',
                'vendor/psr/log/Psr/Log/LogLevel.php',
                'vendor/psr/log/Psr/Log/NullLogger.php',
            ),
        );

        $data[] = array(
            array(),
            array(
                'src',
                'vendor',
            ),
            array(
                'bin/check.sh',
                'bin/run.sh',
            ),
        );

        $data[] = array(
            array('src'),
            array(),
            array(
                'src/Item/Item.php',
                'src/Item/ItemManager.php',
            ),
        );

        $data[] = array(
            array(
                'bin',
                'src/Item/Item.php',
            ),
            array(),
            array(
                'bin/check.sh',
                'bin/run.sh',
                'src/Item/Item.php'
            ),
        );

        return $data;
    }

    /**
     * @test
     * @dataProvider combinationsProvider
     */
    public function variousCombinationsOfIncludedAndExcludedPathsWork(
        array $include,
        array $exclude,
        array $expectedPaths
    ) {
        $items = $this->getItemManager()->getItems($include, $exclude);

        $actualPaths = array();
        foreach ($items as $item) {
            $actualPaths[] = $item->getRelativePathname();
        }

        $this->assertSame($expectedPaths, $actualPaths);
    }
}
