jQuery(document).ready(function($) {
    // Niche Setup
    window.setupNiche = function() {
        if ( !confirm("This will automatically generate core business pages (Home, Services, Contact) and sample demo data for your niche. Continue?") ) return;

        var niche = $('#gp-niche-select').val();
        $.post(ajaxurl, {
            action: 'gp_setup_niche',
            niche: niche,
            gp_nonce: gp_admin.nonce
        }, function(response) {
            if (response.success) {
                alert("OS Initialized Successfully! Core pages created.");
                location.reload();
            }
        });
    };

    // Global Micro-interactions
    $('.glass-card').addClass('gp-reveal');

    // Kanban Drag & Drop
    if ($('.kanban-cards').length > 0) {
        $('.kanban-card').on('click', function(e) {
            if($(e.target).closest('a, button').length) return;
            const leadId = $(this).data('id');
            const overlay = $('<div class="gp-modal-overlay"><div class="gp-modal"><div class="modal-loader" style="text-align:center; padding:100px; font-weight:950; letter-spacing:2px; opacity:0.4;">NEURAL BRIEF SYNCHRONIZING...</div></div></div>').appendTo('body');

            $.post(ajaxurl, {
                action: 'gp_get_lead_brief',
                lead_id: leadId,
                gp_nonce: gp_admin.nonce
            }, function(res) {
                if(res.success) {
                    overlay.find('.gp-modal').html(res.data.html);
                }
            });

            overlay.on('click', function(e) {
                if($(e.target).is('.gp-modal-overlay')) $(this).fadeOut(function(){ $(this).remove(); });
            });
        });

        $('.kanban-card').draggable({
            revert: "invalid",
            helper: "clone",
            cursor: "move",
            start: function() { $(this).hide(); },
            stop: function() { $(this).show(); }
        });

        $('.kanban-col').droppable({
            accept: ".kanban-card",
            drop: function(event, ui) {
                var leadId = ui.draggable.data('id');
                var newStage = $(this).data('stage');
                var $cardsContainer = $(this).find('.kanban-cards');

                ui.draggable.appendTo($cardsContainer).css({
                    top: '0px',
                    left: '0px'
                });

                $.post(ajaxurl, {
                    action: 'gp_update_lead_stage',
                    lead_id: leadId,
                    stage: newStage,
                    gp_nonce: gp_admin.nonce
                }, function(response) {
                    if (!response.success) alert("Failed to update lead stage.");
                });
            }
        });
    }

    // Lead Generation for Content Studio
    window.generateContent = function() {
        var $out = $('#gp-studio-output');
        $out.html('AI Strategist is calculating...');

        $.post(ajaxurl, {
            action: 'gp_generate_content',
            content_type: $('#gp-content-type').val(),
            topic: $('#gp-content-topic').val(),
            gp_nonce: gp_admin.nonce
        }, function(res) {
            if (res.success) {
                $out.html('<div class="ai-response">' + res.data.replace(/\n/g, '<br>') + '</div>');
            } else {
                $out.html('Error generating content.');
            }
        });
    };
});
