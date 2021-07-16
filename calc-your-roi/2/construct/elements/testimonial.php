<?php

	$testimonialKey = array_keys(array_column($_SESSION['testimonialHolders'], 'testimonial_holder_id'),$id);

	if($testimonialKey){
		$testimonialHolder = $_SESSION['testimonialHolders'][$testimonialKey[0]];
	}
	
	$testimonialsKeys = array_keys(array_column($_SESSION['testimonialBlockquotes'], 'testimonial_holder_id'),$id);
?>
<div class="quovolve-box play">
	<div class="quotes quovolve" data-auto-play-speed="<?= $testimonialHolder['auto_play_speed'] ?>" data-transition-speed="<?= $testimonialHolder['transition_speed'] ?>">

<?php

	$firstTestimonial = true;
	foreach($testimonialsKeys as $blockquote){
?>
		<div id="blockquote" class="row" style="<?= $firstTestimonial ? 'display: block;' : 'display: none;' ?> margin: 0px;">
			<blockquote>
				<p><?= $_SESSION['testimonialBlockquotes'][$blockquote]['testimonial'] ?></p>
				<?= $_SESSION['testimonialBlockquotes'][$blockquote]['author'] ? '<p>— '.$_SESSION['testimonialBlockquotes'][$blockquote]['author'].'</p>' : '' ?>
			</blockquote>
		</div>
<?php
		$firstTestimonial = false;
	}
?>
	</div>
</div>