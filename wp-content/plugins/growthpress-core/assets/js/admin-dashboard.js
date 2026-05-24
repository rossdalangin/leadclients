jQuery(document).ready(function($) {
    $('.kanban-card').draggable({
        revert: "invalid",
        helper: "clone",
        start: function() { $(this).hide(); },
        stop: function() { $(this).show(); }
    });

    $('.kanban-col').droppable({
        accept: ".kanban-card",
        drop: function(event, ui) {
            var lead_id = ui.draggable.data('id');
            var stage = $(this).data('stage');
            var $col = $(this).find('.kanban-cards');

            $.post(ajaxurl, {
                action: 'gp_update_lead_stage',
                lead_id: lead_id,
                stage: stage,
                gp_nonce: gp_admin.nonce
            }, function(response) {
                if (response.success) {
                    ui.draggable.appendTo($col).css({top: '0', left: '0'}).show();
                }
            });
        }
    });

    window.setupNiche = function() {
        var niche = $('#gp-niche-select').val();
        $.post(ajaxurl, {
            action: 'gp_setup_niche',
            niche: niche,
            gp_nonce: gp_admin.nonce
        }, function(response) {
            if (response.success) {
                alert(response.data);
                location.reload();
            }
        });
    };
});
