<?php
class METGS_meeting extends METGS_public_cpt {

    public $cpt = METGS_CPT_MEETING;

    function __construct($id = 0)
    {
        parent::__construct($id);
    }

    function showInfo(){
	    echo '<div class="metgs-meeting metgs-item">';
	    echo '<div class="metgs-data">';
	    $formattedDate = $this->getFormattedDate();
	    if(!empty($formattedDate)){
	    	echo '<div class="metgs-date">'.$formattedDate.'</div>';
	    }
	    $formattedDateDiff = $this->getFormattedDateDiff();
	    if(!empty($formattedDateDiff)){
		    echo '<div class="metgs-datediff">'.$formattedDateDiff.'</div>';
	    }
	    echo '</div>';
	    echo '</div>';
    }

    function getFormattedDate(){
    	$date = '';
    	$datetime = $this->getDatetime();
    	if(!empty($datetime)){
    		$date=date_i18n(get_option('date_format'), $datetime).' '.date_i18n(get_option('time_format'), $datetime);
	    }
		return $date;
    }

    function getFormattedDateDiff(){
	    $date = '';
	    $datetime = $this->getDatetime();
	    if(!empty($datetime)){
		    $date = METGS_functions_humanTimeDiff::getHumanTimeDiff($datetime);
	    }
	    return $date;
    }

    function getDatetime(){
        return $this->getValue($this->prefix.'_startdatetime');
    }
}