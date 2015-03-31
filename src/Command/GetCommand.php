<?php
namespace Civi\Cxn\Dir\Command;

use Civi\Cxn\Dir\DirConfig;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetCommand extends Command {

  protected function configure() {
    $this
      ->setName('get')
      ->setDescription('Get a list of known applications')
      ->setHelp('Example: cxndir get')
      ->addArgument('appId', InputArgument::OPTIONAL, 'The Appplication GUID');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $config = new DirConfig();
    $apps = $config->getApps();

    if ($input->getArgument('appId')) {
      foreach (array($input->getArgument('appId'), 'app:' . $input->getArgument('appId')) as $appId) {
        if (isset($apps[$appId])) {
          print_r($apps[$appId]);
        }
      }
    }
    else {
      $rows = array();
      foreach ($apps as $app) {
        $rows[] = array($app['appId'], $app['appUrl']);
      }

      $table = $this->getApplication()->getHelperSet()->get('table');
      $table
        ->setHeaders(array('App ID', 'App URL'))
        ->setRows($rows);
      $table->render($output);
    }
  }

}
