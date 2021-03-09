<?php

class METGS_public_taxonomies
{
	public $prefix = METGS_PREFIX;

	public function __construct($term)
    {
        if(is_int($term)){
            $term = get_term($term);
        }

        if(!is_wp_error($term)) {
            $this->id = $term->term_id;
            $this->name = $term->name;
            $this->slug = $term->slug;
            $this->description = $term->description;
            $this->term = $term;
        }
        $this->init();
    }

    function init(){

    }

    function getName(){
    	return $this->name;
    }

    function getDescription(){
	    return $this->description;
    }

	function getSocialLinks(){
		$socialLinks = array();
		$url = $this->getValue($this->prefix.'_social_links_url');
		if(!empty($url)){
			$socialLinks['url']=$url;
		}
		$url = $this->getValue($this->prefix.'_social_links_wpprofile');
		if(!empty($url)){
			$socialLinks['wpprofile']=$url;
		}
		$url = $this->getValue($this->prefix.'_social_links_twitter');
		if(!empty($url)){
			$socialLinks['twitter']=$url;
		}
		$url = $this->getValue($this->prefix.'_social_links_linkedin');
		if(!empty($url)){
			$socialLinks['linkedin']=$url;
		}
		return $socialLinks;
	}

	function getImageID(){
		$imageId = $this->getValue($this->prefix.'_image');
		if(empty($imageId)){
			$imageId=0;
		}
		return $imageId;
	}

    function getImageHTML(){
        $html = '';
        $imgid = $this->getImageID();
        if(!empty($imgid)) {
	        $html .= '<div class="metgs-term-img-wrapper metgs-img-' . $this->slug . '">';
	        $html .= wp_get_attachment_image( $imgid );
	        $html .= '</div>';
        }
        return $html;
    }

     function getValue($key, $array=false){
        $values = '';
        $single = true;
        if($array){
            $single = false;
        }
        if (!empty($this->id)) {
            $values = get_term_meta($this->id, $key, $single);
            $values = empty($values) ? '' : $values;
        }
        return $values;
    }

    function getTerms($key) {
        return get_terms($key);
    }

    function getAllTerms($key) {
        return get_terms( array(
            'taxonomy' => $key,
            'hide_empty' => false,
        ) );
    }

    function formatDataForVCMap($array) {
        $results_formatted = array();
        foreach ($array as $key => $value) {
            $results_formatted[$value->name] = $value->term_id;
        }

        return $results_formatted;
    }
}
