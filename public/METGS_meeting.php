<?php
class METGS_meeting extends METGS_public_cpt {

    public $cpt = METGS_CPT_MEETING;

    function __construct($id = 0)
    {
        parent::__construct($id);
    }

    function getVariable(){ //As example
        return $this->getValue('_metgs_variable');
    }


}