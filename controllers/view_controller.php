<?php

class view_controller {

    private $view;
    private $html;
    private $header = false;
    private $footer = false;

    function getView() {
        return $this->view;
    }

    function setView($view) {
        $this->view = $view;
    }
    
    function addToView($html){
        if(empty($this->getView())){
            $this->view = $html;
        }else{
            $this->view .= $html;
        }
    }

    private function getHtml() {
        return $this->html;
    }

    private function setHtml($html) {
        $this->html = $html;
    }

    private function getHeader() {
        return $this->header;
    }

    private function getFooter() {
        return $this->footer;
    }

    private function setHeader($header) {
        $this->header = $header;
    }

    private function setFooter($footer) {
        $this->footer = $footer;
    }

    function __construct($html) {
        if (ob_get_level()) {
            ob_get_clean();
        } 
        ob_start();
        $this->setHtml($html);
    }

    public function show() {
        $header = $this->getHeader()===false?'':$this->getHeader();
        $footer = $this->getFooter()===false?'':$this->getFooter();
        $body = $this->getHtml();
        $page = $header.$body.$footer;
        
        echo $page;
    }

    public function setHeaderTemplate($html) {
        $this->setHeader($html);
    }

    public function setFooterTemplate($html) {
        $this->setFooter($html);
    }
    
    
//    public function setHeaderTemplate(string $html = false) {
//        $this->header = $html;
//    }
//
//    public function setFooterTemplate(string $html = false) {
//        $this->footer = $html;
//    }

}

//echo "hola que tal esto no se va a ver";
//
//
//$html = "Esto si<br>";
//
//$view = new view_controller($html);
//$view->setHeaderTemplate("Este es el encabezado<br>");
//$view->setFooterTemplate("Este es el pie");
//
//$view->show();
