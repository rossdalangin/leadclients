window.runSolarCalc = function() {
    var bill = jQuery('#gp-bill').val();
    var size = jQuery('#gp-solar-size').val() || 10;
    var resultDiv = jQuery('#solar-results');
    if (bill) {
        var yearlySavings = bill * 12 * 0.85;
        var systemCost = size * 3000; // $3k per kW
        var loanPayment = (systemCost * 0.7) / 120; // 10 year mock loan
        var paybackYears = systemCost / (bill * 12);

        resultDiv.html("<div class='glass-card' style='margin-top:20px; border-color:#10B981;'><h4>Advanced Solar Analysis</h4><p>Yearly Savings: <strong>$" + Math.round(yearlySavings).toLocaleString() + "</strong></p><p>Payback: <strong>" + paybackYears.toFixed(1) + " yrs</strong></p><p>Est. Loan Payment: <strong>$" + Math.round(loanPayment) + "/mo</strong></p></div>");
    }
};

window.calcRoofEstimate = function() {
    var squares = jQuery('#gp-roof-squares').val();
    var material = jQuery('#gp-roof-material').val();
    var pitch = jQuery('#gp-roof-pitch').val();

    var basePrice = material === 'metal' ? 1200 : (material === 'premium' ? 600 : 400);
    var pitchMultiplier = pitch === 'steep' ? 1.3 : 1.0;

    var total = squares * basePrice * pitchMultiplier;
    if(squares) {
        jQuery('#roof-estimate-result').html("<div class='glass-card' style='margin-top:20px; border-color:#2563EB;'><h4>Preliminary Estimate</h4><p>Total Project Investment: <strong>$" + Math.round(total).toLocaleString() + "</strong></p><p style='font-size:12px; opacity:0.7;'>Final pricing subject to on-site inspection.</p></div>");
    }
};

function checkSymptoms() {
    var symptoms = document.getElementById('symptoms').value;
    var nonce = document.getElementById('gp_medical_nonce').value;
    var output = document.getElementById('ai-medical-advice');
    output.innerHTML = "Consulting AI Triage...";
    jQuery.post(gp_ajax.ajaxurl, { action: 'gp_check_symptoms', symptoms: symptoms, nonce: nonce }, function(res) {
        if(res.success) output.innerHTML = res.data;
    });
}

// Quiz Logic
window.nextStep = function(revenue) {
    jQuery('#gp-quiz-step-1').fadeOut(400, function() {
        jQuery('#gp-quiz-form').fadeIn();
    });
};

// Form Logic
jQuery(document).on('submit', '.gp-form', function(e) {
    e.preventDefault();
    var $form = jQuery(this);
    var action = $form.data('action');
    var formData = $form.serialize();

    var leadEmail = $form.find('[name="lead_email"]').val();
    $.post(gp_ajax.ajaxurl, {
        action: action,
        nonce: $form.find('[name="nonce"]').val(),
        lead_name: $form.find('[name="lead_name"]').val(),
        lead_email: leadEmail,
        lead_msg: $form.find('[name="lead_msg"]').val()
    }, function(res) {
        if (res.success) {
            localStorage.setItem('gp_lead_email', leadEmail);
            $form.html('<div class="success-msg">' + res.data + '</div>');
        }
    });
});

// Behavior Tracking
jQuery(document).ready(function($) {
    var email = localStorage.getItem('gp_lead_email');
    if (email) {
        $.post(gp_ajax.ajaxurl, {
            action: 'gp_log_behavior',
            email: email,
            page: window.location.pathname
        });
    }
});

// Luxury Animations & Reveals
jQuery(document).ready(function($) {
    var reveals = document.querySelectorAll('.reveal, .glass-card, .gp-hero');
    var observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
                entry.target.style.opacity = 1;
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });

    reveals.forEach(function(r) {
        r.style.opacity = 0;
        r.style.transform = 'translateY(30px)';
        r.style.transition = 'all 0.8s cubic-bezier(0.34, 1.56, 0.64, 1)';
        observer.observe(r);
    });
});

// Mobile Menu Toggle
jQuery(document).ready(function($) {
    $('.site-header .container').append('<button class="menu-toggle" style="display:none; background:none; color:inherit; border:1px solid #ddd; padding:5px 10px; font-size:18px;">☰</button>');

    if (window.innerWidth < 768) {
        $('.menu-toggle').show();
        $('.main-navigation').hide();
    }

    $('.menu-toggle').on('click', function() {
        $('.main-navigation').slideToggle();
    });
});

// Exit Intent Logic
jQuery(document).on('mouseleave', function(e) {
    if (e.clientY < 0 && !localStorage.getItem('gp_exit_shown')) {
        jQuery('#gp-exit-popup').fadeIn();
        localStorage.setItem('gp_exit_shown', 'true');
    }
});
