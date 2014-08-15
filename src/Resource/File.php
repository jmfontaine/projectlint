<?php
namespace ProjectLint\Resource;

use ProjectLint\Exception;
use ProjectLint\Resource\Factory;

class File extends AbstractResource
{
    public function getContent()
    {
        $filename = $this->getFilename();

        if (!$this->isReadable()) {
            throw new Exception(
                "File '$filename' is unreadable"
            );
        }

        $content = file_get_contents($filename);
        if (false === $content) {
            throw new Exception(
                "Could not get '$filename' content"
            );
        }

        return $content;
    }

    public function getExtension()
    {
        $filename        = $this->getFilename();
        $lastDotPosition = strrpos($filename, '.');
        return substr($filename, $lastDotPosition + 1);
    }

    public function getFilename()
    {
        return $this->fileInfo->getFilename();
    }

    public function getMimeEncoding()
    {
        return $this->_getMimeType(FILEINFO_MIME_ENCODING);
    }

    public function getMimeType($returnAll = false)
    {
        $options = FILEINFO_MIME_TYPE | FILEINFO_PRESERVE_ATIME;

        if ($returnAll) {
            $options = $options | FILEINFO_CONTINUE;
        }

        $finfo    = finfo_open($options);
        $mimeType = finfo_file($finfo, $this->getRealpath());
        finfo_close($finfo);

        return $mimeType;
    }

    public function getSize($unit = Factory::BYTE)
    {
        $size = $this->fileInfo->getSize();

        switch ($unit) {
            case Factory::BYTE:
                // Do nothing;
                break;

            case Factory::KILOBYTE:
                $size = $size * 1024;
                break;

            case Factory::MEGABYTE:
                $size = $size * 1024 * 1024;
                break;

            case Factory::GIGABYTE:
                $size = $size * 1024 * 1024 * 1024;
                break;

            default:
                throw new Exception(
                    "Invalid unit provided : $unit"
                );
        }

        return $size;
    }

    public function isExecutable()
    {
        return $this->fileInfo->isExecutable();
    }

    public function isFile()
    {
        return true;
    }

    public function isReadable()
    {
        return $this->fileInfo->isReadable();
    }

    public function isWritable()
    {
        return $this->fileInfo->isWritable();
    }
}
