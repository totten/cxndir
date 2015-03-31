<?php
namespace Civi\Cxn\Dir;

use Civi\Cxn\Dir\Command\AddCommand;
use Civi\Cxn\Dir\Command\CallCommand;
use Civi\Cxn\Dir\Command\GetCommand;
use Civi\Cxn\Dir\Command\InitCommand;
use Civi\Cxn\Dir\Command\PreviewCommand;

class Application extends \Symfony\Component\Console\Application {

  /**
   * Primary entry point for execution of the standalone command.
   *
   * @return
   */
  public static function main($binDir) {
    $application = new Application('cxnapp', '@package_version@');
    $application->run();
  }

  public function __construct($name = 'UNKNOWN', $version = 'UNKNOWN') {
    parent::__construct($name, $version);
    $this->setCatchExceptions(TRUE);
    $this->addCommands($this->createCommands());
  }

  /**
   * Construct command objects
   *
   * @return array of Symfony Command objects
   */
  public function createCommands() {
    $commands = array();
    $commands[] = new InitCommand();
    $commands[] = new PreviewCommand();
    $commands[] = new AddCommand();
    $commands[] = new GetCommand();
    return $commands;
  }
}
