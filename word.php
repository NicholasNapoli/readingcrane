<?php
	
	include("inc/settings.php");

	if(isset($_GET['id'])) {
		
		require("inc/word-0.0.1.class.php");
		
		$word = new word_document_generator;
		
		$id = $_GET['id'];

		$content = $word->WordFormat($id);
		
	} else {
		$content = 'NO ID SUPPLIED.<br><a href="index.php">Back To Inspections Manager</a>';
	}

	$v_navigation = vertical_navigation();
	/* Template File  */
	require_once("templates/standard.php");

?>