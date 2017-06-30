<?php

function html( $text ) {
	return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8') ?: htmlspecialchars((string)$text, ENT_QUOTES, 'ISO-8859-1');
}

function set_message( $message ) {
	$_SESSION['anonupload']['message'] = $message;
}

function get_message() {
	$message = @$_SESSION['anonupload']['message'];
	unset($_SESSION['anonupload']['message']);
	return $message;
}

function handle_upload() {
	global $batch;

	if ( isset($_FILES['files']) ) {
		$files = get_files();

		if ( !$batch ) {
			$batch = create_batch();
		}

		foreach ($files as $file) {
			$batch_file = create_file($batch, $file);

			move_uploaded_file($file['tmp_name'], ANONUPLOAD_FILES_DIR . '/' . $batch_file->location);
		}

		$num = count($files);
		set_message("$num files uploaded");

		do_redirect('index.php?batch=' . urlencode($batch->secret));
	}
}

function do_redirect( $uri = null ) {
	$uri or $uri = get_url();
	header("Location: " . $uri);
	exit;
}

function do_mail( db_generic_record $batch, $recipients ) {
	$success = mail($recipients, 'Anonymous upload', "Batch URL:\n\n" . get_url(), implode("\r\n", array(
		"Content-type: text/plain; charset=utf-8",
		"From: Anonymous upload <anonymous@example.com>"
	)));
	$success = $success ? 'Y' : 'N';

	set_message("Mail sent to $recipients: $success");
}

function do_download( db_generic_record $file ) {
	header('Content-type: ' . ($file->mime ?: 'application/octet-stream'));
	header('Content-disposition: attachment; filename=' . urlencode($file->name));
	readfile(ANONUPLOAD_FILES_DIR . '/' . $file->location);
	exit;
}

function delete_file( db_generic_record $file ) {
	global $db;

	@unlink(ANONUPLOAD_FILES_DIR . '/' . $file->location);
	$db->delete('files', array('id' => $file->id));

	set_message("1 file deleted");
}

function delete_batch( db_generic_record $batch ) {
	global $db;

	foreach ( $batch->files as $file) {
		delete_file($file);
	}

	$db->delete('batches', array('id' => $batch->id));

	set_message("Entire batch deleted");
}

function create_batch() {
	global $db;

	$secret = get_random();
	$data = array(
		'secret' => $secret,
		'created_on' => time(),
	);
	$db->insert('batches', $data);
	$data['id'] = $db->insert_id();

	return new db_generic_record($data);
}

function create_file( db_generic_record $batch, array $file ) {
	global $db;

	$secret = get_random();
	$ext = ($pos = strrpos($file['name'], '.')) !== false ? substr($file['name'], $pos) : '';

	$data = array(
		'batch_id' => $batch->id,
		'name' => basename($file['name']),
		'location' => $secret . $ext,
		'mime' => $file['type'],
	);
	$db->insert('files', $data);
	$data['id'] = $db->insert_id();

	return new db_generic_record($data);
}

function get_url() {
	$scheme = @$_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
	$host = $_SERVER['HTTP_HOST'];
	$uri = $_SERVER['REQUEST_URI'];
	return $scheme . $host . $uri;
}

function get_file( $secret = null ) {
	global $db;

	$secret or $secret = @$_GET['file'];
	if ( $secret ) {
		$file = $db->select('files', array('location' => $secret))->first();
		if ( $file ) {
			$file->batch = $db->select('batches', array('id' => $file->batch_id))->first();
			return $file;
		}
	}
}

function get_batch( $secret = null ) {
	global $db;

	$secret or $secret = @$_GET['batch'];
	if ( $secret ) {
		$batch = $db->select('batches', array('secret' => $secret))->first();
		if ( $batch ) {
			$batch->files = $db->select('files', array('batch_id' => $batch->id))->all();
			return $batch;
		}
	}
}

function get_files( $source = null ) {
	$source or $source = $_FILES['files'];

	$files = array();
	foreach ( $source as $property => $data ) {
		foreach ( $data as $index => $value ) {
			$files[$index][$property] = $value;
		}
	}

	$files = array_filter($files, function($file) {
		return !empty($file['name']) && !empty($file['tmp_name']) && empty($file['error']);
	});

	return $files;
}

function get_random() {
	$source = array_merge(range('a', 'z'), range('A', 'Z'), range(0, 9));

	$string = '';
	while ( strlen($string) < 24 ) {
		$string .= $source[ array_rand($source) ];
	}

	return $string;
}
