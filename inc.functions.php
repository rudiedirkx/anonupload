<?php

function html( $text ) {
	return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8') ?: htmlspecialchars((string)$text, ENT_QUOTES, 'ISO-8859-1');
}

function do_redirect( $uri = null ) {
	$uri or $uri = get_url();
	header("Location: " . $uri);
	exit;
}

function do_mail( db_generic_record $batch, $recipients ) {
	mail($recipients, 'Anonymous upload', "Batch URL:\n\n" . get_url(), implode("\r\n", array(
		"Content-type: text/plain; charset=utf-8",
		"From: Anonymous upload <anonymous@example.com>"
	)));
}

function delete_file( db_generic_record $file ) {
	@unlink(ANONUPLOAD_FILES_DIR . '/' . $file->location);
	$db->delete('files', array('id' => $file->id));
}

function delete_batch( db_generic_record $batch ) {
	foreach ( $batch->files as $file) {
		delete_file($file);
	}

	$db->delete('batches', array('id' => $batch->id));
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
		return $db->select('files', array('location' => $secret))->first();
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
