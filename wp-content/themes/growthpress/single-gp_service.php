<?php
/**
 * Single Service Line Template - GrowthPress Elite
 */
get_header(); ?>

<main id="primary" class="site-main grainy-bg" style="padding-top:120px; padding-bottom:120px;">
    <div class="container">
        <?php while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <div class="wp-block-columns are-vertically-aligned-center">
                    <div class="wp-block-column" style="flex-basis: 55%;">
                        <div style="font-size:12px; font-weight:900; color:var(--primary); text-transform:uppercase; letter-spacing:3px; margin-bottom:20px;">ELITE SERVICE LINE</div>
                        <h1 class="text-gradient" style="margin-bottom:30px;"><?php the_title(); ?></h1>
                        <div class="entry-content" style="font-size:1.25rem; line-height:1.8; opacity:0.8; margin-bottom:40px;">
                            <?php the_content(); ?>
                        </div>
                        <div style="display:flex; gap:20px;">
                            <a href="#booking" class="gp-btn">Secure My Appointment</a>
                            <a href="/pricing" class="gp-btn" style="background:transparent; border:2px solid var(--primary); color:var(--primary) !important;">View Plans</a>
                        </div>
                    </div>
                    <div class="wp-block-column">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="glass-card" style="padding:10px; border-radius:40px;">
                                <?php the_post_thumbnail('large', array('style' => 'border-radius:30px; display:block; width:100%; height:auto;')); ?>
                            </div>
                        <?php else: ?>
                            <div class="glass-card" style="padding:60px; text-align:center; background:var(--primary-glow);">
                                <div style="font-size:6rem; margin-bottom:20px;">💎</div>
                                <h3>Industry Standard Excellence</h3>
                                <p>Our <?php the_title(); ?> methodology is built for market dominance and high-ticket returns.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Strategic Benefits Section -->
                <div style="margin-top:120px;">
                    <h2 class="text-gradient" style="text-align:center; margin-bottom:60px;">The GrowthPress Advantage</h2>
                    <div class="wp-block-columns">
                        <div class="wp-block-column glass-card" style="text-align:center;">
                            <div style="font-size:2.5rem; margin-bottom:15px;">🤖</div>
                            <h4>AI Integration</h4>
                            <p>Every service is backed by our proprietary triage and qualification engine.</p>
                        </div>
                        <div class="wp-block-column glass-card" style="text-align:center;">
                            <div style="font-size:2.5rem; margin-bottom:15px;">📊</div>
                            <h4>ROI Focused</h4>
                            <p>We prioritize activities that generate the highest measurable return on investment.</p>
                        </div>
                        <div class="wp-block-column glass-card" style="text-align:center;">
                            <div style="font-size:2.5rem; margin-bottom:15px;">⚡</div>
                            <h4>Rapid Execution</h4>
                            <p>Proprietary workflows ensure your business stays ahead of local competitors.</p>
                        </div>
                    </div>
                </div>

                <!-- Booking Section -->
                <div id="booking" style="margin-top:120px; max-width:800px; margin-left:auto; margin-right:auto;">
                    <div class="glass-card" style="padding:60px; text-align:center; border:2px solid var(--primary);">
                        <h2 class="text-gradient">Ready to Get Started?</h2>
                        <p style="margin-bottom:40px;">Schedule your high-stakes consultation for <?php the_title(); ?> below.</p>
                        [gp_booking_form]
                    </div>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
