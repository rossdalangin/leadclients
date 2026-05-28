jQuery(document).ready(function($) {
    // Neural Preloader Logic
    var $preloader = $('#gp-preloader');
    if ($preloader.length) {
        $('.preloader-fill').css('width', '100%');
        setTimeout(function() {
            $preloader.addClass('fade-out');
            $('body').addClass('os-synchronized');
        }, 2600);
    }

    // Scroll-Triggered Parallax Depth v4.0
    $(window).scroll(function() {
        var scrolled = $(window).scrollTop();
        $('.gp-hero').css('background-position', 'center ' + (scrolled * 0.45) + 'px');
        $('.grainy-bg').css('background-position', '0 ' + (scrolled * 0.1) + 'px');

        if (scrolled > 70) {
            $('.site-header').addClass('scrolled');
        } else {
            $('.site-header').removeClass('scrolled');
        }
    });

    // Elite Form Sequence
    $(document).on('submit', '.gp-form', function(e) {
        e.preventDefault();
        var $btn = $(this).find('button');
        var originalText = $btn.text();
        $btn.prop('disabled', true).text('EXECUTING NEURAL TRIAGE...');

        $.post(gp_ajax.ajaxurl, {
            action: $(this).data('action'),
            lead_name: $(this).find('input[name="lead_name"]').val(),
            lead_email: $(this).find('input[name="lead_email"]').val(),
            lead_msg: $(this).find('textarea[name="lead_msg"]').val(),
            nonce: $(this).find('input[name="nonce"]').val()
        }, function(res) {
            if (res.success) {
                $('.gp-form').fadeOut(500, function() {
                    $(this).html('<div style="text-align:center; padding:100px 40px;"><div style="font-size:6rem; margin-bottom:40px;">💎</div><h2 class="text-gradient" style="font-size:3rem; margin-bottom:20px;">STRATEGY NODE ACTIVATED</h2><p style="font-size:1.2rem; opacity:0.7;">AI analysis complete. A specialist is preparing your bespoke roadmap.</p></div>').fadeIn();
                });
            } else {
                $btn.prop('disabled', false).text(originalText);
            }
        });
    });

    // Smooth Reveal Engine v4.0
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('gp-revealed');
                entry.target.style.transitionDelay = (entry.target.dataset.delay || 0) + 'ms';
            }
        });
    }, { threshold: 0.12, rootMargin: "0px 0px -100px 0px" });

    $('.glass-card, section, h1, h2, .gp-btn, .wp-block-column').each(function() {
        $(this).addClass('gp-reveal');
        revealObserver.observe(this);
    });

    // Ultra Magnetic Physics v4.0 (Custom Trailing Cursor Integration)
    $(document).on('mousemove', '.gp-btn, #gp-chat-launcher, .staff-avatar', function(e) {
        const rect = this.getBoundingClientRect();
        const x = (e.clientX - rect.left - rect.width / 2) / 4.5;
        const y = (e.clientY - rect.top - rect.height / 2) / 4.5;
        $(this).css({
            'transform': `translate(${x}px, ${y}px) scale(1.1)`,
            'box-shadow': '0 30px 60px rgba(0,0,0,0.15)'
        });
    }).on('mouseleave', '.gp-btn, #gp-chat-launcher, .staff-avatar', function() {
        $(this).css({
            'transform': '',
            'box-shadow': ''
        });
    });

    // Mobile Navigation Slide-over
    $('#gp-mobile-trigger').on('click', function() { $('#gp-mobile-menu').addClass('active'); });
    $('#gp-mobile-close').on('click', function() { $('#gp-mobile-menu').removeClass('active'); });
});
