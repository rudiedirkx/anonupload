<?php

require 'inc.bootstrap.php';

// Download or delete file
$file = get_file();
if ( $file ) {
	if ( !empty($_GET['delete']) ) {
		delete_file($file);
		do_redirect('index.php?batch=' . urlencode($file->batch->secret));
	}

	do_download($file);
}

// Create new batch
$batch = get_batch();
if ( !$batch ) {
	handle_upload();
	require 'tpl.upload.php';
	exit;
}

// Delete batch
if ( !empty($_GET['delete']) ) {
	delete_batch($batch);
	do_redirect('index.php');
}

// Mail batch
if ( !empty($_POST['mail']) ) {
	do_mail($batch, $_POST['mail']);
	do_redirect();
}

// Show batch files
handle_upload();
require 'tpl.files.php';
