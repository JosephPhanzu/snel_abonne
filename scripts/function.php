<?php

	// Convertit une date ou un timestamp en français
function dateToFrench($date, $format) 
{
    $english_days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
    $french_days = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'vendredi', 'Samedi', 'Dimanche');
    $english_months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
    $french_months = array('Janv', 'Fév', 'Mars', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc');
    return str_replace($english_months, $french_months, str_replace($english_days, $french_days, date($format, strtotime($date) ) ) );
}

	function dateTime($timeDate, $time, $differance){
        
        if ( $differance < 60) {
            echo "à l'instant";
        }elseif ($differance += $timeDate > $time AND $differance < 3600) {
                                    
            echo "il à ".date("i", $differance)." minutes";
        }elseif ($differance += $timeDate < $time AND $differance < 86400) {
            echo "il à ".date("H", $differance)." Heure";
        }elseif ($differance += $timeDate < $time AND $differance < 172800) {
            echo "Hier à ".date("H", $timeDate)."H".date("i", $timeDate);
        }elseif ($differance += $timeDate < $time AND $differance < 31536000) {
            echo  dateToFrench(date('l', $timeDate), 'l')." ".date('d' , $timeDate)." ".dateToFrench(date('F', $timeDate), 'F')." à ".date("H", $timeDate).":".date("i", $timeDate);
        }else{
            echo date("d/m/Y", $timeDate)." à ".date("H:i", $timeDate);
        }
    }


	function plusDeHier($timeDate, $time, $differance){
		if ($differance += $timeDate < $time AND $differance < 31536000) {
            $plusDeHier = dateToFrench(date('l j F Y',$time),'l j F Y');
        }
	}

	function securisation($entre){
        $entre=trim($entre);
        $entre=strip_tags($entre);
        // $entre = htmlspecialchars($entre, ENT_QUOTES, 'UTF-8');
        return $entre;
    }

?>