<?php

require 'env.php';
require 'vendor/autoload.php';

$db = db_sqlite::open(array('database' => ANONUPLOAD_DB_DIR . '/files.sqlite3'));

$db->ensureSchema(require 'inc.db-schema.php');

session_start();
