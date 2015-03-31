<?php
namespace Civi\Cxn\Dir\Command;

use Civi\Cxn\Dir\DirConfig;
use Civi\Cxn\Rpc\AppMeta;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddCommand extends Command {

  protected function configure() {
    $this
      ->setName('add')
      ->setDescription('Add the metadata of a remote application')
      ->setHelp('Example: cxndir add http://app.example.com/cxn/metadata.json')
      ->addArgument('url', InputArgument::REQUIRED, 'The application\'s metadata URL');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $appMeta = json_decode(file_get_contents($input->getArgument('url')), TRUE);
    AppMeta::validate($appMeta);

    $config = new DirConfig();
    $apps = $config->getApps();
    $apps[$appMeta['appId']] = $appMeta;
    file_put_contents($config->getAppsFile(),
      json_encode($apps, defined('JSON_PRETTY_PRINT') ? JSON_PRETTY_PRINT : 0));

    print_r($appMeta);
  }

}
