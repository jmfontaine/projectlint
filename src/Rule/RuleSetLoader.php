<?php
namespace ProjectLint\Rule;

use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Yaml;

class RuleSetLoader extends FileLoader
{
    protected function normalizeYaml($yaml)
    {
        $yaml = array_map(
            function ($value) {
                return '    ' . $value;
            },
            explode(PHP_EOL, $yaml)
        );
        array_unshift($yaml, 'ruleset:');
        $yaml = implode(PHP_EOL, $yaml);

        return $yaml;
    }

    public function load($resource, $type = null)
    {
        $yaml = file_get_contents($resource);
        $yaml = $this->normalizeYaml($yaml);
        $configValues = Yaml::parse($yaml);

        return $configValues;
    }

    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'yml' === pathinfo($resource, PATHINFO_EXTENSION);
    }
}
