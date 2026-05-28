<footer id="colophon" class="site-footer" style="background: var(--secondary); color: white; padding: 100px 0 60px; margin-top: 120px; position: relative; overflow: hidden;">
    <div class="grainy-bg" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0.1; pointer-events: none;"></div>
	<div class="container" style="position: relative; z-index: 2;">
        <div class="wp-block-columns" style="margin-bottom: 80px;">
            <div class="wp-block-column" style="flex-basis: 40%;">
                <h2 style="color: white; margin-bottom: 20px;"><?php bloginfo('name'); ?></h2>
                <p style="color: rgba(255,255,255,0.6); font-size: 16px; line-height: 1.8; max-width: 360px;">
                    Empowering high-ticket service businesses with the world's first AI-powered Business Operating System. Scale intelligently, automate relentlessly.
                </p>
                <div style="margin-top: 30px; display: flex; gap: 20px;">
                    <span style="opacity: 0.5;">FOLLOW US</span>
                    <!-- Social Icons Mockup -->
                    <div style="display: flex; gap: 15px;">
                        <div style="width: 24px; height: 24px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                        <div style="width: 24px; height: 24px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                        <div style="width: 24px; height: 24px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                    </div>
                </div>
            </div>
            <div class="wp-block-column">
                <h4 style="color: white; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; font-size: 13px; margin-bottom: 30px;">Ecosystem</h4>
                <ul style="list-style: none; padding: 0; font-size: 15px; line-height: 2.5;">
                    <li><a href="<?php echo home_url('/services'); ?>" style="color: rgba(255,255,255,0.6); text-decoration: none; transition: color 0.3s;">Services Suite</a></li>
                    <li><a href="<?php echo home_url('/pricing'); ?>" style="color: rgba(255,255,255,0.6); text-decoration: none; transition: color 0.3s;">Investment Plans</a></li>
                    <li><a href="<?php echo home_url('/case-studies'); ?>" style="color: rgba(255,255,255,0.6); text-decoration: none; transition: color 0.3s;">Results Gallery</a></li>
                    <li><a href="<?php echo home_url('/faq'); ?>" style="color: rgba(255,255,255,0.6); text-decoration: none; transition: color 0.3s;">Intelligence Base</a></li>
                </ul>
            </div>
            <div class="wp-block-column">
                <h4 style="color: white; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; font-size: 13px; margin-bottom: 30px;">Company</h4>
                <ul style="list-style: none; padding: 0; font-size: 15px; line-height: 2.5;">
                    <li><a href="<?php echo home_url('/our-mission'); ?>" style="color: rgba(255,255,255,0.6); text-decoration: none; transition: color 0.3s;">Our Mission</a></li>
                    <li><a href="<?php echo home_url('/contact'); ?>" style="color: rgba(255,255,255,0.6); text-decoration: none; transition: color 0.3s;">Contact Command</a></li>
                    <li><a href="#" style="color: rgba(255,255,255,0.6); text-decoration: none; transition: color 0.3s;">Privacy & Ethics</a></li>
                    <li><a href="#" style="color: rgba(255,255,255,0.6); text-decoration: none; transition: color 0.3s;">Terms of Service</a></li>
                </ul>
            </div>
        </div>
		<div class="site-info" style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 40px; display: flex; justify-content: space-between; align-items: center; font-size: 14px; color: rgba(255,255,255,0.4);">
			<div>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. Elite Growth OS Deployment.</div>
            <div style="display: flex; gap: 20px;">
                <span>STATUS: SYSTEM ACTIVE</span>
                <span style="color: var(--accent);">AI CONNECTED</span>
            </div>
		</div>
	</div>
</footer>

<div class="gp-mobile-cta-bar">
    <div class="price-info">
        <span class="price-val">FREE</span>
        GROWTH STRATEGY
    </div>
    <a href="<?php echo home_url('/book-now'); ?>" class="gp-btn" style="padding: 10px 20px; font-size: 13px; border-radius: 10px;">BOOK NOW</a>
</div>

<?php wp_footer(); ?>
</body>
</html>
