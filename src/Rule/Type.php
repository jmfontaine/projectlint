<?php
namespace ProjectLint\Rule;

use ProjectLint\Exception;
use ProjectLint\Resource\AbstractResource;
use ProjectLint\Rule\AbstractRule;

class Type extends AbstractRule
{
    public function check(AbstractResource $resource)
    {
        $message       = '';
        $expectedClass = '';
        $actualClass   = substr(get_class($resource), 21);

        switch($this->data) {
            case 'folder':
                if ('Folder' != $actualClass) {
                    $message       = 'Resource is not a folder';
                    $expectedClass = 'Folder';
                }
                break;

            case 'file':
                if ('File' != $actualClass) {
                    $message       = 'Resource is not a file';
                    $expectedClass = 'File';
                }
                break;

            case 'link':
                if ('Link' != $actualClass) {
                    $message       = 'Resource is not a link';
                    $expectedClass = 'Link';
                }
                break;

            default:
                throw new Exception(
                    "Invalid resource type '$this->data'"
                );
        }

        if (!empty($message)) {
            $this->addError(
                $message,
                $expectedClass,
                $actualClass,
                $resource
            );
        }
    }
}
