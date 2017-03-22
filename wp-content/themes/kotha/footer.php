
<footer id="footer">
	<div class="container">
		<div class="row">
			<div class="footer-top"><?php dynamic_sidebar('footer-widget'); ?></div>
		</div>
	</div>

	<div class="copy-right-text text-center">
		<?php if(get_theme_mod('kotha_footer_copyright')): ?>
			<p><?php
			$copyright = get_theme_mod('kotha_footer_copyright');
			$allowed_tags = array(
				'strong' => array(),
				'a' => array(
					'href' => array(),
					'title' => array()
				)
			);
			echo wp_kses( $copyright, $allowed_tags ); ?></p>
		<?php endif; ?>
	</div><!-- /Copyright Text -->
</footer><!-- /#Footer -->

<?php if (!get_theme_mod('kotha_back_to_top')): ?>
    <div class="scroll-up">
        <a href="#"><i class="fa fa-angle-up"></i></a>
    </div>
<?php endif; ?>
<!-- Scroll Up -->

<?php wp_footer(); ?>

</body>
</html>
