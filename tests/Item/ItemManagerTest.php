<?php
namespace ProjectLint\Test\Item;

use ProjectLint\Item\ItemManager;
use ProjectLint\Test\ProjectLintTestCase;

class ItemManagerTest extends ProjectLintTestCase
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


    public function setUp()
    {
        $this->setItemManager(new ItemManager($this->getMockFileSystemRootPath()));
    }

    public function combinationsProvider()
    {
        $data = array();

        // Simple paths
        $data[] = array(
            array(),
            array(),
            array(
                'bin',
                'bin/check.sh',
                'bin/run.sh',
                'build',
                'src',
                'src/Item',
                'src/Item/Item.php',
                'src/Item/ItemManager.php',
                'vendor',
                'vendor/autoload.php',
                'vendor/composer',
                'vendor/composer/autoload_classmap.php',
                'vendor/composer/autoload_namespaces.php',
                'vendor/composer/autoload_psr4.php',
                'vendor/composer/autoload_real.php',
                'vendor/composer/ClassLoader.php',
                'vendor/composer/installed_paths.php',
                'vendor/composer/installed.json',
                'vendor/psr',
                'vendor/psr/log',
                'vendor/psr/log/composer.json',
                'vendor/psr/log/LICENSE',
                'vendor/psr/log/README.md',
                'vendor/psr/log/Psr',
                'vendor/psr/log/Psr/Log',
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
                'bin',
                'bin/check.sh',
                'bin/run.sh',
                'build',
            ),
        );

        $data[] = array(
            array('src'),
            array(),
            array(
                'src',
                'src/Item',
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
                'bin',
                'bin/check.sh',
                'bin/run.sh',
                'src/Item/Item.php'
            ),
        );

        $data[] = array(
            array(
                'vendor/**.php',
            ),
            array(),
            array(
                'vendor/autoload.php',
                'vendor/composer/autoload_classmap.php',
                'vendor/composer/autoload_namespaces.php',
                'vendor/composer/autoload_psr4.php',
                'vendor/composer/autoload_real.php',
                'vendor/composer/ClassLoader.php',
                'vendor/composer/installed_paths.php',
                'vendor/psr/log/Psr/Log/InvalidArgumentException.php',
                'vendor/psr/log/Psr/Log/LoggerInterface.php',
                'vendor/psr/log/Psr/Log/LogLevel.php',
                'vendor/psr/log/Psr/Log/NullLogger.php',
            ),
        );

        // Wildcards
        $data[] = array(
            array(
                'vendor/*.php',
            ),
            array(),
            array(
                'vendor/autoload.php',
            ),
        );

        $data[] = array(
            array(
                'vendor/**.php',
            ),
            array(
                'vendor/composer'
            ),
            array(
                'vendor/autoload.php',
                'vendor/psr/log/Psr/Log/InvalidArgumentException.php',
                'vendor/psr/log/Psr/Log/LoggerInterface.php',
                'vendor/psr/log/Psr/Log/LogLevel.php',
                'vendor/psr/log/Psr/Log/NullLogger.php',
            ),
        );

        $data[] = array(
            array(
                'vendor/**.json',
            ),
            array(
                'vendor/**composer.json'
            ),
            array(
                'vendor/composer/installed.json',
            ),
        );

        // Regular Expressions
        $data[] = array(
            array(
                '/vendor\/.*\.php/',
            ),
            array(
                'vendor/composer'
            ),
            array(
                'vendor/autoload.php',
                'vendor/psr/log/Psr/Log/InvalidArgumentException.php',
                'vendor/psr/log/Psr/Log/LoggerInterface.php',
                'vendor/psr/log/Psr/Log/LogLevel.php',
                'vendor/psr/log/Psr/Log/NullLogger.php',
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
