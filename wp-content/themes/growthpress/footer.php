<footer id="colophon" class="site-footer" style="background:#1E293B; color:white; padding:80px 0 40px; margin-top:100px;">
	<div class="container">
        <div class="wp-block-columns" style="margin-bottom:60px;">
            <div class="wp-block-column">
                <h3 style="color:white;"><?php bloginfo('name'); ?></h3>
                <p style="color:#94a3b8; font-size:14px; max-width:300px;">Powered by GrowthPress OS—the world's most advanced AI-powered business growth engine.</p>
            </div>
            <div class="wp-block-column">
                <h4 style="color:white;">Quick Links</h4>
                <ul style="list-style:none; padding:0; font-size:14px; color:#94a3b8;">
                    <li><a href="<?php echo home_url('/services'); ?>" style="color:inherit; text-decoration:none;">Our Services</a></li>
                    <li><a href="<?php echo home_url('/pricing'); ?>" style="color:inherit; text-decoration:none;">Pricing Plans</a></li>
                    <li><a href="<?php echo home_url('/contact'); ?>" style="color:inherit; text-decoration:none;">Contact Us</a></li>
                </ul>
            </div>
            <div class="wp-block-column">
                <h4 style="color:white;">Legal</h4>
                <ul style="list-style:none; padding:0; font-size:14px; color:#94a3b8;">
                    <li><a href="#" style="color:inherit; text-decoration:none;">Privacy Policy</a></li>
                    <li><a href="#" style="color:inherit; text-decoration:none;">Terms of Service</a></li>
                </ul>
            </div>
        </div>
		<div class="site-info" style="border-top:1px solid #334155; padding-top:40px; text-align:center; font-size:13px; color:#64748b;">
			&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. Built for Growth.
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
