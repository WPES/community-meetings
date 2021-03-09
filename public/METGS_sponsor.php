<?php

class METGS_sponsor extends METGS_public_taxonomies
{
	function showInfo(){
		?>
		<div class="metgs_sponsor">
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