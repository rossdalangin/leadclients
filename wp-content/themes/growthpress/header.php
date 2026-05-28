<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header id="masthead" class="site-header">
	<div class="container" style="display:flex; justify-content:space-between; align-items:center;">
		<div class="site-branding">
			<?php if(has_custom_logo()) { the_custom_logo(); } else { echo '<h2 style="margin:0; font-weight:900; letter-spacing:-0.05em;">' . get_bloginfo('name') . '</h2>'; } ?>
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
        <div class="header-cta" style="display: flex; gap: 20px; align-items: center;">
            <div id="gp-theme-toggle" class="theme-toggle" title="Toggle Dark/Light Mode">
                <svg class="sun" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                <svg class="moon" style="display:none;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
            </div>
            <a href="<?php echo home_url('/book-now'); ?>" class="gp-btn" style="padding: 12px 24px; font-size: 14px; border-radius:100px;">Book Session</a>
        </div>
	</div>
</header>
<style>
    .theme-toggle { cursor: pointer; color: var(--text); opacity: 0.6; transition: opacity 0.2s; }
    .theme-toggle:hover { opacity: 1; }
</style>
<script>
    const toggle = document.getElementById('gp-theme-toggle');
    const sun = toggle.querySelector('.sun');
    const moon = toggle.querySelector('.moon');

    // Check for saved theme
    if (localStorage.getItem('gp_theme') === 'dark') {
        document.body.classList.add('dark-theme');
        sun.style.display = 'none';
        moon.style.display = 'block';
    }

    toggle.addEventListener('click', () => {
        document.body.classList.toggle('dark-theme');
        const isDark = document.body.classList.contains('dark-theme');
        localStorage.setItem('gp_theme', isDark ? 'dark' : 'light');

        sun.style.display = isDark ? 'none' : 'block';
        moon.style.display = isDark ? 'block' : 'none';
    });
</script>
<div id="content" class="site-content grainy-bg">
