<?php
namespace Core;

class View {
    protected $head;
    protected $body;
    protected $siteTitle = SITE_TITLE;
    protected $outputBuffer;
    protected $layout = DEFAULT_LAYOUT;

    public function __construct() {

    }

    public function render($viewName) {
        $viewArr = explode('/', $viewName);
        $viewString = implode(DS, $viewArr);
        if(file_exists(ROOT . DS . 'app' . DS . 'views' . DS . $viewString . '.php')) {
            include(ROOT . DS . 'app' . DS . 'views' . DS . $viewString . '.php');
            include(ROOT .DS . 'app' . DS . 'views' . DS . 'layouts' . DS . $this->layout . '.php');
        } else {
            die('View "'.$viewName.'" doesnt exist');
        }
    }

    public function content($type) {
        if($type == 'head') {
            return $this->head;
        } elseif($type == 'body') {
            return $this->body;
        }
        return false;
    }

    public function start($type) {
        $this->outputBuffer = $type;
        ob_start();
    }

    public function end() {
        if($this->outputBuffer == 'head') {
            $this->head = ob_get_clean();
        } elseif ($this->outputBuffer == 'body') {
            $this->body = ob_get_clean();
        } else {
            die('U must first run the start method');
        }
    }

    public function siteTitle() {
        return $this->siteTitle;
    }

    public function setSiteTitle($title) {
        $this->siteTitle = $title;
    }

    public function setLayout($path) {
        $this->layout = $path;
    }

}