<?php
namespace ProjectLint\Resource;

use ProjectLint\Exception;
use ProjectLint\Resource\Factory;

class File extends AbstractResource
{
    protected function _getMimeType($options = null)
    {
        $options = $options | FILEINFO_PRESERVE_ATIME;

        $finfo    = finfo_open($options);
        $mimeType = finfo_file($finfo, $this->getRealpath());
        finfo_close($finfo);

        return $mimeType;
    }

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
        return $this->_fileInfo->getFilename();
    }

    public function getMimeEncoding()
    {
        return $this->_getMimeType(FILEINFO_MIME_ENCODING);
    }

    public function getMimeType($returnAll = false)
    {
        $options = FILEINFO_MIME_TYPE;

        if ($returnAll) {
            $options = $options | FILEINFO_CONTINUE;
        }

        return $this->_getMimeType($options);
    }

    public function getSize($unit = Factory::BYTE)
    {
        $size = $this->_fileInfo->getSize();

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
        return $this->_fileInfo->isExecutable();
    }

    public function isFile()
    {
        return true;
    }

    public function isReadable()
    {
        return $this->_fileInfo->isReadable();
    }

    public function isWritable()
    {
        return $this->_fileInfo->isWritable();
    }
}