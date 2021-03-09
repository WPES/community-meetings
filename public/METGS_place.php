<?php

class METGS_place extends METGS_public_taxonomies
{
	function showInfo(){
		?>
		<div class="metgs_place">
			<?php
			echo $this->getName();
			echo $this->getDescription();
			var_dump($this->getSocialLinks());
			echo $this->getImageHTML();
			echo $this->getFormattedAddress();
			?>
		</div>
		<?php
	}

	function getFormattedAddress(){
	    $formattedAddress = '';
		$formattedAddress .=  $this->getStreet();
		$formattedAddress .= $this->getAddressDetails();
		$formattedAddress .= $this->getPostalCode();
		$formattedAddress .= $this->getCity();
		$formattedAddress .= $this->getState();
		$formattedAddress .= $this->getCountryNativeName();
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