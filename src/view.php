<?php

class View {

	private $template;
	public function __construct($template)
	{
		$this->template = APP_DIR .'/views/'. $template .'.php';
	}

	public function render($params = [])
	{
		extract($params);

		ob_start();
		require($this->template);
		echo ob_get_clean();

	}

}
