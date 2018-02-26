<?php

require 'env.php';
require 'vendor/autoload.php';

define('ANONUPLOAD_DB_DIR', __DIR__ . '/db');

$db = db_sqlite::open(array('database' => ANONUPLOAD_DB_DIR . '/files.sqlite3'));

$db->ensureSchema(require 'inc.db-schema.php');

session_start();
