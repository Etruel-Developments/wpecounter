jQuery(document).ready(function ($) {
    // Listen for click on reset views button
    $(document).on('click', '.wpecounter-reset-views-btn', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var postId = $btn.data('postid');
        var nonce = $btn.data('nonce');

        if (!postId || !nonce) return;

        if (typeof wpecounterResetViews !== 'undefined' && wpecounterResetViews.confirm) {
            if (!confirm(wpecounterResetViews.confirm)) {
                return;
            }
        }

        $btn.addClass('is-resetting');
        $.post(
            wpecounterResetViews.ajax_url,
            {
                action: 'wpecounter_reset_views',
                post_id: postId,
                nonce: nonce
            },
            function (response) {
                $btn.removeClass('is-resetting');
                if (response && response.success) {
                    // Optionally update the views column to 0
                    var $row = $btn.closest('tr');
                    $row.find('.column-post_views').text('0');
                    // Show a temporary success message
                    $btn.css('color', 'green');
                    setTimeout(function () {
                        $btn.css('color', '');
                    }, 1000);
                } else {
                    alert(response && response.data && response.data.message ? response.data.message : 'Error resetting views.');
                }
            }
        );
    });
});