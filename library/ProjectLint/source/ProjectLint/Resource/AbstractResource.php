<?php
namespace ProjectLint\Resource;

abstract class AbstractResource
{
    /**
     * File informations as SplFileInfo instance
     * @var SplFileInfo
     */
    protected $_fileInfo;

    protected $_projectPath;

    public function __construct(\SplFileInfo $fileInfo, $projectPath)
    {
        $this->_fileInfo    = $fileInfo;
        $this->_projectPath = $projectPath;
    }

    public function getAccessTime()
    {
        return $this->_fileInfo->getATime();
    }

    public function getGroup()
    {
        return $this->_fileInfo->getGroup();
    }

    public function getInode()
    {
        return $this->_fileInfo->getInode();
    }

    public function getInodeChangeTime()
    {
        return $this->_fileInfo->getCTime();
    }

    public function getModificationTime()
    {
        return $this->_fileInfo->getMTime();
    }

    public function getOwner()
    {
        return $this->_fileInfo->getOwner();
    }

    public function getPath()
    {
        return $this->_fileInfo->getPath();
    }

    public function getPathName()
    {
        return $this->_fileInfo->getPathname();
    }

    public function getPermissions()
    {
        return $this->_fileInfo->getPerms();
    }

    public function getRealPath()
    {
        return $this->_fileInfo->getRealPath();
    }

    public function getRelativePath()
    {
        $projectPathLength = strlen($this->_projectPath);
        return substr($this->_fileInfo->getRealPath(), $projectPathLength);
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