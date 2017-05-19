<?php

    class Controller implements IController {
        
        private $get;
        
        public function __construct(array $get)  {
            $this->get = $get;
        }
        
        public function html()   {
            return "Запрашиваемый ресурс (" . $this->get['sc_controller'] . ") не найден.";
        }
    }

?>