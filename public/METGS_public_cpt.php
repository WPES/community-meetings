<?php
class METGS_public_cpt {

	public function __construct($id=0)
	{
	    if (empty($id)){
	        $id=get_the_ID();
        }
        $this->id=$id;
	}

    function getTitle(){
        return get_the_title($this->id);
    }
    
    function getURI(){
    	return get_the_permalink($this->id);
    }

    function getEditURI(){
	    return get_edit_post_link($this->id);
    }
    
    function getContent(){
        $content_post = get_post($this->id);
        $content = $content_post->post_content;
        $content = apply_filters('the_content', $content);
        $content = str_replace(']]>', ']]&gt;', $content);
        return $content;
    }

    function getExcerpt(){
	    return get_the_excerpt($this->id);
    }

    function getExcerptLimited($limit){
        return wp_trim_words($this->getExcerpt(), $limit);
    }

    function isPublished(){
        return (get_post_status( $this->id )=='publish');
    }

    function getAuthorId(){
	    return get_post_field( 'post_author', $this->id );
    }

    function isUserAllowed(){
	if(get_post_type($this->id) == $this->cpt &&
            (current_user_can( 'edit_others_posts', $this->id ) || get_post_field ('post_author', $this->id) == get_current_user_id())
        ){
	        return true;
        }
	return false;
    }

    function getPublicationDate($dateFormat='U'){
        return get_the_time($dateFormat, $this->id);
    }

    function getModificationDate($dateFormat='U'){
	return get_the_modified_time($dateFormat, $this->id);
    }

    function getPageSummary(){
        return get_the_excerpt( $this->id );
    }

    /* Common functions */
    function getPortfolioType(){
        return $this->getTerms($this->taxonomy_portfoliotype);
    }

    function getTerms($taxonomy){
        return wp_get_post_terms( $this->id, $taxonomy);
    }

    function getTermsDataArray($terms, $className){
        $dataArray=array();
        if(!empty($terms) && is_array($terms)) {
            foreach ($terms as $term) {
                $termObj = new $className($term);
                $dataArray[] = $termObj->dataArray();
            }
        }
        return $dataArray;
    }

    /* Tools */
    function getValue($key){
        $values = '';
        if (!empty($this->id)) {
            $values = get_post_meta($this->id, $key, true);
            $values = ($values=='') ? '' : $values;
        }
        return $values;
    }

    function setValue($key, $value){
        if (!empty($this->id)) {
            return update_post_meta($this->id, $key, $value);
        }
        return 0;
    }

    function removeValue($key, $value=''){
        if (!empty($this->id)) {
            if(empty($value)) {
                return delete_post_meta($this->id, $key);
            } else {
                return delete_post_meta($this->id, $key, $value);
            }
        }
        return 0;
    }

    function getQueryArgsForTaxonomy($taxonomy){
        $taxQuery=array();
        $termsObj = $this->getTerms($taxonomy);
        if(!empty($termsObj)){
            $termsIDArray=array();
            foreach ($termsObj as $term){
                $termsIDArray[]=$term->term_id;
            }

            $taxQuery=array(
                'taxonomy' => $taxonomy,
                'field'    => 'term_id',
                'terms'    => $termsIDArray,
            );
        }

        //Append to $args['tax_query'][]
        return $taxQuery;
    }

    function getPostedOrPublishedHTML(){
        if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
            $time_string = '<time class="entry-date published hidden" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
        } else {
            $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
        }
        $time_string = sprintf( $time_string,
            esc_attr( get_the_date( 'c' ) ),
            esc_html( get_the_date() ),
            esc_attr( get_the_modified_date( 'c' ) ),
            esc_html( get_the_modified_date() )
        );
        if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
            // $posted_on = sprintf(
            // /* translators: %s: post date. */
            //     esc_html_x( 'updated on %s', 'post date', 'gsg-sports' ),
            //     $time_string
            // );
            $posted_on = __('updated on','g4co') . ' ' . $time_string;
        } else {
            $posted_on = sprintf(
            /* translators: %s: post date. */
                esc_html_x( 'published on %s', 'post date', 'g4co' ),
                $time_string
            );
        }
        return $posted_on;
    }
    
    function setParameterIfDifferent($parameterName, $newValue){
        if(isset($newValue)){
            $getParameterFunction='get'.$parameterName;
            $setParameterFunction='set'.$parameterName;
            if(method_exists($this, $getParameterFunction) && method_exists($this, $setParameterFunction)){
                $currentValue = $this->$getParameterFunction();
                if($currentValue != $newValue){
                    $this->$setParameterFunction($newValue);
                }
            }
        }
    }
    
    function isAjaxAction($action){
        if(wp_doing_ajax() && (!empty($_POST['action']) && $_POST['action']==$action)){
            return true;
        }
        return false;
    }

    function timeNow(){
        return (int)time();
    }

}
