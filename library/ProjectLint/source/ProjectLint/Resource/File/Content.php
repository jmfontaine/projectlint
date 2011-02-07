<?php
class ProjectLint_Resource_File_Content
{
    protected $_rawContent;

    public function findPattern($pattern)
    {
        $pattern = "/$pattern/";

        return $this->findRegexPattern($pattern);
    }

    public function findRegexPattern($pattern)
    {
        $result = preg_match($pattern, $this->_rawContent);

        if (false === $result) {
            throw new ProjectLint_Exception(
            	"Could not use pattern '$pattern'"
            );
        }

        return $result;
    }

    public function getRawContent()
    {
        return $this->_rawContent;
    }
}