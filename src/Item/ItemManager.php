<?php
namespace ProjectLint\Item;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class ItemManager
{
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
        $this->rootPath = rtrim($rootPath, '/');

        return $this;
    }

    private function loadItem($resource)
    {
        return new Item($resource, $this->getRootPath());
    }

    public function __construct($rootPath)
    {
        $this->setRootPath($rootPath);
    }

    public function getItems(array $include, array $exclude)
    {
        $finder = new Finder();
        $finder->files();

        $isolatedFiles = array();

        if (empty($include)) {
            $finder->in($this->getRootPath());
        } else {
            foreach ($include as $path) {
                if (is_file($path)) {
                    $isolatedFiles[] = new SplFileInfo($this->getRootPath() . '/' . $path, dirname($path), $path);
                } else {
                    $finder->in($this->getRootPath() . '/' . $path);
                }
            }
        }

        foreach ($exclude as $path) {
            $finder->exclude($path);
        }

        $finder->append($isolatedFiles);

        $items = array();
        foreach ($finder as $resource) {
            $items[] = $this->loadItem($resource);
        }

        return $items;
    }
}
