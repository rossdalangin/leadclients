jQuery(document).ready(function($) {
    // Lead Form Submission
    $(document).on('submit', '.gp-form', function(e) {
        e.preventDefault();
        var $form = $(this);
        var $btn = $form.find('button');
        var originalText = $btn.text();

        $btn.prop('disabled', true).text('Processing...');

        $.post(gp_ajax.ajaxurl, {
            action: $form.data('action'),
            lead_name: $form.find('input[name="lead_name"]').val(),
            lead_email: $form.find('input[name="lead_email"]').val(),
            lead_msg: $form.find('textarea[name="lead_msg"]').val(),
            nonce: $form.find('input[name="nonce"]').val()
        }, function(response) {
            if (response.success) {
                $form.html('<div class="gp-success-msg">' + response.data + '</div>');
                // Store email for behavior tracking
                localStorage.setItem('gp_lead_email', $form.find('input[name="lead_email"]').val());
            } else {
                alert(response.data);
                $btn.prop('disabled', false).text(originalText);
            }
        });
    });

    // Quiz Step Logic
    window.nextStep = function(val) {
        $('#gp-quiz-step-1').fadeOut(300, function() {
            $('#gp-quiz-form').fadeIn();
        });
    };

    // Behavioral Tracking: Send page view data to CRM
    var leadEmail = localStorage.getItem('gp_lead_email');
    if (leadEmail) {
        $.post(gp_ajax.ajaxurl, {
            action: 'gp_log_behavior',
            email: leadEmail,
            page: window.location.pathname
        });
    }

    // Exit Intent with Velocity Tracking
    var exitTriggered = false;
    $(document).on('mouseleave', function(e) {
        if (e.clientY < 0 && !exitTriggered) {
            $('#gp-exit-popup').fadeIn();
            exitTriggered = true;
        }
    });

    // Inactivity Re-engagement
    var inactivityTimer;
    function resetInactivity() {
        clearTimeout(inactivityTimer);
        inactivityTimer = setTimeout(function() {
            if(!exitTriggered) {
                console.log("GP OS: Subtle re-engagement triggered due to inactivity.");
                // Potentially shake the chat bubble or show a small tooltip
                $('#gp-chat-icon').css('animation', 'gp-pulse 1s infinite');
            }
        }, 60000); // 1 minute
    }
    $(document).on('mousemove keypress', resetInactivity);
});
