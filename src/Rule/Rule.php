<?php
namespace ProjectLint\Rule;

use ProjectLint\Item\Item;
use ProjectLint\Report\Report;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class Rule
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
     * @var ExpressionLanguage
     */
    private $language;

    /**
     * @var string
     */
    private $name;

    /**
     * @param array $exclude
     *
     * @return $this
     */
    private function setExclude(array $exclude)
    {
        $this->exclude = $exclude;

        return $this;
    }

    /**
     * @param string $expression
     *
     * @return $this
     */
    private function setExpression($expression)
    {
        $this->expression = $expression;

        return $this;
    }

    /**
     * @param array $include
     *
     * @return $this
     */
    private function setInclude(array $include)
    {
        $this->include = $include;

        return $this;
    }

    /**
     * @return ExpressionLanguage
     */
    private function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param ExpressionLanguage $language
     *
     * @return $this
     */
    private function setLanguage(ExpressionLanguage $language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    private function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function __construct($name, $expression, array $include, array $exclude)
    {
        $this->setName($name)
             ->setExpression($expression)
             ->setInclude($include)
             ->setExclude($exclude)
             ->setLanguage(new ExpressionLanguage());
    }

    /**
     * @return array
     */
    public function getExclude()
    {
        return $this->exclude;
    }

    /**
     * @return string
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * @return array
     */
    public function getInclude()
    {
        return $this->include;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function check(Item $item, Report $report)
    {
        $result = $this->getLanguage()->evaluate(
            $this->getExpression(),
            array('item' => $item)
        );

        if (false === $result) {
            $report->addViolation($this, $item);
        }

        return $result;
    }
}
