(function($) {
    "use strict";
    $(function () {
        var PHALCON = window.LOYALTY || {};
        PHALCON['document'] = $(document);
        PHALCON['body'] = PHALCON.document.find('body');

        // Init Tooltips
        PHALCON.body.find('[data-toggle="tooltip"]').tooltip();

        // Init Tooltips after ajax requests, if not already done
        PHALCON.document.ajaxComplete(function (event, request, settings) {
            PHALCON.body
                .find('[data-toggle="tooltip"]')
                .not('[data-original-title]')
                .tooltip()
            ;
        });

    }); // End Dom Ready
})(jQuery);
