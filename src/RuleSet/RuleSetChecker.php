<?php
namespace ProjectLint\RuleSet;

use ProjectLint\Item\ItemManager;
use ProjectLint\Report\Report;

class RuleSetChecker
{
    /**
     * @var ItemManager
     */
    private $itemManager;

    private function checkRule(Rule $rule, Report$report)
    {
        $items = $this->getItemManager()->getItems(
            $rule->getInclude(),
            $rule->getExclude()
        );

        foreach ($items as $item) {
            $rule->check($item, $report);
        }
    }

    /**
     * @return ItemManager
     */
    private function getItemManager()
    {
        return $this->itemManager;
    }

    /**
     * @param ItemManager $itemManager
     *
     * @return $this
     */
    private function setItemManager($itemManager)
    {
        $this->itemManager = $itemManager;

        return $this;
    }

    public function __construct(ItemManager $itemManager)
    {
        $this->setItemManager($itemManager);
    }

    /**
     * @param RuleSet $ruleSet
     *
     * @return Report
     */
    public function check(RuleSet $ruleSet)
    {
        $report = new Report();

        foreach ($ruleSet->getRules() as $rule) {
            $this->checkRule($rule, $report);
        }

        return $report;
    }
}
