<?php

class Errors extends Controller {

	function index()
	{
		$this->error404();
	}

	function error404()
	{
		echo '<h1>404 Error</h1>';
		echo '<p>Looks like this page doesn\'t exist</p>';
		echo $_SERVER['REQUEST_URI'];
	}
    function error403()
	{
		echo '<h1>403 Forbidden </h1>';
		echo '<p>You are not allowed to perform this action.</p>';
	}
}
