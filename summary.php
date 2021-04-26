<?php
	/**
	 * Reading Crane Summary Creator
	 *
	 * Copyright (C) 2011 Reading Crane
	 *
	 */

	/** Error reporting */
	error_reporting(E_ALL);
	
	include("inc/settings.php");

	if(isset($_GET['id'])) {
	
		require("inc/summary-0.0.1.class.php");
		
		$summary = new summary_document_generator;

		$id = $_GET['id'];

		$content = $summary->generate_summary($id);
		
	} else {
		$content = 'NO ID SUPPLIED!<br><a href="index.php">Back To Inspections Manager</a>';
	}

	$v_navigation = vertical_navigation();
	/* Template File  */
	require_once("templates/standard.php");

?>