<?php

     class mDay implements IModel  {
         
        private $params;
        private $year, $month, $day, $hour, $minute;
         
        public function __construct()  {
            
            $this->year = intval(date('Y'));
            $this->hour = intval(date('G'));
            $this->minute = intval(date('i'));
            
        }

     
        public function action($params = array())   {
            
            $this->params = $params;

            $res = array();

            #0 - today or not ?
            $this->day = intval($params['day']);
            $this->month = intval($params['month']);
            
            $today = $params['day'] == intval(date('j')) && $params['month'] == intval(date('n'));
            $res['today'] = $today;

            #1 - sunset & sunrise
            $dt = mktime(1, 1, 1, $params['month'], $params['day']);
            
            $res['sunset'] = date_sunset($dt, SUNFUNCS_RET_STRING, $_GET['sc_config']['latitude'], $_GET['sc_config']['longitude']);
            $res['sunrise'] = date_sunrise($dt, SUNFUNCS_RET_STRING, $_GET['sc_config']['latitude'], $_GET['sc_config']['longitude']);
            
            $res['daylong'] = $this->DayLong($res['sunrise'], $res['sunset']);
            
            #2 - moon
            if($today)  {
                $phase = $this->EvalMoonPhase($params['day'], $params['month']);
                $res['moon'] = abs($phase) . "%, " . ($phase < 0 ? 'убывает &darr;' : 'растет &uarr;');
            } else $res['moon'] = false;
            
            #3 - date
            $dstamp = mktime($this->hour, $this->minute, 0, $this->month, $this->day, $this->year);
            $dparts = getdate($dstamp);
            
            if($today)  {
                $dstr = ruWeekDay($dparts['wday']) . ' ' . $dparts['mday'] . ' ' . ruMonthName($dparts['mon']) . ' ' . $dparts['year'] . ' г.';
            } else {
                $dstr = $dparts['mday'] . ' ' . ruMonthName($dparts['mon']);
            }
            
            $res['day'] = $dstr;

            // done
            return $res;

        }

        private function EvalMoonPhase($day, $month)	{

            $lfm = explode('/', $_GET['sc_config']['fullmoon']);
            $fullMoon = (double) mktime(intval($lfm[0]), intval($lfm[1]), intval($lfm[2]), 
                                        intval($lfm[3]), intval($lfm[4]), intval($lfm[5]));	# последнее точно известное полнолуние !
            
            $currDate = (double) mktime($this->hour, $this->minute, 0, $this->month, $this->day, $this->year);

            $dsec = (double) (29.53059*24*60*60)/2;
            $tmp = $fullMoon;
            $up = false;

            do	{

                if($tmp + $dsec > $currDate) break;
                $tmp += $dsec;
                $up = ($up == true) ? false : true;

            } while(true);

            $x = (($currDate - $tmp)/$dsec)*100;
            if(!$up) $x = 100 - $x;

            $res = abs(round($x,2));

            return $up ? $res : -$res;

        }	# evalMoonPhase()


        /**
		 * $sunrise	00:00	string
		 * $sunset		00:00	string
		 */
		private function DayLong($sunrise, $sunset)	{
			
			$sr = explode(':', $sunrise);
			$ss = explode(':', $sunset);
			
			$sr_mins = intval($sr[0]) * 60 + intval($sr[1]);	// sunrise time in minutes from 00:00
			$ss_mins = intval($ss[0]) * 60 + intval($ss[1]);	// sunset time in minutes from 00:00
            
            
			$diff = $ss_mins - $sr_mins; 
			
			$long_h = floor($diff / 60);
			$long_m = $diff - ($long_h * 60); 
			
			// -->>>>>
			return $long_h . " ч. " . $long_m . " мин.";
		}
         
     }

?>
