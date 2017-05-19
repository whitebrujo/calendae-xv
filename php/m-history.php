<?php

     class mHistory implements IModel  {
         
        private $params;
        

     
        public function action($params = array())   {
            
            $this->params = $params;
            
            $data_path = $_GET['sc_config']['data_path'];
            
            $files = array();
            $dp = opendir($data_path);
            while(false !== ($entry = readdir($dp)))    {
                if(!is_dir($data_path . '/' . $entry)) 
                    if(1 === preg_match("|^a[0-9]{3}\\.html$|U", $entry)) $files[] = $entry;
            }
            closedir($dp);
            
        
			sort($files);
			$tfn = $files[sizeof($files) - 1];	// last in list
			
			$match = array();
			preg_match("/\\d{3}/", $tfn, $match);
			$up = intval($match[0]);
			
			$avg = rand(1, $up);	// получим случайное число в нужном диапазоне
			
			$fname = sprintf('%s/a%03d.html', $data_path, $avg);	// получили имя файла
			
			$out = @file_get_contents($fname);
		    
            // done.
            return $out;
        }
     }