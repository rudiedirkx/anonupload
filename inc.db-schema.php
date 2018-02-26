<?php

return array(
	'version' => 1,
	'tables' => array(
		'batches' => array(
			'id' => array('pk' => true),
			'secret',
			'created_on' => array('unsigned' => true),
		),
		'files' => array(
			'id' => array('pk' => true),
			'batch_id' => array('unsigned' => true),
			'name',
			'location',
			'mime',
		),
	),
);
