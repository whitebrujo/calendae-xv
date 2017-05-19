<?php

    class mNDays implements IModel  {
         
        private $params;
        private $day, $month;

     
        public function action($params = array())   {
            
            $this->params = $params;
            
            $this->day = intval($params['day']);
            $this->month = intval($params['month']);
            
            $res = '';
            
            $fp = fopen($_GET['sc_config']['data_path'] . '/namedays.data', 'r');
            if($fp === false) return '';
            
            while(!feof($fp))   {
                
                $dstr = trim(fgets($fp));
                if($dstr === '') break;
                $nstr = trim(fgets($fp));
                if($nstr === '') break;
                
                $dparts = explode('/', $dstr);
                if(intval($dparts[0]) == $this->day && intval($dparts[1]) == $this->month)  {
                    $res = $nstr;
                    break;
                }
            }
            
            fclose($fp);
            
            // done.
            return $res;
          
        }
        
    }

?>
