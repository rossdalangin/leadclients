jQuery(document).ready(function($) {
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
            } else {
                alert('Error: ' + response.data);
            }
        });
    };
});
