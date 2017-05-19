<?php

    /**
    *   load template file, substitute outer vars (given as param) and in-template vars
    */
    function workUpTemplate($filename, $vars = array()) {
        
        $html = file_get_contents($filename);
        
        #1 - outer vars
        foreach($vars as $vk => $vv)    {
            $html = str_replace('%' . $vk . '%', $vv, $html);
        }
        #2 - inner includes
        $file_pattern = "/file\\{[^\\{]+\\}/U";
        $url_pattern = "/url\\{[^\\{]+\\}/U";
        
        $files_found = preg_match_all($file_pattern, $html, $file_matches);
        $urls_found = preg_match_all($url_pattern, $html, $url_matches);
        
        for($i = 0; $i < sizeof($file_matches[0]); $i++)    {
            $match = $file_matches[0][$i];
            $fname = extractArg($match);
            $data = file_exists($fname) ? file_get_contents($fname) : '';   // no file - do nothing !
            $html = str_replace($match, $data, $html);
        }
        
        for($i = 0; $i < sizeof($url_matches[0]); $i++)    {
            $match = $url_matches[0][$i];
            $data = getDataFromUrl(extractArg($match));
            $html = str_replace($match, $data, $html);
        }
        
        // ---->>> exit
        return $html;
        
    }

?>
