<?php

namespace App\Http\Controllers\ContentController;

interface ContentControllerInterface
{
	/**
	* Id is set after a call to one of the construct functions.
	* 
	* returns id, or null
	*/
	public function getId();

	/*
	* This function acts like a constructor
	*
	* $row is the row from the db.
	*/
	public function constructFromDBRow($row);

	//NYI
	public function getSettingsHTML();

	/*
	* This function is used to get the final HTML output.
	*/
	public function getHTML();
	
	/**
	* returns it's id (content_id), or a string, or an array of strings if an error has occured.
	*/
	public function createDBStructure($page_id);

	/**
	* returns it's id (content_id), or a string, or an array of strings if an error has occured.
	*/
	public function deleteDBStructure($page_contents_row);
}