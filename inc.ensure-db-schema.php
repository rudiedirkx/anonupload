<?php

foreach ( $schema AS $tableName => $tableDefinition ) {
	// New table?
	$created = $db->table($tableName, $tableDefinition);

	// Table existed
	if ( null === $created ) {
		// Retrieve columns
		$actualColumns = $db->columns($tableName);

		// Build SQL for missing columns
		foreach ( $tableDefinition AS $columnName => $columnDefinition ) {
			if ( is_int($columnName) ) {
				$columnName = $columnDefinition;
				$columnDefinition = array();
			}

			// Ensure its existance
			$db->column($tableName, $columnName, $columnDefinition);
		}
	}
}
