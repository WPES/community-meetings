<?php defined('ABSPATH') or die('Not today.');

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
	    	echo '<div class="metgs-date">'.esc_html($formattedDate).'</div>';
	    }
	    $formattedDateDiff = $this->getFormattedDateDiff();
	    if(!empty($formattedDateDiff)){
		    echo '<div class="metgs-datediff">'.esc_html($formattedDateDiff).'</div>';
	    }
	    $meetupUrl = $this->getMeetupURL();
	    if(!empty($meetupUrl)){
		    echo '<div class="metgs-meetup-url"><a href="'.esc_url($meetupUrl).'">'.__('Meetup URL', 'meetings').'</a></div>';
	    }
	    $attendees = $this->getMeetupAttendees();
	    if(!empty($attendees)){
		    echo '<div class="metgs-attendees">'.__('Attendees', 'meetings').': '.esc_html($attendees).'</div>';
	    }

	    echo '</div>';
	    echo '</div>';
	    $this->showSchema();
    }

    function showSchema(){
	    $schema = array(
		'@context' => 'https://schema.org',
		'type' => 'Event',
		'location' => array(
			'@type' => 'Place',
		),
		'image' => get_the_post_thumbnail_url(),
		'description' => get_the_excerpt(),
		'name' => get_the_title(),
	    );
	    $formattedDate = $this->getFormattedDate();
	    if(!empty($formattedDate)){
		    $schema['startDate'] = $formattedDate;
		    // "eventStatus": "https://schema.org/EventScheduled",
	    }
	    $output = wp_json_encode( $schema, JSON_UNESCAPED_SLASHES );
	    echo '<script type="application/ld+json">';
	    echo $output; // phpcs:ignore
	    echo '</script>';
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
		if(!empty($results['attendees']) && $results['attendees']!=-1){
			$attendees=$results['attendees'];
		}
		return $attendees;
    }

	private function gatherMeetupData() {
		$results = array(
			'attendees' => -1
		);
		$url = $this->getMeetupURL();
		if ( ! empty( $url ) ) {
			$url=trailingslashit($url);
			$transientName = 'METGS_meeting-gatherMeetupData-'.md5($url);
			if ( false === ( $results = get_transient( $transientName ) ) ) {
				$response = wp_remote_get( $url );
				$http_code = wp_remote_retrieve_response_code( $response );
				if($http_code==200) {
					$body = wp_remote_retrieve_body( $response );
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

					$dom->loadHTML( $body );
					$finder    = new DomXPath( $dom );
					$classname = "attendees-sample-total";
					$nodes     = $finder->query( "//*[contains(@class, '$classname')]" );
					$tmp_dom   = new DOMDocument();
					foreach ( $nodes as $node ) {
						$tmp_dom->appendChild( $tmp_dom->importNode( $node, true ) );
					}
					$html_var = trim( $tmp_dom->saveHTML() );
					preg_match_all( '/\(([0-9]*)\)/', $html_var, $matches );

					libxml_use_internal_errors( false );

					if ( ! empty( $matches[1][0] ) ) {
						$attendees = $matches[1][0];
					}
					$results['attendees'] = $attendees;
					set_transient( $transientName, $results, 4 * HOUR_IN_SECONDS );
				}
			}
		}
		return $results;
	}

}