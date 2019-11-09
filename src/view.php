<?php

/**
 * This file is part of UBoat - HTTP Botnet Project
 */

class View
{
    private $template;

    public function __construct($template)
    {
        $this->template = APP_DIR.'/views/'.$template.'.php';
    }

    public function render($params = [], $renderHeaderAndFooter = true)
    {
        extract($params);

        ob_start();
        if ($renderHeaderAndFooter) {
            require APP_DIR.'/views/head.php';
        }
        require $this->template;
        if ($renderHeaderAndFooter) {
            require APP_DIR.'/views/footer.php';
        }
        echo ob_get_clean();
    }
}
