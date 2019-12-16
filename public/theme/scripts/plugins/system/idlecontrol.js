$(document).ready(function() {
    // setup the dialog
    $(".container-idle-dialog").dialog({
        autoOpen: false,
        modal: true,
        width: 400,
        height: 200,
        closeOnEscape: false,
        draggable: false,
        resizable: false,
        buttons: {
            'Yes, Keep Working': function() {
                $(this).dialog('close');
                window.location = ROOT.base_url;
            },
            'No, Logoff': function() {
                // fire whatever the configured onTimeout callback is.
                // using .call(this) keeps the default behavior of "this" being the warning
                // element (the dialog in this case) inside the callback.
                $.idleTimeout.options.onTimeout.call(this);
            }
        }
    });

// cache a reference to the countdown element so we don't have to query the DOM for it on each ping.
    var $countdown = $(".container-idle-dialog-countdown");

// start the idle timer plugin
    $.idleTimeout('.container-idle-dialog', 'div.ui-dialog-buttonpane button:first', {
        idleAfter: 300, // 300 divide by 60 seconds = 5 minutes   
        pollingInterval: 2,
        keepAliveURL: ROOT.base_url,
        serverResponseEquals: 'OK',
        onTimeout: function() {
            window.location = ROOT.site_url + '/logout';
        },
        onIdle: function() {
            $(this).dialog("open");
        },
        onCountdown: function(counter) {
            $countdown.html(counter); // update the counter
        }
    });

});