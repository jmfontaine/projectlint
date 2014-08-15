<?php
namespace ProjectLint\Resource;

class Link extends File
{
    public function getMimeType($returnAll = false)
    {
        $options = FILEINFO_MIME_TYPE | FILEINFO_SYMLINK;

        if ($returnAll) {
            $options = $options | FILEINFO_CONTINUE;
        }

        return $this->_getMimeType($options);
    }

    public function getSize($unit = self::BYTE)
    {
        $size = $this->fileInfo->getSize();

        switch ($unit) {
            case self::BYTE:
                // Do nothing;
                break;

            case self::KILOBYTE:
                $size = $size * 1024;
                break;

            case self::MEGABYTE:
                $size = $size * 1024 * 1024;
                break;

            case self::GIGABYTE:
                $size = $size * 1024 * 1024 * 1024;
                break;

            default:
                throw new Exception(
                    "Invalid unit provided : $unit"
                );
        }

        return $size;
    }

    public function getTarget()
    {
        return $this->fileInfo->getTargetPath();
    }

    public function isLink()
    {
        return true;
    }
}
