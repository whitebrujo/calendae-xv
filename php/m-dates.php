<?php

    class mDates implements IModel  {
     
        public function action($params = array())   {
            
            $res = array();
            
            $fp = fopen('data/dates.data', 'r');
            if($fp === false) return $res;
            
            while(!feof($fp))   {
                
                if(($date_str = fgets($fp)) === false) break;
                if(($text_str = fgets($fp)) === false) break;
                
                $date = explode('/', trim($date_str));
                
                if($date[0] == $params['day'] && $date[1] == $params['month'])
                    $res[] = array('day' => $date[0], 'month' => $date[1], 'year' => $date[2], 'text' => $text_str);
            }
            
            fclose($fp);
            
            // now sort dates by year
            function dcmp($d1, $d2) {
                $dv1 = intval($d1['year']);
                $dv2 = intval($d2['year']);
                
                if($dv1 == $dv2) return 0;
                if($dv1 > $dv2) return 1;
                else return -1;
            }
            
            usort($res, "dcmp");
            
            // done
            return $res;
        }
        
    }


?>
