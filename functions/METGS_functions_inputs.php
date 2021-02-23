<?php
/**
 * Generates standard inputs
 */

class METGS_functions_inputs
{
    var $label;

    var $id;
    var $classes;
    var $name;
    var $autocomplete;
    var $required;
    var $disabled;
    var $step;
    var $min;
    var $max;
    var $maxlength;
    var $placeholder;

    var $value;
    var $saveValue;

    var $options;
    var $postHTML;
    var $afterLabel;

    function __construct($name, $id){
        $this->name = $name;
        $this->id = $id;
        $this->postHTML='';
        $this->afterLabel='';
    }

    function setInput($required, $label='', $name='', $classes=array(), $id='', $options=array(), $autocomplete='', $disabled=false){
        $this->value=get_post_meta($this->id, $this->name, true);

        $this->required = $required;
        if(empty($this->label)){
            $this->label = $label;
        }
        if(empty($this->name)){
            $this->name = $name;
        }
        if(empty($this->classes)){
            $this->classes = $classes;
        }
        if(empty($this->id)){
            $this->id = $id;
        }
        if(empty($this->options)){
            $this->options = $options;
        }
        if(empty($this->autocomplete)){
            $this->autocomplete = $autocomplete;
        }
        if(empty($this->disabled)){
            $this->disabled = $disabled;
        }
    }

    function save(){
        if ( $this->saveExists() && !isset($this->saveValue) ) {
            $this->saveValue=$_POST[$this->name];
        }

        if(isset($this->saveValue)) {
            update_post_meta(
                $this->id,
                $this->name,
                $this->saveValue
            );
        }
    }

    function saveExists(){
        return array_key_exists( $this->name, $_POST );
    }

    function showDatetime(){
        $mainname = $this->name;
        $mainlabel = $this->label;
        $mainvalue = $this->value;

        $this->name=$mainname.'_date';
        $this->label=$mainlabel.' '.__('date');
        if(!empty($this->value)) {
            $this->value = date('Y-m-d', $mainvalue);
        }
        $this->showInputHTML('date');

        $this->name=$mainname.'_time';
        $this->label=$mainlabel.' '.__('time', 'metgs');
        if(!empty($this->value)) {
            $this->value = date('H:m', $mainvalue);
        }
        $this->showInputHTML('time');

        $this->name=$mainname;
        $this->label=$mainlabel;
        $this->value=$mainvalue;
    }

    function saveDatetime(){
        if(array_key_exists( $this->name.'_date', $_POST )){
            $date = $_POST[$this->name.'_date'];
            if(array_key_exists( $this->name.'_time', $_POST )){
                $time = $_POST[$this->name.'_time'];
            } else {
                $time = '00:00';
            }
            $this->saveValue=strtotime($date.' '.$time);
            $this->save();
        }
    }

    function showInputHTML($type='text'){
        if(empty($this->id)){
            //Won't work with lists
            $this->id='input-'.$this->name;
        }

        $this->showInputPreHTML();
        if ($type=='textarea'){
            $this->showInputLabel();
            echo '<textarea'.$this->getAttrs().'>'.$this->value.'</textarea>';
        } else if ($type=='select') {
            $this->showInputLabel();
            echo '<select'.$this->getAttrs().'>';
            $this->showSelectOptions();
            echo '</select>';
        } else if ($type=='radio') {
            $this->showInputLabel();
            $this->showRadioOptions();
        } else if ($type=='checkbox') {
            $checked = '';
            if ("1" == $this->value) {
                $checked = ' checked';
            }
            echo '<input type="' . $type . '" value="1"'.$this->getAttrs().$checked.'/>';
            $this->showInputLabel();
        } else if ($type=='richeditor') {
            $settings = array(
                'teeny' => true,
                'textarea_rows' => 15,
                'tabindex' => 1
            );
            wp_editor(esc_html( $this->value ), $this->name, $settings);
        } else {
            $this->showInputLabel();
            echo '<input type="' . $type . '" value="'.$this->value.'"'.$this->getAttrs().'/>';
        }
        $this->showInputPostHTML();
    }

