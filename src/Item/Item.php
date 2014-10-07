<?php
namespace ProjectLint\Item;

use Hal\Component\Token\Tokenizer;
use Hal\Metrics\Complexity\Text\Length\Loc;
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
            case 'absPath':
                $propertyValue = $this->getResource()->getPathname();
                break;
            case 'absDir':
                $propertyValue = dirname($this->getResource()->getPathname());
                break;
            case 'atime':
                $propertyValue = $this->getResource()->getATime();
                break;
            case 'contents':
                $propertyValue = $this->getResource()->getContents();
                break;
            case 'ctime':
                $propertyValue = $this->getResource()->getCTime();
                break;
            case 'depth':
                $relativePath  = dirname($this->getRelativePathname());
                $parts         = explode('/', $relativePath);
                $propertyValue = count($parts);
                break;
            case 'extension':
                $propertyValue = $this->getResource()->getExtension();
                break;
            case 'group':
                $groupData = posix_getgrgid($this->getResource()->getGroup());
                $propertyValue = $groupData['name'];
                break;
            case 'groupId':
                $propertyValue = $this->getResource()->getGroup();
                break;
            case 'loc':
                $resource = $this->getResource();
                if ('php' == $resource->getExtension()) {
                    $tokenizer     = new Tokenizer();
                    $locAnalyzer   = new Loc($tokenizer);
                    $info          = $locAnalyzer->calculate($resource->getPathname());
                    $propertyValue = $info->getLoc();
                } else {
                    $propertyValue = null;
                }
                break;
            case 'mtime':
                $propertyValue = $this->getResource()->getMTime();
                break;
            case 'name':
                $propertyValue = $this->getResource()->getFilename();
                break;
            case 'owner':
                $ownerData = posix_getpwuid($this->getResource()->getOwner());
                $propertyValue = $ownerData['name'];
                break;
            case 'ownerId':
                $propertyValue = $this->getResource()->getOwner();
                break;
            case 'path':
                $propertyValue = $this->getRelativePathname();
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

        // Strip leading "./" if present
        if ('./' === substr($relativePath, 0, 2)) {
            if ('./' === $relativePath) {
                $relativePath = '';
            } else {
                $relativePath = substr($relativePath, 2);
            }
        }

        return $relativePath . $this->getResource()->getFilename();
    }
}
