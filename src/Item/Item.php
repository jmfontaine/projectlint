<?php
namespace ProjectLint\Item;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\SplFileInfo;

class Item
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * Resource
     *
     * @var SplFileInfo
     */
    private $resource;

    /**
     * @var string
     */
    private $rootPath;

    /**
     * @return SplFileInfo
     */
    private function getResource()
    {
        return $this->resource;
    }

    /**
     * @param SplFileInfo $resource
     *
     * @return $this
     */
    private function setResource(SplFileInfo $resource)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * @return Filesystem
     */
    private function getFilesystem()
    {
        return $this->filesystem;
    }

    /**
     * @param Filesystem $filesystem
     *
     * @return $this
     */
    private function setFilesystem(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;

        return $this;
    }

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

    public function __construct(SplFileInfo $resource, $rootPath)
    {
        $this->setResource($resource)
             ->setRootPath($rootPath)
             ->setFilesystem(new Filesystem());
    }

    /**
     * @param string $propertyName
     */
    public function __get($propertyName)
    {
        switch ($propertyName) {
            case 'atime':
                $propertyValue = $this->getResource()->getATime();
                break;
            case 'ctime':
                $propertyValue = $this->getResource()->getCTime();
                break;
            case 'extension':
                $propertyValue = $this->getResource()->getExtension();
                break;
            case 'group':
                $propertyValue = $this->getResource()->getGroup();
                break;
            case 'mtime':
                $propertyValue = $this->getResource()->getMTime();
                break;
            case 'owner':
                $propertyValue = $this->getResource()->getOwner();
                break;
            case 'perms':
                $propertyValue = $this->getResource()->getPerms();
                break;
            case 'size':
                $propertyValue = $this->getResource()->getSize();
                break;
            case 'type':
                $propertyValue = $this->getResource()->getType();
                break;

            default:
                throw new \InvalidArgumentException('Unknown property: ' . $propertyName);
        }

        return $propertyValue;
    }

    public function getRelativePathname()
    {
        $relativePath = $this->getFilesystem()->makePathRelative(
            $this->getResource()->getPath(),
            $this->getRootPath()
        );

        return $relativePath . $this->getResource()->getFilename();
    }

    public function getName()
    {
        return $this->getResource()->getFilename();
    }

    public function getPath()
    {
        return $this->getResource()->getPath();
    }

    public function getPathname()
    {
        return $this->getResource()->getPathname();
    }
}
