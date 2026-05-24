<?php get_header(); ?>
<main class="site-main container">
    <?php while ( have_posts() ) : the_post(); ?>
        <article class="glass-card">
            <h1><?php the_title(); ?></h1>
            <div class="treatment-meta">
                <span class="badge">Professional Care</span>
            </div>
            <div class="treatment-content">
                <?php the_content(); ?>
            </div>
            <div class="cta-box" style="margin-top: 30px;">
                <h3>Interested in this treatment?</h3>
                <?php echo do_shortcode('[gp_lead_form]'); ?>
            </div>
        </article>
    <?php endwhile; ?>
</main>
<?php get_footer(); ?>
