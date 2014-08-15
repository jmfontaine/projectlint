<?php
namespace ProjectLint\Resource\File;

use ProjectLint\Exception;

class Content
{
    protected $rawContent;

    public function findPattern($pattern)
    {
        $pattern = "/$pattern/";

        return $this->findRegexPattern($pattern);
    }

    public function findRegexPattern($pattern)
    {
        $result = preg_match($pattern, $this->rawContent);

        if (false === $result) {
            throw new Exception("Could not use pattern '$pattern'");
        }

        return $result;
    }

    public function getRawContent()
    {
        return $this->rawContent;
    }
}
