<?php defined('ABSPATH') or die('Not today.');

class METGS_sponsor extends METGS_public_taxonomies
{
	function showInfo(){
		?>
        <div class="metgs-sponsor metgs-item">
			<?php echo $this->getImageHTML(); ?>
            <div class="metgs-data">
	            <?php
	            if(!is_tax()):
		            $nameWithUrl = $this->getNameWithURL();
		            if(!empty($nameWithUrl)): ?>
                        <div class="metgs-name"><?php echo $nameWithUrl; ?></div>
		            <?php
		            endif;
	            endif; ?>
	            <?php if(!empty($this->getClaim())): ?>
                    <div class="metgs-claim"><?php echo esc_html($this->getClaim()); ?></div>
	            <?php endif; ?>
				<?php if(!empty($this->getDescription())): ?>
                    <div class="metgs-description"><?php echo esc_html($this->getDescription()); ?></div>
				<?php endif; ?>
				<?php echo $this->getSocialLinksHTML(); ?>
            </div>
        </div>
		<?php
	}

	function getClaim(){
	    return $this->getValue($this->prefix.'_claim');
	}



}