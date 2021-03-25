<?php defined('ABSPATH') or die('Not today.');
/**
 * Generates standard inputs
 */

class METGS_functions_inputs {
	var $prefix=METGS_PREFIX;

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

	function __construct( $name, $id, $elementType = 'post' ) {
		$this->name        = $name;
		$this->id          = $id;
		$this->postHTML    = '';
		$this->afterLabel  = '';
		$this->elementType = $elementType;
		$this->screen = 'post';

		if(function_exists('get_current_screen')) {
			$screen = get_current_screen();
			if ( ! empty( $screen ) && ! empty( $screen->base ) ) {
				switch ( $screen->base ) {
					case 'edit-tags':
						$this->screen = 'add-term';
						break;
					case 'term':
						$this->screen = 'edit-term';
						break;
					default:
						$this->screen = 'post';
				}
			}
		}
	}

	function setInput( $required, $label = '', $name = '', $classes = array(), $id = '', $options = array(), $autocomplete = '', $disabled = false ) {
		if ( $this->isTaxonomy() ) {
			$this->value = get_term_meta( $this->id, $this->name, true );
		} else {
			$this->value = get_post_meta( $this->id, $this->name, true );
		}

		$this->required = $required;
		if ( empty( $this->label ) ) {
			$this->label = $label;
		}
		if ( empty( $this->name ) ) {
			$this->name = $name;
		}
		if ( empty( $this->classes ) ) {
			$this->classes = $classes;
		}
		if ( empty( $this->id ) ) {
			$this->id = $id;
		}
		if ( empty( $this->options ) ) {
			$this->options = $options;
		}
		if ( empty( $this->autocomplete ) ) {
			$this->autocomplete = $autocomplete;
		}
		if ( empty( $this->disabled ) ) {
			$this->disabled = $disabled;
		}
	}

	/* Show and save metaboxes */
	function showDatetime() {
		$inputDate = new METGS_functions_inputs( $this->name . '_date', $this->id );
		$inputDate->setInput( false, $this->label . ' ' . __( 'date' ) );
		if ( ! empty( $this->value ) ) {
			$inputDate->value = date( 'Y-m-d', $this->value );
		}
		$inputDate->showInputHTML( 'date' );

		$inputTime = new METGS_functions_inputs( $this->name . '_time', $this->id );
		$inputTime->setInput( false, $this->label . ' ' . __( 'time', 'meetings' ) );
		if ( ! empty( $this->value ) ) {
			$inputTime->value = date( 'H:m', $this->value );
		}
		$inputTime->showInputHTML( 'time' );
	}
	function saveDatetime() {
		if ( array_key_exists( $this->name . '_date', $_POST ) ) {
			$date = $_POST[ $this->name . '_date' ];
			if ( array_key_exists( $this->name . '_time', $_POST ) ) {
				$time = $_POST[ $this->name . '_time' ];
			} else {
				$time = '00:00';
			}
			$this->saveValue = strtotime( $date . ' ' . $time );
			$this->save();
		}
	}

	function showMeetupEvent() {
		$options       = array(
			'0' => 'None',
			'1' => 'Placeholder'
		);
		$this->options = $options;
		$this->showInputHTML( 'select' );
	}
	function saveMeetupEvent(){
		$this->save('select');
	}

	function showText() {
		$this->showInputHTML( 'text' );
	}
	function showUrl() {
		$this->showInputHTML( 'url' );
	}
	function showTextarea() {
		$this->showInputHTML( 'textarea' );
	}
	function showTexteditor() {
		$this->showInputHTML( 'richeditor' );
	}

	function showSocialLinks() {
		$input = new METGS_functions_inputs( $this->name . '_url', $this->id, $this->elementType );
		$input->setInput( false, $this->label . ' ' . __( 'url' ) );
		$input->showInputHTML( 'url' );

		$input = new METGS_functions_inputs( $this->name . '_wpprofile', $this->id, $this->elementType );
		$input->setInput( false, $this->label . ' ' . __( 'WordPress Profile', 'metgs' ) );
		$input->showInputHTML( 'url' );

		$input = new METGS_functions_inputs( $this->name . '_twitter', $this->id, $this->elementType );
		$input->setInput( false, $this->label . ' ' . __( 'twitter', 'metgs' ) );
		$input->showInputHTML( 'url' );

		$input = new METGS_functions_inputs( $this->name . '_linkedin', $this->id, $this->elementType );
		$input->setInput( false, $this->label . ' ' . __( 'linkedIn', 'metgs' ) );
		$input->showInputHTML( 'url' );
	}
	function saveSocialLinks() {
		$input = new METGS_functions_inputs( $this->name . '_url', $this->id, $this->elementType );
		$input->save();
		$input = new METGS_functions_inputs( $this->name . '_wpprofile', $this->id, $this->elementType );
		$input->save();
		$input = new METGS_functions_inputs( $this->name . '_twitter', $this->id, $this->elementType );
		$input->save();
		$input = new METGS_functions_inputs( $this->name . '_linkedin', $this->id, $this->elementType );
		$input->save();
	}

