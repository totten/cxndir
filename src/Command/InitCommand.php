<?php
namespace Civi\Cxn\Dir\Command;

use Civi\Cxn\Dir\DirConfig;
use Civi\Cxn\Rpc\CA;
use Civi\Cxn\Rpc\Constants;
use Civi\Cxn\Rpc\KeyPair;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitCommand extends Command {

  protected function configure() {
    $this
      ->setName('init')
      ->setDescription('Initialize the configuration files')
      ->setHelp('Example: cxndir init "C=US, O=My Org')
      ->addArgument('basedn', InputArgument::OPTIONAL, 'The base DN for the CSR+certificate', 'O=DemoDir');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $config = new DirConfig();

    if (!is_dir($config->getDir())) {
      mkdir($config->getDir());
    }

    $appDn = $input->getArgument('basedn') . ', CN=' . Constants::OFFICIAL_APPMETAS_CN;

    if (!file_exists($config->getKeyFile())) {
      $output->writeln("<info>Create key file ({$config->getKeyFile()})</info>");
      $appKeyPair = KeyPair::create();
      KeyPair::save($config->getKeyFile(), $appKeyPair);
    }
    else {
      $output->writeln("<info>Found key file ({$config->getKeyFile()})</info>");
      $appKeyPair = KeyPair::load($config->getKeyFile());
    }

    if (!file_exists($config->getCsrFile())) {
      $output->writeln("<info>Create certificate request ({$config->getCsrFile()})</info>");
      $appCsr = CA::createCSR($appKeyPair, $appDn);
      file_put_contents($config->getCsrFile(), $appCsr);
    }
    else {
      $output->writeln("<info>Found certificate request ({$config->getCsrFile()})</info>");
      $appCsr = file_get_contents($config->getCsrFile());
    }

    if (!file_exists($config->getCertFile())) {
      $output->writeln("<info>Create certificate ({$config->getCertFile()})</info>");
      $appCert = CA::createSelfSignedCert($appKeyPair, $appDn);
      file_put_contents($config->getCertFile(), $appCert);
    }
    else {
      $output->writeln("<info>Found certificate ({$config->getCertFile()})</info>");
      $appCert = file_get_contents($config->getCertFile());
    }

    if (!file_exists($config->getAppsFile())) {
      $output->writeln("<info>Create apps file ({$config->getAppsFile()})</info>");
      $apps = array();
      file_put_contents($config->getAppsFile(), json_encode($apps, defined('JSON_PRETTY_PRINT') ? JSON_PRETTY_PRINT : 0));
    }
    else {
      $output->writeln("<info>Found apps file ({$config->getAppsFile()})</info>");
      $apps = $config->getApps();
    }
  }

}
