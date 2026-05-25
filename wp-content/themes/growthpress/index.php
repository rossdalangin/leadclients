<?php
/**
 * Main template file
 */
get_header();
?>
<main id="primary" class="site-main" style="padding: 60px 0;">
    <div class="container">
        <?php if ( is_home() && ! is_front_page() ) : ?>
            <header style="margin-bottom: 50px;">
                <h1 class="page-title"><?php single_post_title(); ?></h1>
            </header>
        <?php endif; ?>

        <?php
        if ( have_posts() ) :
            while ( have_posts() ) :
                the_post();
                if ( is_singular() ) : ?>
                    <article id="post-<?php the_ID(); ?>" <?php body_class(); ?>>
                        <?php if ( ! is_front_page() ) : ?>
                            <header class="entry-header" style="margin-bottom:30px;">
                                <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <div class="post-thumbnail" style="margin-top:20px; border-radius:20px; overflow:hidden;">
                                        <?php the_post_thumbnail( 'large', array( 'style' => 'width:100%; height:auto;' ) ); ?>
                                    </div>
                                <?php endif; ?>
                            </header>
                        <?php endif; ?>

                        <div class="entry-content">
                            <?php the_content(); ?>
                        </div>
                    </article>
                <?php else : ?>
                    <div class="glass-card" style="margin-bottom:30px;">
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <?php the_excerpt(); ?>
                        <a href="<?php the_permalink(); ?>" class="button button-small">Read More</a>
                    </div>
                <?php endif;
            endwhile;
        endif;
        ?>
    </div>
</main>
<?php
get_footer();
