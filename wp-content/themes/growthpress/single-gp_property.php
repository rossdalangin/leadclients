<?php get_header(); ?>
<main class="site-main container">
    <?php while ( have_posts() ) : the_post(); ?>
        <article class="glass-card property-display">
            <div class="property-header">
                <h1><?php the_title(); ?></h1>
                <p class="price">$<?php echo number_format(get_post_meta(get_the_ID(), '_gp_property_price', true)); ?></p>
            </div>
            <?php the_post_thumbnail('large'); ?>
            <div class="property-details">
                <?php the_content(); ?>
            </div>
            <div class="ai-matchmaking glass-card" style="margin-top: 20px; background: #f0f9ff;">
                <h3>AI Matchmaker</h3>
                <p>Tell us what you're looking for, and see if this home is your perfect match.</p>
                <textarea id="buyer-intent" placeholder="e.g. I need a large backyard for my dogs and a quiet office space."></textarea>
                <button onclick="matchProperty()">Check Compatibility</button>
                <div id="match-result"></div>
            </div>
        </article>
    <?php endwhile; ?>
</main>
<script>
function matchProperty() {
    var intent = document.getElementById('buyer-intent').value;
    var output = document.getElementById('match-result');
    output.innerHTML = "AI is analyzing compatibility...";
    jQuery.post(gp_ajax.ajaxurl, {
        action: 'gp_property_match',
        intent: intent
    }, function(res) {
        if(res.success) output.innerHTML = res.data;
    });
}
</script>
<?php get_footer(); ?>
