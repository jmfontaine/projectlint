<?php
namespace ProjectLint\Item;

use Symfony\Component\Finder\Finder;

class ItemManager
{
    /**
     * @var array
     */
    private $cache = array();

    /**
     * @var string
     */
    private $rootPath;

    /**
     * @return string
     */
    private function getRootPath()
    {
        return $this->rootPath;
    }

    /**
     * @param string $rootPath
     *
     * @return $this
     */
    private function setRootPath($rootPath)
    {
        $this->rootPath = $rootPath;

        return $this;
    }

    private function loadItem($resource)
    {
        return new Item($resource);
    }

    public function __construct($rootPath)
    {
        $this->setRootPath($rootPath);
    }

    public function getItems(array $include, array $exclude)
    {
        $finder = new Finder();
        $finder->files()
               ->in($this->getRootPath());

        foreach ($include as $path) {
            $finder->path($path);
        }

        foreach ($exclude as $path) {
            $finder->notPath($path);
        }

        $items = array();
        foreach ($finder as $resource) {
            $items[] = $this->loadItem($resource);
        }

        return $items;
    }
}
