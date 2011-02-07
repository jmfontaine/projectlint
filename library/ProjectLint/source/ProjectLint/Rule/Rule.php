<?php
namespace ProjectLint\Rule;

use ProjectLint\Resource\AbstractResource;

abstract class Rule
{
    protected $_data;
    protected $_rulesManager;
    protected $_selector;

    public function __construct(RulesManager $rulesManager,
        $selector, $data)
    {
        $this->setRulesManager($rulesManager);
        $this->setSelector($selector);
        $this->setData($data);
    }

    public function addError($message, $expectedValue, $actualValue,
        AbstractResource $resource) {
        $this->_rulesManager->addError(
            $message,
            $expectedValue,
            $actualValue,
            $resource
        );
    }

    abstract public function check(AbstractResource $resource);

    public function getData()
    {
        return $this->_data;
    }

    public function getSelector()
    {
        return $this->_selector;
    }

    public function getType()
    {
        return $this->_type;
    }

    public function setData($data)
    {
        $this->_data = $data;
        return $this;
    }

    public function setRulesManager(RulesManager $rulesManager)
    {
        $this->_rulesManager = $rulesManager;
        return $this;
    }

    public function setScope($scope)
    {
        $this->_scope = $scope;
        return $this;
    }

    public function setSelector($selector)
    {
        $this->_selector = $selector;
        return $this;
    }
}