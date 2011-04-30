<?php
namespace ProjectLint\Console;

use Symfony\Component\Console\Application;
use ProjectLint\Console\Command;

class ProjectLint extends Application {
    public function __construct() {
    	parent::__construct('ProjectLint by Jean-Marc Fontaine', '0.1-dev');

    	$this->addCommands(
    	    array(
    	        new Command\Check()
	        )
		);
    }
}