<?php

require 'inc.bootstrap.php';

$file = get_file();
if ( $file ) {
	if ( !empty($_GET['delete']) ) {
		exit('@todo Delete 1 file');
	}

	exit('@todo Download 1 file');

	require 'tpl.download.php';
	exit;
}

$batch = get_batch();
if ( !$batch ) {
	require 'inc.upload.php';
	require 'tpl.upload.php';
	exit;
}

if ( !empty($_GET['delete']) ) {
	exit('@todo Delete batch');
}

if ( !empty($_POST['mail']) ) {
	do_mail($batch, $_POST['mail']);
	do_redirect();
}

require 'tpl.files.php';
