<?php
namespace ProjectLint\Resource;

abstract class AbstractResource
{
    /**
     * File informations as SplFileInfo instance
     * @var SplFileInfo
     */
    protected $fileInfo;

    protected $projectPath;

    public function __construct(\SplFileInfo $fileInfo, $projectPath)
    {
        $this->fileInfo    = $fileInfo;
        $this->projectPath = $projectPath;
    }

    public function getAccessTime()
    {
        return $this->fileInfo->getATime();
    }

    public function getGroup()
    {
        return $this->fileInfo->getGroup();
    }

    public function getInode()
    {
        return $this->fileInfo->getInode();
    }

    public function getInodeChangeTime()
    {
        return $this->fileInfo->getCTime();
    }

    public function getModificationTime()
    {
        return $this->fileInfo->getMTime();
    }

    public function getOwner()
    {
        return $this->fileInfo->getOwner();
    }

    public function getPath()
    {
        return $this->fileInfo->getPath();
    }

    public function getPathName()
    {
        return $this->fileInfo->getPathname();
    }

    public function getPermissions()
    {
        return $this->fileInfo->getPerms();
    }

    public function getRealPath()
    {
        return $this->fileInfo->getRealPath();
    }

    public function getRelativePath()
    {
        $projectPathLength = strlen($this->projectPath);
        return substr($this->fileInfo->getRealPath(), $projectPathLength);
    }

    public function isFile()
    {
        return false;
    }

    public function isFolder()
    {
        return false;
    }

    public function isLink()
    {
        return false;
    }
}
