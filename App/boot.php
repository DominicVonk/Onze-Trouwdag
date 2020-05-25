<?php

define('DRAFT_CONTROLLERS', __DIR__ . '/Controllers');
define('DRAFT_VIEWS', __DIR__ . '/Views');
define('DRAFT_CONFIGS', __DIR__ . '/../config');

define('APP_FOLDER', __DIR__);
define('DATA_FOLDER', realpath(__DIR__.'/../data'));
define('PHP_VENDOR_FOLDER', realpath(__DIR__.'/../vendor'));

require(PHP_VENDOR_FOLDER.'/autoload.php');
setlocale(LC_ALL, 'nl_NL');

\App\Library\Config::load("db.json");
\App\Library\Config::load("routes.json");
\App\Library\Config::load("general.json");

\DraftMVC\DraftRouter::setViewClass('\DraftMVC\DraftViewTwig');
\DraftMVC\DraftRouter::setViewExtension('twig');
\DraftMVC\DraftRouter::disableLayoutSearch();

\DraftMVC\DraftModel::useDB(new \App\Library\DB());

\App\Library\Session::start();

\DraftMVC\DraftRouter::route(
    \DraftMVC\DraftConfig::get('routes')
);
