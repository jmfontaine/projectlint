<?php
namespace ProjectLint\Console;

use ProjectLint\Console\Command;
use Symfony\Component\Console\Application as BaseApplication;

class Application extends BaseApplication
{
    public function __construct()
    {
        parent::__construct('ProjectLint by Jean-Marc Fontaine', '0.1.0-dev');

        $checkCommand = new Command\CheckCommand();
        $this->add($checkCommand);
        $this->setDefaultCommand($checkCommand->getName());
    }
}
