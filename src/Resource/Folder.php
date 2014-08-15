<?php
namespace ProjectLint\Resource;

class Folder extends AbstractResource
{
    protected $children = array();

    public function getChildren()
    {
        return $this->children;
    }

    public function isFolder()
    {
        return true;
    }
}
