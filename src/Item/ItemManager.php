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

    private function createRegexFromPath($path)
    {
        $quotedPath = preg_quote($path, '/');

        $replacements = array(
            '\*\*' => '.*',
            '\*'   => '[^\/]*',
        );
        $pattern = '/' . strtr($quotedPath, $replacements) . '/';

        return $pattern;
    }

    public function __construct($rootPath)
    {
        $this->setRootPath($rootPath);
    }

    public function getItems(array $include, array $exclude)
    {
        $finder = new Finder();
        $finder->in($this->getRootPath());

        foreach ($include as $path) {
            $finder->path($this->createRegexFromPath($path));
        }

        foreach ($exclude as $path) {
            $finder->notPath($this->createRegexFromPath($path));
        }

        $items = array();
        foreach ($finder as $resource) {
            $items[] = $this->loadItem($resource);
        }

        return $items;
    }
}
