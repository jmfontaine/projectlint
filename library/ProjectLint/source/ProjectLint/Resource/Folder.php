<?php
namespace ProjectLint\Resource;

class Folder extends AbstractResource
{
    protected $_children = array();

    public function getChildren()
    {
        return $this->_children;
    }

    public function isFolder()
    {
        return true;
    }
}