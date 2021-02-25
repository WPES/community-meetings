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

    function __construct($name, $id, $elementType='post'){
        $this->name = $name;
        $this->id = $id;
        $this->postHTML='';
        $this->afterLabel='';
        $this->elementType=$elementType;

        $screen = get_current_screen();
        if(!empty($screen) && !empty($screen->base)){
            switch ($screen->base){
                case 'edit-tags':
                    $this->screen='add-term';
                    break;
                case 'term':
                    $this->screen='edit-term';
                    break;
                default:
                    $this->screen='post';
            }
        }
    }

    function isTaxonomy(){
        return $this->elementType=='taxonomy';
    }

    function setInput($required, $label='', $name='', $classes=array(), $id='', $options=array(), $autocomplete='', $disabled=false){
        if($this->isTaxonomy()) {
            $this->value = get_term_meta($this->id, $this->name, true);
        } else {
            $this->value = get_post_meta($this->id, $this->name, true);
        }

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
            if($this->isTaxonomy()){
                update_term_meta(
                    $this->id,
                    $this->name,
                    $this->saveValue
                );
            } else {
                update_post_meta(
                    $this->id,
                    $this->name,
                    $this->saveValue
                );
            }
        }
    }

    function saveExists(){
        return array_key_exists( $this->name, $_POST );
    }

    function showDatetime(){
        $inputDate = new METGS_functions_inputs($this->name.'_date', $this->id);
        $inputDate->setInput(false, $this->label.' '.__('date'));
        if(!empty($this->value)) {
            $inputDate->value = date('Y-m-d', $this->value);
        }
        $inputDate->showInputHTML('date');

        $inputTime = new METGS_functions_inputs($this->name.'_time', $this->id);
        $inputTime->setInput(false, $this->label.' '.__('time', 'meetings'));
        if(!empty($this->value)) {
            $inputTime->value = date('H:m', $this->value);
        }
        $inputTime->showInputHTML('time');
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

    function showMeetupEvent(){
        $options=array(
          '0' => 'None',
          '1' => 'Placeholder'
        );
        $this->options = $options;
        $this->showInputHTML('select');
    }

    function showTextarea(){
        $this->showInputHTML('textarea');
    }

    function showTexteditor(){
        $this->showInputHTML('richeditor');
    }

    function showSocialLinks(){
        $input = new METGS_functions_inputs($this->name.'_url', $this->id);
        $input->setInput(false, $this->label.' '.__('url'));
        $input->showInputHTML('url');

        $input = new METGS_functions_inputs($this->name.'_wpprofile', $this->id);
        $input->setInput(false, $this->label.' '.__('WordPress Profile', 'metgs'));
        $input->showInputHTML('url');

        $input = new METGS_functions_inputs($this->name.'_twitter', $this->id);
        $input->setInput(false, $this->label.' '.__('twitter', 'metgs'));
        $input->showInputHTML('url');

        $input = new METGS_functions_inputs($this->name.'_linkedin', $this->id);
        $input->setInput(false, $this->label.' '.__('linkedIn', 'metgs'));
        $input->showInputHTML('url');
    }

    function saveSocialLinks(){
        $input = new METGS_functions_inputs($this->name.'_url', $this->id);
        $input->save();
        $input = new METGS_functions_inputs($this->name.'_wpprofile', $this->id);
        $input->save();
        $input = new METGS_functions_inputs($this->name.'_twitter', $this->id);
        $input->save();
        $input = new METGS_functions_inputs($this->name.'_linkedin', $this->id);
        $input->save();
    }

    function showInputHTML($type='text'){
        if(empty($this->id)){
            //Won't work with lists
            $this->id='input-'.$this->name;
        }

        $this->showInputPreHTML();
        if($type!='checkbox' || $this->screen=='edit-term') {
            $this->showInputLabel();
            $this->input($type);
        } else {
            $this->input($type);
            $this->showInputLabel();
        }
        $this->showInputPostHTML();
    }

    private function inputTextarea(){
        echo '<textarea'.$this->getAttrs().'>'.$this->value.'</textarea>';
    }

    private function inputSelect(){
        echo '<select'.$this->getAttrs().'>';
        $this->showSelectOptions();
        echo '</select>';
    }

    private function inputCheckbox($type){
        $checked = '';
        if ("1" == $this->value) {
            $checked = ' checked';
        }
        echo '<input type="' . $type . '" value="1"'.$this->getAttrs().$checked.'/>';
    }

    private function inputWPEditor(){
        $settings = array(
            'teeny' => true,
            'textarea_rows' => 15,
            'tabindex' => 1
        );
        wp_editor(esc_html( $this->value ), $this->name, $settings);
    }

    private function input($type){
        if($this->screen=='edit-term'){
            echo '<td>';
        }
        if ($type=='textarea'){
            $this->inputTextarea();
        } else if ($type=='select') {
            $this->inputSelect();
        } else if ($type=='radio') {
            $this->showRadioOptions();
        } else if ($type=='checkbox') {
            $this->inputCheckbox($type);
        } else if ($type=='richeditor') {
            $this->inputWPEditor();
        } else {
            echo '<input type="' . $type . '" value="'.$this->value.'"'.$this->getAttrs().'/>';
        }
        if($this->screen=='edit-term'){
            echo '</td>';
        }
    }

    function showInputPreHTML(){
        $classes = array('form-group');
        if(!empty($this->name)){
            $classes[]='input-'.$this->name;
        }
        if($this->screen=='edit-term'){
            $classes[]='form-field';
            if($this->required) {
                $classes[]='form-required';
            }
            echo '<tr class="' . implode(' ', $classes) . '">';
        } else {
            echo '<div class="' . implode(' ', $classes) . '">';
        }
    }

    function showInputLabel(){
        if(!empty($this->label)) {
            if($this->screen=='edit-term'){
                echo '<th>';
            }
            echo '<label for="' . $this->id . '">' . $this->label . ($this->required ? '*' : '') .'</label>';
            echo $this->afterLabel;
            if($this->screen=='edit-term'){
                echo '</th>';
            }
        }
    }
















    function setDisabled(){
        $this->disabled = true;
    }

    function defaultRadio(){
        $this->setInput(__('Radio', 'meetings'), 'radio', array('metgs-input'), '', array());
        $this->showInputHTML('radio');
    }

    function defaultCheckbox(){
        $this->setInput(__('Checkbox', 'meetings'), 'checkbox', array('metgs-input'), '', array());
        $this->showInputHTML('checkbox');
    }

    function defaultText(){
        $this->setInput(__('Texto', 'meetings'), 'text', array('metgs-input'), '', array());
        $this->showInputHTML('text');
    }



    function country() {
        $countriesSelectorObj = new G4CO_functions_countries();
        $countriesSelector = $countriesSelectorObj->getSelectorList();
        $this->setInput(__('País', 'meetings'), 'country', array('metgs-input'), '', $countriesSelector, 'country-name');
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
        if($this->screen=='edit-term'){
            echo '</tr>';
        } else {
            echo '</div>';
        }
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