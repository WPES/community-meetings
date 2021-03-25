<?php defined('ABSPATH') or die('Not today.');

class METGS_place extends METGS_public_taxonomies
{
	function showInfo(){
		?>
        <div class="metgs-place metgs-item">
			<?php echo $this->getImageHTML('placeimg'); ?>
            <div class="metgs-data">
				<?php
                if(!is_tax()):
                    $nameWithUrl = $this->getNameWithURL();
				    if(!empty($nameWithUrl)): ?>
                        <div class="metgs-name"><?php echo esc_html($nameWithUrl); ?></div>
				        <?php
                    endif;
				endif; ?>
				<?php if(!empty($this->getFormattedAddress())): ?>
                    <div class="metgs-address"><?php echo esc_html($this->getFormattedAddress()); ?></div>
				<?php endif; ?>
				<?php if(!empty($this->getDescription())): ?>
                    <div class="metgs-description"><?php echo esc_html($this->getDescription()); ?></div>
				<?php endif; ?>
				<?php echo $this->getSocialLinksHTML(); ?>
            </div>
        </div>
		<?php
	}

	function getFormattedAddress(){
	    $formattedAddress = '';
	    $data=$this->getStreet();
	    if(!empty($data)){
		    $formattedAddress .= '<span class="metgs-address-street">'.esc_html($data).'</span>';
	    }
		$data=$this->getAddressDetails();
		if(!empty($data)){
			$formattedAddress .= '<span class="metgs-address-details">'.esc_html($data).'</span>';
		}
		$locationArray=array();
		$data=$this->getPostalCode();
		if(!empty($data)){
			$locationArray[] = '<span class="metgs-address-postalcode">'.esc_html($data).'</span>';
		}
		$data=$this->getCity();
		if(!empty($data)){
			$locationArray[] = '<span class="metgs-address-city">'.esc_html($data).'</span>';
		}
		$data=$this->getState();
		if(!empty($data)){
			$locationArray[] = '<span class="metgs-address-state">'.esc_html($data).'</span>';
		}
		$formattedAddress .= implode(', ', $locationArray);
		$data=$this->getCountryNativeName();
		if(!empty($data)){
		    if(!empty($locationArray)){
			    $formattedAddress .= ' - ';
		    }
			$formattedAddress .= '<span class="metgs-address-country">'.esc_html($data).'</span>';
		}
		return $formattedAddress;
	}

	function getStreet(){
	    return $this->getValue($this->prefix.'_street');
	}

	function getAddressDetails(){
		return $this->getValue($this->prefix.'_address_details');
	}

	function getPostalCode(){
		return $this->getValue($this->prefix.'_cp');
	}

	function getCity(){
		return $this->getValue($this->prefix.'_city');
	}

	function getState(){
		return $this->getValue($this->prefix.'_state');
	}

	function getCountryID(){
		return $this->getValue($this->prefix.'_country');
	}

	function getCountryNativeName(){
	    $countryID = $this->getCountryID();
	    if(!empty($countryID)){
	        $countriesObj = new METGS_functions_countries();
	        return $countriesObj->getNativeName($countryID);
	    }
	    return '';
	}

}