	function showImage(){
		$this->showInputHTML('image');
	}
	static function enqueueImageScripts(){
		wp_enqueue_media();
		wp_register_script('metgs_functions_input_image', METGS_PLUGIN_FUNCTION_URL.'/js/input-image.js', array('jquery'), '1', true );
		wp_enqueue_script('metgs_functions_input_image');

		wp_enqueue_style( 'metgs_functions_input_image', METGS_PLUGIN_FUNCTION_URL.'/js/input-image.css');
	}

	function showCountry() {
		$countriesSelectorObj = new METGS_functions_countries();
		$this->options = $countriesSelectorObj->getSelectorList();
		$this->showInputHTML( 'select' );
	}

	function save( $type = 'text' ) {
		if ( $this->saveExists() && ! isset( $this->saveValue ) ) {
			$savevalue = $_POST[ $this->name ];
		}

		if ( $type == 'textarea' ) {
			$this->saveValue = sanitize_textarea_field( $savevalue );
		} else if ( $type == 'radio' || $type == 'select' ) {
			$this->saveValue = sanitize_key( $savevalue );
		} else if ( $type == 'checkbox' ) {
			if ( ! empty( $savevalue ) ) {
				$this->saveValue = 1;
			} else {
				$this->saveValue = 0;
			}
		} else if ( $type == 'richeditor' ) {
			$this->saveValue = wp_kses_post( $savevalue );
		} else if ($type == 'url') {
			$this->saveValue = esc_url_raw( $savevalue );
		} else {
			$this->saveValue = sanitize_text_field( $savevalue );
		}

		if ( isset( $this->saveValue ) ) {
			if ( $this->isTaxonomy() ) {
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

	/* Internal functions */

	function isTaxonomy() {
		return ($this->elementType == 'taxonomy');
	}
	function setDisabled() {
		$this->disabled = true;
	}
	function setDescription( $html ) {
		$this->afterLabel .= '<div class="input-description">' . $html . '</div>';
	}

	function showInputHTML( $type = 'text' ) {
		if ( empty( $this->id ) ) {
			//Won't work with lists
			$this->id = esc_attr('input-' . $this->name);
		}

		$this->showInputPreHTML($type);
		if ( $type != 'checkbox' || $this->screen == 'edit-term' ) {
			$this->showInputLabel();
			$this->input( $type );
		} else {
			$this->input( $type );
			$this->showInputLabel();
		}
		$this->showInputPostHTML();
	}
	function showInputPreHTML($type='') {
		$classes = array( 'form-group' );
		if ( ! empty( $this->name ) ) {
			$classes[] = $this->prefix.'-input-' . $this->name;
		}
		if ( ! empty( $type ) ) {
			$classes[] = $this->prefix.'-inputtype-' . $type;
		}
		if ( $this->screen == 'edit-term' ) {
			$classes[] = 'form-field';
			if ( $this->required ) {
				$classes[] = 'form-required';
			}
			echo '<tr class="' . esc_attr(implode( ' ', $classes )) . '">';
		} else {
			echo '<div class="' . esc_attr(implode( ' ', $classes )) . '">';
		}
	}
	function showInputPostHTML() {
		echo $this->postHTML;
		if ( $this->screen == 'edit-term' ) {
			echo '</tr>';
		} else {
			echo '</div>';
		}
	}
	function setAdditionalPostHTML( $html ) {
		$this->postHTML .= $html;
	}
	function showInputLabel() {
		if ( ! empty( $this->label ) ) {
			if ( $this->screen == 'edit-term' ) {
				echo '<th>';
			}
			echo '<label for="' . esc_attr($this->id) . '">' . esc_html($this->label) . ( $this->required ? '*' : '' ) . '</label>';
			echo $this->afterLabel;
			if ( $this->screen == 'edit-term' ) {
				echo '</th>';
			}
		}
	}

	function getAttrs() {
		$attrs = '';
		if ( ! empty( $this->id ) ) {
			$attrs .= ' id="' . esc_attr($this->id) . '"';
		}
		if ( ! empty( $this->classes ) ) {
			if ( is_array( $this->classes ) ) {
				$classes = implode( ' ', $this->classes );
			} else {
				$classes = $this->classes;
			}
			$attrs .= ' class="' . esc_attr($classes) . '"';
		}
		if ( ! empty( $this->name ) ) {
			$attrs .= ' name="' . esc_attr($this->name) . '"';
		}
		if ( ! empty( $this->autocomplete ) ) {
			$attrs .= ' autocomplete="' . esc_attr($this->autocomplete) . '"';
		}
		if ( $this->required ) {
			$attrs .= ' required';
		}
		if ( $this->disabled ) {
			$attrs .= ' disabled';
		}
		if ( $this->step ) {
			$attrs .= ' step="' . esc_attr($this->step) . '"';
		}
		if ( $this->min ) {
			$attrs .= ' min="' . esc_attr($this->min) . '"';
		}
		if ( $this->max ) {
			$attrs .= ' max="' . esc_attr($this->max) . '"';
		}
		if ( $this->maxlength ) {
			$attrs .= ' maxlength="' . esc_attr($this->maxlength) . '"';
		}
		if ( $this->placeholder ) {
			$attrs .= ' placeholder="' . esc_attr($this->placeholder) . '"';
		}

		return $attrs;
	}

	private function input( $type ) {
		if ( $this->screen == 'edit-term' ) {
			echo '<td>';
		}
		if ( $type == 'textarea' ) {
			$this->inputTextarea();
		} else if ( $type == 'select' ) {
			$this->inputSelect();
		} else if ( $type == 'radio' ) {
			$this->showRadioOptions();
		} else if ( $type == 'checkbox' ) {
			$this->inputCheckbox( $type );
		} else if ( $type == 'richeditor' ) {
			$this->inputWPEditor();
		} else if ( $type == 'image' ) {
			$this->inputImage();
		} else {
			$this->inputDefault($type);
		}
		if ( $this->screen == 'edit-term' ) {
			echo '</td>';
		}
	}
	private function inputTextarea() {
		echo '<textarea' . $this->getAttrs() . '>' . esc_textarea($this->value) . '</textarea>';
	}
	private function inputSelect() {
		echo '<select' . $this->getAttrs() . '>';
		$this->showSelectOptions();
		echo '</select>';
	}
	private function showSelectOptions() {
		if ( ! empty( $this->options ) ) {
			foreach ( $this->options as $optionKey => $optionName ) {
				$selected = '';
				if ( $optionKey == $this->value ) {
					$selected = ' selected';
				}
				echo '<option value="' . esc_attr($optionKey) . '"' . $selected . '>' . esc_html($optionName) . '</option>';
			}
		}
	}
	private function showRadioOptions() {
		if ( ! empty( $this->options ) ) {
			$first = true;
			foreach ( $this->options as $optionKey => $optionName ) {
				if ( $first ) {
					$first = false;
				} else {
					echo '<br>';
				}
				$checked = '';
				if ( $optionKey == $this->value ) {
					$checked = ' checked';
				}
				echo '<input type="radio" value="' . esc_attr($optionKey) . '"' . $this->getAttrs() . $checked . '/>' . esc_html($optionName);
			}
		}
	}
	private function inputCheckbox( $type ) {
		$checked = '';
		if ( "1" == $this->value ) {
			$checked = ' checked';
		}
		echo '<input type="' . esc_attr($type) . '" value="1"' . $this->getAttrs() . $checked . '/>';
	}
	private function inputWPEditor() {
		$settings = array(
			'teeny'         => true,
			'textarea_rows' => 15,
			'tabindex'      => 1
		);
		wp_editor( esc_html( $this->value ), esc_textarea($this->name), $settings );
	}
	private function inputImage(){
		$imageclass='';
		if(!empty($this->value)){
			$imgsrc = wp_get_attachment_image_src($this->value, 'original');
			$imgurl = $imgsrc[0];
		} else {
		    $imageclass=' empty';
        }
		?>
		<style>
			.<?php echo $this->prefix; ?>-inputtype-image .image {
			<?php if(!empty($imgurl)): ?>
                background-image: url('<?php echo $imgurl; ?>');
			<?php endif; ?>
            }
		</style>

		<div class="image<?php echo esc_attr($imageclass); ?>" data-uploader_title="<?php esc_attr_e('Select image', 'metgs');?>" data-uploader_button_text="<?php esc_attr_e('Add', 'metgs');?>"></div>
        <div class="close"><?php esc_html_e('Delete image', 'metgs'); ?></div>
		<?php
		$this->inputDefault('hidden');
	}
	private function inputDefault($type){
		echo '<input type="' . esc_attr($type) . '" value="' . esc_attr($this->value) . '"' . $this->getAttrs() . '/>';
	}

	function saveExists() {
		return array_key_exists( $this->name, $_POST );
	}
}