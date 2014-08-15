<?php
namespace ProjectLint\Rule;

use ProjectLint\Resource\AbstractResource;

abstract class AbstractRule
{
    protected $data;

    protected $rulesManager;

    protected $selector;

    public function __construct(RulesManager $rulesManager, $selector, $data)
    {
        $this->setRulesManager($rulesManager);
        $this->setSelector($selector);
        $this->setData($data);
    }

    public function addError($message, $expectedValue, $actualValue, AbstractResource $resource)
    {
        $this->rulesManager->addError(
            $message,
            $expectedValue,
            $actualValue,
            $resource
        );
    }

    abstract public function check(AbstractResource $resource);

    public function getData()
    {
        return $this->data;
    }

    public function getSelector()
    {
        return $this->selector;
    }

    public function getType()
    {
        return $this->_type;
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function setRulesManager(RulesManager $rulesManager)
    {
        $this->rulesManager = $rulesManager;
        return $this;
    }

    public function setScope($scope)
    {
        $this->_scope = $scope;
        return $this;
    }

    public function setSelector($selector)
    {
        $this->selector = $selector;
        return $this;
    }
}
