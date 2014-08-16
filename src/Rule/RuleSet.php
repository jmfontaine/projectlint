<?php
namespace ProjectLint\Rule;

use Psr\Log\LoggerInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;

class RuleSet
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $path;

    /**
     * @var array
     */
    private $rules;

    /**
     * @return LoggerInterface
     */
    private function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return $this
     */
    private function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * @param string $path
     *
     * @return $this
     */
    private function setPath($path)
    {
        $this->path = $path;

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

    private function loadFromArray(array $data)
    {
        $this->getLogger()->info('Trying to load rule set from array');

        ob_start();
        print_r($data);
        $debugData = ob_get_clean();
        $this->getLogger()->debug('Raw rule set content: ' . $debugData);

        $this->setName($data['name'])
             ->loadRulesFromArray($data['rules']);
    }

    private function loadRulesFromArray(array $data)
    {
        $this->getLogger()->info('Trying to load rules from array');

        foreach ($data as $name => $values) {
            $this->rules[] = new Rule(
                $name,
                $values['expression'],
                $values['include'],
                $values['exclude']
            );
        }

        return $this;
    }

    public function __construct($path, LoggerInterface $logger)
    {
        $this->setLogger($logger)
             ->setPath($path)
             ->loadFromFile($path);
    }

    public function loadFromFile($path)
    {
        $this->getLogger()->info('Trying to load rule set from file: ' . $path);

        // TODO: Check if ruleset can be loaded
        $locator       = new FileLocator(dirname($path));
        $loader        = new RuleSetLoader($locator);
        $configValues  = $loader->load($locator->locate(basename($path)));
        $processor     = new Processor();
        $configuration = new RuleSetConfiguration();

        $data = $processor->processConfiguration(
            $configuration,
            $configValues
        );

        $this->loadFromArray($data);

        $this->getLogger()->info('Loaded rule set: ' . $this->getName());

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }
}
