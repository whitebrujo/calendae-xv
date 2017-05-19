<?php

    class mSigns implements IModel  {
     
        public function action($params = array())   {
            
            $res = array();
            
            $fp = fopen('data/signs.data', 'r');
            if($fp === false) return $res;
            
            while(!feof($fp))   {
                
                if(($date_str = fgets($fp)) === false) break;
                if(($title_str = fgets($fp)) === false) break;
                if(($text_str = fgets($fp)) === false) break;
                
                $date = explode('/', trim($date_str));
                
                if($date[0] == $params['day'] && $date[1] == $params['month'])
                    $res[] = array('day' => $date[0], 'month' => $date[1], 'title' => trim($title_str), 'text' => $text_str);
            }
            
            fclose($fp);
            
            // done
            return $res;
        }
        
    }

?>
