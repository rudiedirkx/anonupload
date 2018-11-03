<!doctype html>
<html>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="theme-color" content="#333" />
	<meta charset="utf-8" />
	<title>Anonymous upload</title>
</head>

<body>

<? if ($msg = get_message()): ?>
	<p style="color: orange; font-size: 150%"><?= html($msg) ?></p>
<? endif ?>
