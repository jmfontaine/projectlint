<?php
namespace ProjectLint\ExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage as BaseExpressionLanguage;

class ExpressionLanguage extends BaseExpressionLanguage
{
    protected function registerFunctions()
    {
        parent::registerFunctions();

        $this->register('lowercase', function ($str) {
            return sprintf('(is_string(%1$s) ? strtolower(%1$s) : %1$s)', $str);
        }, function ($arguments, $str) {
            if (!is_string($str)) {
                return $str;
            }

            return strtolower($str);
        });
    }
}
