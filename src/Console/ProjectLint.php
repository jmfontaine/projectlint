<?php
namespace ProjectLint\Console;

use ProjectLint\Console\Command;
use Symfony\Component\Console\Application;

class ProjectLint extends Application
{
    public function __construct()
    {
    	parent::__construct('ProjectLint by Jean-Marc Fontaine', '0.1.0-dev');

    	$this->addCommands(
    	    array(
    	        new Command\Check()
	        )
		);
    }
}
