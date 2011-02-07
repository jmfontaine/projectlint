<?php
namespace ProjectLint\Rule;

use ProjectLint\Resource\Factory;
use ProjectLint\Resource\AbstractResource;

class Size extends Rule
{
    protected function _checkFile(AbstractResource $resource)
    {
        $operator     = $this->_extractOperator($this->_data);
        $value        = $this->_extractValue($this->_data);
        $unit         = $this->_extractUnit($this->_data);
        $expectedSize = $this->_convertSizeToBytes($value, $unit);
        $actualSize   = $resource->getSize(Factory::BYTE);

        $result = $this->_evaluateResult($actualSize, $expectedSize, $operator);
        if (false === $result) {
            $this->addError(
                "File has not the required size",
                $operator . ' ' . $expectedSize,
                $actualSize,
                $resource
            );
        }
    }

    protected function _checkFolder(AbstractResource $resource)
    {
        $operator      = $this->_extractOperator($this->_data);
        $value         = $this->_extractValue($this->_data);
        $childrenCount = count($resource->getChildren());

        $result = $this->_evaluateResult($childrenCount, $value, $operator);
        if (false === $result) {
            $this->addError(
                "The folder has not the required number of children",
                $this->_data,
                $childrenCount,
                $resource
            );
        }
    }

    protected function _convertSizeToBytes($size, $unit)
    {
        switch ($unit) {
            case '':
            case 'B':
                // Do nothing
                break;

            case 'kB':
                $size = $size * 1024;
                break;

            case 'MB':
                $size = $size * 1024 * 1024;
                break;

            case 'GB':
                $size = $size * 1024 * 1024 * 1024;
                break;

            default:
                throw new ProjectLint_Exception(
                    "Invalid unit provided : $unit"
                );
        }

        return $size;
    }

    protected function _evaluateResult($value1, $value2, $operator)
    {
        // KLUDGE: Find a way to avoid using eval()
        return eval("return $value1 $operator $value2;");
    }

    protected function _extractOperator($data)
    {
        // Extract operator by removing non operator characters
        $operator = preg_replace('/[^<>=\!]/', '', $data);

        // Check operator
        $validOperators = array(
        	'=',
            '==',
            '!=',
            '<>',
            '<',
            '<=',
            '>',
            '>=',
        );
        if (!in_array($operator, $validOperators)) {
            throw new ProjectLint_Exception(
                "Invalid size comparison operator '$operator'"
            );
        }

        return $operator;
    }

    protected function _extractUnit($data)
    {
        // Extract unit by removing non unit characters
        $unit = preg_replace('/[^BkMG]/', '', $data);

        // Check unit
        $validUnits = array(
        	'',
            'B',
            'kB',
            'MB',
            'GB',
        );
        if (!in_array($unit, $validUnits)) {
            throw new ProjectLint_Exception(
                "Invalid unit '$unit'"
            );
        }

        return $unit;
    }

    protected function _extractValue($data)
    {
        return preg_replace('/[^0-9]/', '', $data);
    }

    public function check(AbstractResource $resource)
    {
        if ($resource->isFolder()) {
            $this->_checkFolder($resource);
        } else {
            $this->_checkFile($resource);
        }
    }
}