<?php

class METGS_functions
{
    var $class_prefix=METGS_PREFIX;

    public function load() {
        $this->constants();
        $this->includes();
        $this->inits();
    }

    private function constants() {

    }

    private function includes() {
        require_once METGS_PLUGIN_FUNCTION_DIR . 'METGS_functions_inputs.php';
    }

    private function inits(){
    }

}
