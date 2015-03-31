<?php

namespace Civi\Cxn\Dir;

use Civi\Cxn\Rpc\CxnStore\JsonFileCxnStore;
use Civi\Cxn\Rpc\KeyPair;

/**
 * Class DirConfig
 *
 * @package Civi\Cxn\Dir
 */
class DirConfig {

  private $id;
  private $keyPair;
  private $apps;
  private $cert;

  public function getDir() {
    return dirname(__DIR__) . '/app';
  }

  public function getIdFile() {
    return dirname(__DIR__) . '/app/id.txt';
  }

  /**
   * @return string
   */
  public function getId() {
    if (!$this->id) {
      if (!file_exists($this->getIdFile())) {
        throw new \RuntimeException("Missing id file.");
      }

      $this->id = trim(file_get_contents($this->getIdFile()));
    }
    return $this->id;
  }

  public function getKeyFile() {
    return dirname(__DIR__) . '/app/cxndir.keys.json';
  }

  public function getCsrFile() {
    return dirname(__DIR__) . '/app/cxndir.csr';
  }

  public function getCertFile() {
    return dirname(__DIR__) . '/app/cxndir.crt';
  }

  public function getCert() {
    if ($this->cert === NULL) {
      $this->cert = file_get_contents($this->getCertFile());
    }
    return $this->cert;
  }

  /**
   * @return array
   *   Array with elements:
   *     - publickey: string, pem.
   *     - privateey: string, pem
   */
  public function getKeyPair() {
    if (!$this->keyPair) {
      if (!file_exists($this->getKeyFile())) {
        throw new \RuntimeException("Missing key file.");
      }

      $this->keyPair = KeyPair::load($this->getKeyFile());
    }
    return $this->keyPair;
  }

  public function getAppsFile() {
    return dirname(__DIR__) . '/app/cxndir.apps.json';
  }

  /**
   * @return array
   */
  public function getApps() {
    if (!is_array($this->apps)) {
      if (!file_exists($this->getAppsFile())) {
        throw new \RuntimeException("Missing metadata file.");
      }

      $this->apps = json_decode(file_get_contents($this->getAppsFile()), TRUE);
    }
    return $this->apps;
  }

  public function getLogFile() {
    return dirname(__DIR__) . '/app/log.txt';
  }

  /**
   * @param string $prefix
   * @return \Psr\Log\LoggerInterface
   */
  public function getLog($prefix = '') {
    return new \Civi\Cxn\Dir\SimpleFileLogger($this->getLogFile(), $prefix);
  }
}
