<?php
namespace ProjectLint\Item;

use Symfony\Component\Finder\SplFileInfo;

class Item
{
    /**
     * Resource
     *
     * @var SplFileInfo
     */
    private $resource;

    /**
     * @return \SplFileInfo
     */
    private function getResource()
    {
        return $this->resource;
    }

    /**
     * @param \SplFileInfo $resource
     *
     * @return $this
     */
    private function setResource(SplFileInfo $resource)
    {
        $this->resource = $resource;

        return $this;
    }

    public function __construct(SplFileInfo $resource)
    {
        $this->setResource($resource);
    }

    /**
     * @param string $name
     */
    public function __get($name)
    {
        switch ($name) {
            case 'extension':
                $value = $this->getResource()->getExtension();
                break;
            case 'type':
                $value = $this->getResource()->getType();
                break;

            default:
                throw new \InvalidArgumentException('Unknown property:' . $name);
        }

        return $value;
    }

    public function getRelativePathname()
    {
        return $this->getResource()->getRelativePathname();
    }
}
