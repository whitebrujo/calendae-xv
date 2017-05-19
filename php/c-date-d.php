<?php

    class Controller implements IController {
        
        private $get, $day, $month, $year;
        
        public function __construct(array $get)  {
            
            $this->get = $get;
            
            $this->year = intval(date('Y'));
            
            if(sizeof($get['sc_params']) != 2)  {
                $this->day = intval(date('j'));
                $this->month = intval(date('n'));
            } else {
                $this->month = $get['sc_params'][0];
                $this->day = $get['sc_params'][1];
            }
        }
        
        public function html()   {
            
            $out = file_get_contents(HTML_PATH . 'v-date-d.html');
            
            #1 - signs
            require_once(PHP_PATH . 'm-signs.php');
            
            $sm = new mSigns();
            $res = $sm->action(array('day' => $this->day, 'month'=> $this->month));
            $tmp = '';
            
            foreach($res as $csign) {
                $tmp .= "<li><span class=\"outlet\">" . $csign['title'] . ":</span> " . $csign['text'] . "</li>\n";
            }
            
            $out = str_replace('%signs%', "<ul>\n" . $tmp . "</ul>\n", $out);
            
            #2 - dates
            require_once(PHP_PATH . 'm-dates.php');
            
            $sd = new mDates();
            $res = $sd->action(array('day' => $this->day, 'month'=> $this->month));
            $tmp = '';
            
            foreach($res as $cdate) {
                if(false === ($pipe_pos = mb_strpos($cdate['text'], '|')))  // no pileline character found - add text as it is
                    $tmp .= "<li><span class=\"outlet\">" . normalizeYear($cdate['year']) . ': </span>' . $cdate['text'] . "</li>\n";
                else    {
                    $summary = mb_substr($cdate['text'], 0, $pipe_pos);
                    $text = mb_substr($cdate['text'], $pipe_pos + 1);
                    $tmp .= "<li><span class=\"outlet\">" . normalizeYear($cdate['year']) . 
                        ": </span><span class=\"summary\">$summary<span class=\"readmore\">[развернуть]</span></span><span class=\"details\"><br/>$text</span></li>\n"; 
                }
            }
            
            $out = str_replace('%dates%', "<ul>\n" . $tmp . "</ul>\n", $out);
            
            #3 - fests
            require_once(PHP_PATH . 'm-fests.php');
            
            $sd = new mFests();
            $res = $sd->action(array('day' => $this->day, 'month'=> $this->month));
            $tmp = '';
            
            foreach($res as $cfest) {
                $tmp .= "<li><i class=\"fa fa-star\"></i>" . $cfest['text'] . "</li>\n";
            }
            
            $out = str_replace('%fests%', "<ul>\n" . $tmp . "</ul>\n", $out);
            
            #4 - day
            require_once(PHP_PATH . 'm-day.php');
            
            $sd = new mDay();
            $res = $sd->action(array('day' => $this->day, 'month'=> $this->month));
            $tmp = '';
            
            $out = str_replace('%today%', $res['today'] ? 'Сегодня: ' : '', $out);
            $out = str_replace('%sunset%', $res['sunset'], $out);
            $out = str_replace('%sunrise%', $res['sunrise'], $out);
            $out = str_replace('%daylong%', $res['daylong'], $out);
            
            $out = str_replace('%moon%', $res['moon'] ? $res['moon'] : '?', $out);
            $out = str_replace('%date%', $res['day'], $out);
            
            #5 - history
            require_once(PHP_PATH . 'm-history.php');
            
            $sd = new mHistory();
            
            $out = str_replace('%history%', $sd->action(), $out);
            
            #6 - namedays
            require_once(PHP_PATH . 'm-ndays.php');
            $sd = new mNDays();
            
            $out = str_replace('%ndays%', $sd->action(array('day' => $this->day, 'month'=> $this->month)), $out);
            
            #7 - adjust %prev% and %next% vars
            $prev_stamp = strtotime('-1 day');
            $next_stamp = strtotime('+1 day');
            
            $_GET['sc_config']['prev'] = date('n/j', $prev_stamp);
            $_GET['sc_config']['next'] = date('n/j', $next_stamp);
            
            
            return $out; 
        }
    }

?>