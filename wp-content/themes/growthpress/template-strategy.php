<?php
/**
 * Template Name: AI Strategy Session
 */
get_header(); ?>
<main class="site-main">
    <section class="hero glass-card container" style="margin-top: 50px; text-align: center;">
        <h1>Free AI Business Strategy Session</h1>
        <p>Book a 15-minute discovery call to see how AI can automate your specific business niche.</p>
        <div style="max-width: 600px; margin: 0 auto;">
            <?php echo do_shortcode('[gp_booking_form]'); ?>
        </div>
    </section>

    <section class="details container" style="margin-top: 50px;">
        <div class="glass-card">
            <h3>What you'll get:</h3>
            <ul>
                <li>Custom AI Readiness Assessment</li>
                <li>Live Demo of the GrowthPress OS</li>
                <li>Immediate Growth Roadmap</li>
            </ul>
        </div>
    </section>
</main>
<?php get_footer(); ?>
