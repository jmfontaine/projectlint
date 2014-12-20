<?php
namespace ProjectLint\Report;

use ProjectLint\Item\Item;
use ProjectLint\Rule\Rule;

class Report
{
    /**
     * @var array
     */
    private $violations = [];

    public function addViolation(Rule $rule, Item $item)
    {
        $this->violations[] = new Violation($rule, $item);
    }

    /**
     * @return array
     */
    public function getViolations()
    {
        return $this->violations;
    }

    public function hasViolations()
    {
        return !empty($this->violations);
    }
}
