<?php
namespace ProjectLint\Report;

use ProjectLint\Item\Item;
use ProjectLint\Rule\Rule;

class Violation
{
    /**
     * @var Item
     */
    private $item;

    /**
     * @var Rule
     */
    private $rule;

    /**
     * @param Item $item
     *
     * @return $this
     */
    private function setItem(Item $item)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * @param Rule $rule
     *
     * @return $this
     */
    private function setRule(Rule $rule)
    {
        $this->rule = $rule;

        return $this;
    }

    public function __construct(Rule $rule, Item $item)
    {
        $this->setRule($rule)
             ->setItem($item);
    }

    /**
     * @return Item
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @return Rule
     */
    public function getRule()
    {
        return $this->rule;
    }
}
