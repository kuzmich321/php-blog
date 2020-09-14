<?php
namespace Core;
use Core\Application;

class Controller extends Application {
    protected $controller;
    protected $action;
    public $view;
    public $request;

    public function __construct($contr, $act) {
        parent::__construct();
        $this->controller = $contr;
        $this->action = $act;
        $this->request = new Input();
        $this->view = new View();
    }

    protected function load_model($model) {
        $modelPath = 'App\Models\\' . $model;
        if(class_exists($modelPath)) {
            $this->{$model.'Model'} = new $modelPath();
        }
    }

}