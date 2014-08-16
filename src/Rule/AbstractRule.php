<?php
namespace ProjectLint\Rule;

abstract class AbstractRule
{
    /**
     * @var array
     */
    private $exclude = array();

    /**
     * @var string
     */
    private $expression;

    /**
     * @var array
     */
    private $include = array();

    /**
     * @var string
     */
    private $name;

    public function __construct($name, $expression, $include, $exclude)
    {
    }
}
