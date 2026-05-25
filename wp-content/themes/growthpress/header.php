<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header id="masthead" class="site-header">
	<div class="container" style="display:flex; justify-content:space-between; align-items:center;">
		<div class="site-branding">
			<?php if(has_custom_logo()) { the_custom_logo(); } else { echo '<h2 style="margin:0;">' . get_bloginfo('name') . '</h2>'; } ?>
		</div>
		<nav id="site-navigation" class="main-navigation">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'menu_id'        => 'primary-menu',
                'container'      => false,
                'fallback_cb'    => false,
			) );
			?>
		</nav>
        <div class="header-cta" style="display: flex; gap: 15px; align-items: center;">
            <a href="<?php echo home_url('/book-now'); ?>" class="gp-btn" style="padding: 10px 20px; font-size: 14px;">Book Now</a>
        </div>
	</div>
</header>
