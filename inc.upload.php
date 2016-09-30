<?php

if ( isset($_FILES['files']) ) {
	$files = get_files();

	if ( !$batch ) {
		$batch = create_batch();
	}

	foreach ($files as $file) {
		$batch_file = create_file($batch, $file);

		move_uploaded_file($file['tmp_name'], ANONUPLOAD_FILES_DIR . '/' . $batch_file->location);
	}

	do_redirect('index.php?batch=' . urlencode($batch->secret));
	exit;
}
