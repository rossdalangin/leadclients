function calculateSolarROI() {
    var bill = document.getElementById('monthly_bill').value;
    var resultDiv = document.getElementById('gp-result');
    if (bill) {
        var yearlySavings = bill * 12 * 0.8;
        var paybackYears = 15000 / (bill * 12);
        resultDiv.innerHTML = "<p>Estimated Yearly Savings: $" + yearlySavings.toFixed(2) + "</p><p>Payback Period: " + paybackYears.toFixed(1) + " years</p>";
    }
}

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

// Exit Intent Logic
jQuery(document).on('mouseleave', function(e) {
    if (e.clientY < 0 && !localStorage.getItem('gp_exit_shown')) {
        jQuery('#gp-exit-popup').fadeIn();
        localStorage.setItem('gp_exit_shown', 'true');
    }
});
