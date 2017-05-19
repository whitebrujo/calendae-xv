<?php

    ini_set('display_errors', 0);

    function _my_logerror( $errno ,  $errstr ,  $errfile ,  $errline ,  $errcontext)    {
        
        $dt = date('r');
        $text = "[$dt]: error #$errno ($errstr) at $errline in $errfile\n";
        
        @file_put_contents('log/phperror.log', $text , FILE_APPEND);
        
        die(str_replace('#errtext#', $text ,@file_get_contents('html/error.html')));
        
    }

    set_error_handler('_my_logerror');

?>