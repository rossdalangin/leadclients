<?php get_header(); ?>
<main class="site-main container">
    <?php while ( have_posts() ) : the_post();
        $price = get_post_meta(get_the_ID(), '_gp_property_price', true);
        $tour_url = get_post_meta(get_the_ID(), '_gp_virtual_tour', true); ?>
        <article class="glass-card property-display">
            <div class="property-header">
                <h1><?php the_title(); ?></h1>
                <p class="price">$<?php echo number_format($price ?: 0); ?></p>
            </div>
            <?php if ( $tour_url ) : ?>
                <div class="virtual-tour-embed" style="margin-bottom: 20px;">
                    <iframe src="<?php echo esc_url($tour_url); ?>" width="100%" height="400" frameborder="0"></iframe>
                </div>
            <?php else : the_post_thumbnail('large'); endif; ?>

            <div class="property-details"><?php the_content(); ?></div>

            <div class="ai-matchmaking glass-card" style="margin-top: 20px;">
                <h3>AI Matchmaker</h3>
                <textarea id="buyer-intent" placeholder="Tell us what you need in a home..."></textarea>
                <button onclick="matchProperty()">Check Compatibility</button>
                <div id="match-result"></div>
            </div>
        </article>
    <?php endwhile; ?>
</main>
<script>
function matchProperty() {
    jQuery('#match-result').text('Analyzing...');
    jQuery.post(gp_ajax.ajaxurl, { action: 'gp_property_match', intent: jQuery('#buyer-intent').val() }, function(res) {
        if(res.success) jQuery('#match-result').html(res.data);
    });
}
</script>
<?php get_footer(); ?>
