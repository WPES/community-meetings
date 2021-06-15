<?php
get_header();
$description = get_the_archive_description();
?>

<?php if ( have_posts() ) : ?>

	<header class="page-header">
		<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
		<?php if ( $description ) : ?>
			<div class="archive-description"><?php echo wp_kses_post( wpautop( $description ) ); ?></div>
		<?php endif; ?>
	</header><!-- .page-header -->

	<?php
    while ( have_posts() ) : ?>
		<?php the_post(); ?>
		<?php get_template_part('content'); ?>
	<?php endwhile; ?>

	<?php the_posts_pagination(); ?>

<?php else : ?>

<?php endif; ?>

<?php get_footer(); ?>
