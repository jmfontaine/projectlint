<?php
class ProjectLint_RulesIterator implements RecursiveIterator
{
    protected $_children = array();
    protected $_data     = array();
    protected $_position = 0;

    public function __construct(array $data)
    {
        $this->setData($data);
    }

	public function current()
	{
	    return $this->_data[$this->_position];
	}

	public function getChildren()
	{
	    return $this->_children;
	}

    public function hasChildren()
	{
	    return !empty($this->_children);
	}

	public function key()
	{
	    return $this->_position;
	}

	public function next()
	{
	    $this->_position++;
	}

	public function rewind()
	{
	    $this->_position = 0;
	}

	public function setData(array $data)
	{
	    if (array_key_exists('children', $data)) {
	        $this->_children = $data['children'];
	        unset($data['children']);
	    }

	    foreach ($data as $key => $value) {
	        $this->_data[] = array($key => $value);
	    }

        $this->rewind();
	}

	public function valid()
	{
        return isset($this->_data[$this->_position]);
	}
}