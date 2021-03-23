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
	    $attendees = $this->getMeetupAttendees();
	    if(!empty($attendees)){
		    echo '<div class="metgs-attendees">'.__('Attendees', 'meetings').': '.$attendees.'</div>';
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

    function getMeetupURL(){
	    return $this->getValue($this->prefix.'_meetup_event_url');
    }

    function getMeetupAttendees(){
    	$attendees=0;
		$results=$this->gatherMeetupData();
		if(!empty($results['attendees'])){
			$attendees=$results['attendees'];
		}
		return $attendees;
    }

	private function gatherMeetupData() {
		$results = array();
		$url = $this->getMeetupURL();
		if ( ! empty( $url ) ) {
			$url=trailingslashit($url);
			$transientName = 'METGS_meeting-gatherMeetupData-'.md5($url);
			if ( false === ( $results = get_transient( $transientName ) ) ) {
				$dom     = new DOMDocument();
				$context = stream_context_create(
					array(
						'http' => array(
							'follow_location' => false,
						),
						'ssl'  => array(
							'verify_peer'      => false,
							'verify_peer_name' => false,
						),
					)
				);
				libxml_use_internal_errors( true );
				libxml_set_streams_context( $context );

				$dom->loadHTMLFile( $url );
				$finder    = new DomXPath( $dom );
				$classname = "attendees-sample-total";
				$nodes     = $finder->query( "//*[contains(@class, '$classname')]" );
				$tmp_dom   = new DOMDocument();
				foreach ( $nodes as $node ) {
					$tmp_dom->appendChild( $tmp_dom->importNode( $node, true ) );
				}
				$html_var = trim( $tmp_dom->saveHTML() );
				preg_match_all( '/\(([0-9]*)\)/', $html_var, $matches );
				if(!empty($matches[1][0])){
					$attendees=$matches[1][0];
				} else {
					$attendees=0;
				}
				$results['attendees'] = $attendees;
				set_transient( $transientName, $results, 4 * HOUR_IN_SECONDS );
			}
		}
		return $results;
	}

}