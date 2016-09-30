<?php

require 'env.php';

define('ANONUPLOAD_DB_DIR', __DIR__ . '/db');

require WHERE_DB_GENERIC_AT . '/db_sqlite.php';

$db = db_sqlite::open(array('database' => ANONUPLOAD_DB_DIR . '/files.sqlite3'));

$schema = require 'inc.db-schema.php';
require 'inc.ensure-db-schema.php';

require 'inc.functions.php';
