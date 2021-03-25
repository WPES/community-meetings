<?php defined('ABSPATH') or die('Not today.');

class METGS_speaker extends METGS_public_taxonomies
{
	function showInfo(){
		?>
		<div class="metgs-speaker metgs-item">
            <?php echo $this->getImageHTML('avatar'); ?>
            <div class="metgs-data">
	            <?php
	            if(!is_tax()):
		            $nameWithUrl = $this->getNameWithURL();
		            if(!empty($nameWithUrl)): ?>
                        <div class="metgs-name"><?php echo esc_html($nameWithUrl); ?></div>
		            <?php
		            endif;
	            endif; ?>
	            <?php if(!empty($this->getDescription())): ?>
                    <div class="metgs-description"><?php echo esc_html($this->getDescription()); ?></div>
	            <?php endif; ?>
	            <?php echo $this->getSocialLinksHTML(); ?>
            </div>
		</div>
		<?php
	}



}