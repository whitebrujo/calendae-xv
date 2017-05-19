<?php

    /**
    *   read .json file and return associative array or NULL
    */
    function readJson($filename)    {
        
        $text = trim(@file_get_contents($filename));
        if($text === '') return null;
        
        $json = @json_decode($text, true);
        
        return $json;
        
    }

    /**
    * extract argument from template expression var{...} file{...} or url{...}
    */
	function extractArg($s)	{
		
		$s = trim($s);
		$s = str_replace('url{', '', $s);
        $s = str_replace('file{', '', $s);
		$s = substr($s, 0, strlen($s) - 1);	// all but last
		
		return $s;
	}

    /**
    *	deletes trailing slash if present
    */
    function delSlash($text)	{

        $text = trim($text);
        if(substr($text, strlen($text) - 1, 1) == '/')
            return substr($text, 0, strlen($text) - 1);
        else
            return $text;
    }

    /**
    *   read data from url
    *
    */
    function getDataFromUrl($url, $timeout = 7) {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    /**
    *   russian weekdays
    */
    function ruWeekDay($wday)   {
        
        if($wday < 0 || $wday > 6) $wday = 0;
        
        $wd = array('воскресенье', 'понедельник', 'вторник', 'среда', 'четверг', 'пятница', 'суббота');
        
        return $wd[$wday];
    }

    /**
    *   russian month name in genetive
    */
    function ruMonthName($month)    {

        if($month < 1 || $month > 12) $month = 0; else $month--;
        $mn = array('января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
        
        return $mn[$month];

    }

    /**
    *   normalize year representation (russian)
    */
    function normalizeYear($year)    {
            
            $yv = intval($year);    
            
            if($yv < 0) return strval(abs($yv)) . " до н.э.";
            if($yv < 800) return strval(abs($yv)) . " н.э.";
            
            return strval($yv);
            
    }
	

?>
