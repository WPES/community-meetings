<?php

class METGS_speaker extends METGS_public_taxonomies
{
	function showInfo(){
		?>
		<div class="metgs-speaker metgs-item">
            <?php echo $this->getImageHTML(); ?>
            <div class="metgs-data">
                <?php if(!empty($this->getName())): ?>
                <div class="metgs-name"><?php echo $this->getName(); ?></div>
                <?php endif; ?>
	            <?php if(!empty($this->getDescription())): ?>
                    <div class="metgs-description"><?php echo $this->getDescription(); ?></div>
	            <?php endif; ?>
	            <?php echo $this->getSocialLinksHTML(); ?>
            </div>
		</div>
		<?php
	}



}