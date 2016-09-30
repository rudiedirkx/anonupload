<!doctype html>
<html>

<head>
<meta charset="utf-8" />
<title>Anonymous upload</title>
</head>

<body>

<h2>Files in batch (<?= count($batch->files) ?>):</h2>

<ul>
	<? foreach ($batch->files as $file): ?>
		<li>
			(<a href="index.php?delete=1&file=<?= urlencode($file->location) ?>">x</a>)
			<a href="index.php?file=<?= urlencode($file->location) ?>"><?= html($file->name) ?></a>
		</li>
	<? endforeach ?>
</ul>

<p><a href="index.php?delete=1&batch=<?= urlencode($batch->secret) ?>">Delete entire batch</a></p>

<h2>Notify:</h2>

<form method="post" action>
	<p><input type="email" name="mail" required placeholder="Recipients, comma separated" /></p>
	<p><button>Mail</button></p>
</form>

<h2>Upload more files:</h2>

<?php include 'tpl.form.php'; ?>

</body>

</html>
