<?php

class METGS_speaker extends METGS_public_taxonomies
{
	function showInfo(){
		?>
		<div class="metgs_speaker">
			<?php
			echo $this->getName();
			echo $this->getDescription();
			var_dump($this->getSocialLinks());
			echo $this->getImageHTML();
			?>
		</div>
		<?php
	}



}