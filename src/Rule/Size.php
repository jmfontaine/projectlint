<?php
namespace ProjectLint\Rule;

use ProjectLint\Exception;
use ProjectLint\Resource\AbstractResource;
use ProjectLint\Resource\Factory;

class Size extends AbstractRule
{
    protected function checkFile(AbstractResource $resource)
    {
        $operator     = $this->extractOperator($this->data);
        $value        = $this->extractValue($this->data);
        $unit         = $this->extractUnit($this->data);
        $expectedSize = $this->convertSizeToBytes($value, $unit);
        $actualSize   = $resource->getSize(Factory::BYTE);

        $result = $this->evaluateResult($actualSize, $expectedSize, $operator);
        if (false === $result) {
            $this->addError(
                "File has not the required size",
                $operator . ' ' . $expectedSize,
                $actualSize,
                $resource
            );
        }
    }

    protected function checkFolder(AbstractResource $resource)
    {
        $operator      = $this->extractOperator($this->data);
        $value         = $this->extractValue($this->data);
        $childrenCount = count($resource->getChildren());

        $result = $this->evaluateResult($childrenCount, $value, $operator);
        if (false === $result) {
            $this->addError(
                "The folder has not the required number of children",
                $this->data,
                $childrenCount,
                $resource
            );
        }
    }

    protected function convertSizeToBytes($size, $unit)
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
                throw new Exception(
                    "Invalid unit provided : $unit"
                );
        }

        return $size;
    }

    protected function evaluateResult($value1, $value2, $operator)
    {
        // KLUDGE: Find a way to avoid using eval()
        return eval("return $value1 $operator $value2;");
    }

    protected function extractOperator($data)
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
            throw new Exception(
                "Invalid size comparison operator '$operator'"
            );
        }

        return $operator;
    }

    protected function extractUnit($data)
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
            throw new Exception(
                "Invalid unit '$unit'"
            );
        }

        return $unit;
    }

    protected function extractValue($data)
    {
        return preg_replace('/[^0-9]/', '', $data);
    }

    public function check(AbstractResource $resource)
    {
        if ($resource->isFolder()) {
            $this->checkFolder($resource);
        } else {
            $this->checkFile($resource);
        }
    }
}
