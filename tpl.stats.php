<?php

$numFiles = $db->count('files');
$numBatches = $db->count('batches');
$bytes = array_sum(array_map(function($file) {
	return filesize($file);
}, glob(ANONUPLOAD_FILES_DIR . '/*')));

?>

<h2>Stats:</h2>

<ul>
	<li><?= $numFiles ?> files</li>
	<li><?= $numBatches ?> batches</li>
	<li><?= number_format($bytes/1e6, 1) ?> MB total</li>
</ul>