    function showInputPreHTML(){
        $classes = array('form-group');
        if(!empty($this->name)){
            $classes[]='input-'.$this->name;
        }
        echo '<div class="'.implode(' ', $classes).'">';
    }

    function showInputLabel(){
        if(!empty($this->label)) {
            echo '<label for="' . $this->id . '">' . $this->label . ($this->required ? '*' : '') .'</label>';
            echo $this->afterLabel;
        }
    }
















    function setDisabled(){
        $this->disabled = true;
    }

    function defaultRadio(){
        $this->setInput(__('Radio', 'metgs'), 'radio', array('metgs-input'), '', array());
        $this->showInputHTML('radio');
    }

    function defaultCheckbox(){
        $this->setInput(__('Checkbox', 'metgs'), 'checkbox', array('metgs-input'), '', array());
        $this->showInputHTML('checkbox');
    }

    function defaultText(){
        $this->setInput(__('Texto', 'metgs'), 'text', array('metgs-input'), '', array());
        $this->showInputHTML('text');
    }

    function defaultTextarea(){
        $this->setInput(__('Texto', 'metgs'), 'textarea', array('metgs-input'), '', array());
        $this->showInputHTML('textarea');
    }

    function country() {
        $countriesSelectorObj = new G4CO_functions_countries();
        $countriesSelector = $countriesSelectorObj->getSelectorList();
        $this->setInput(__('País', 'metgs'), 'country', array('metgs-input'), '', $countriesSelector, 'country-name');
        $this->showInputHTML('select');
    }



    function getAttrs(){
        $attrs = '';
        if(!empty($this->id)){
            $attrs .= ' id="'.$this->id.'"';
        }
        if(!empty($this->classes)){
            if(is_array($this->classes)){
                $classes=implode(' ', $this->classes);
            } else {
                $classes=$this->classes;
            }
            $attrs .= ' class="'.$classes.'"';
        }
        if(!empty($this->name)){
            $attrs .= ' name="'.$this->name.'"';
        }
        if(!empty($this->autocomplete)){
            $attrs .= ' autocomplete="'.$this->autocomplete.'"';
        }
        if($this->required){
            $attrs .= ' required';
        }
        if($this->disabled){
            $attrs .= ' disabled';
        }
        if($this->step){
            $attrs .= ' step="'.$this->step.'"';
        }
        if($this->min){
            $attrs .= ' min="'.$this->min.'"';
        }
        if($this->max){
            $attrs .= ' max="'.$this->max.'"';
        }
        if($this->maxlength){
            $attrs .= ' maxlength="'.$this->maxlength.'"';
        }
        if($this->placeholder){
            $attrs .= ' placeholder="'.$this->placeholder.'"';
        }

        return $attrs;
    }



    function setAdditionalPostHTML($html){
        $this->postHTML .= $html;
    }

    function setDescription($html){
        $this->afterLabel .= '<div class="input-description">'.$html.'</div>';
    }

    function showInputPostHTML(){
        echo $this->postHTML;
        echo '</div>';
    }

    function showSelectOptions(){
        if(!empty($this->options)) {
            foreach ($this->options as $optionKey => $optionName) {
                $selected = '';
                if ($optionKey == $this->value) {
                    $selected = ' selected';
                }
                echo '<option value="' . $optionKey . '"' . $selected . '>' . $optionName . '</option>';
            }
        }
    }

    function showRadioOptions(){
        if(!empty($this->options)) {
            $first = true;
            foreach ($this->options as $optionKey => $optionName) {
                if($first){
                    $first=false;
                } else {
                    echo '<br>';
                }
                $checked = '';
                if ($optionKey == $this->value) {
                    $checked = ' checked';
                }
                echo '<input type="radio" value="' . $optionKey . '"'.$this->getAttrs().$checked.'/>'.$optionName;
            }
        }
    }
}