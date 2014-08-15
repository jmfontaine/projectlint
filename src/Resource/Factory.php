<?php
namespace ProjectLint\Resource;

use ProjectLint\Exception;

class Factory
{
    const BYTE     = 'B';
    const KILOBYTE = 'kB';
    const MEGABYTE = 'MB';
    const GIGABYTE = 'GB';

    public static function create(\SplFileInfo $fileInfo, $projectPath)
    {
        $type = $fileInfo->getType();
        switch ($type) {
            case 'dir':
                $resource = new Folder(
                    $fileInfo,
                    $projectPath
                );
                break;

            case 'file':
                $resource = new File(
                    $fileInfo,
                    $projectPath
                );
                break;

            case 'link':
                $resource = new Link(
                    $fileInfo,
                    $projectPath
                );
                break;

            default:
                throw new Exception(
                    "Invalid resource type '$type'"
                );
        }

        return $resource;
    }
}
