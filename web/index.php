<?php
use Civi\Cxn\Rpc\Constants;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__ . '/../vendor/autoload.php';
ini_set('display_error', 0);

$app = new Silex\Application();

$app['config'] = function () {
  return new \Civi\Cxn\Dir\DirConfig();
};

// OPTIONAL: Provide a nice endpoint for enterprising web surfers.
$app->get('/', function () use ($app) {
  /** @var \Civi\Cxn\Dir\DirConfig $config */
  $config = $app['config'];
  return new Response(
    'civicrm cxn directory service',
    200,
    array('Content-Type' => 'text/plain')
  );
});

// OPTIONAL: Facilitate testing by publishing a feed of all
// apps.
$app->get('/cxn/apps', function () use ($app) {
  /** @var \Civi\Cxn\Dir\DirConfig $config */
  $config = $app['config'];
  $message = new \Civi\Cxn\Rpc\Message\AppMetasMessage(
    $config->getCert(),
    $config->getKeyPair(),
    $config->getApps()
  );

  return $message->toSymfonyResponse();
});

$app->error(function ($e) use ($app) {
  $app['config']->getLog('index.php')->error("Unhandled exception", array(
    'exception' => $e,
  ));
});

$app->run();
