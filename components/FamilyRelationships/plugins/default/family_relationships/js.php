//<script>
Ossn.register_callback('ossn', 'init', 'family_member_request_buttons');
Ossn.register_callback('ossn', 'init', 'family_member_friend_picker');

function family_member_friend_picker() {
    $(document).ready(function() {
        if ($.isFunction($.fn.tokenInput)) {
            $("#familysearch-family-member, #relation-family-member").tokenInput(Ossn.site_url + "friendpicker", {
                placeholder: Ossn.Print('family:relationship:search:family:member'),
                hintText: false,
                tokenLimit: 1,
                propertyToSearch: "first_name",
                resultsFormatter: function(item) {
                    return "<li>" + "<img src='" + item.imageurl + "' title='" + item.first_name + " " + item.last_name + "' height='25px' width='25px' />" + "<div style='display: inline-block; padding-left: 10px;'><div class='full_name' style='font-weight:bold;color:#2B5470;'>" + item.first_name + " " + item.last_name + "</div></div></li>"
                },
                tokenFormatter: function(item) {
                    return "<li><p>" + item.first_name + " " + item.last_name + "</p></li>"
                },
            });
        }
    });
}

function family_member_request_buttons() {
    $(document).ready(function() {
        var cYear = (new Date).getFullYear();
        var alldays = Ossn.Print('datepicker:days');
        var shortdays = alldays.split(",");
        var allmonths = Ossn.Print('datepicker:months');
        var shortmonths = allmonths.split(",");

        var datepick_args = {
            changeMonth: true,
            changeYear: true,
            dateFormat: 'mm/dd/yy',
            yearRange: '1900:' + cYear,
        };

        if (Ossn.isLangString('datepicker:days')) {
            datepick_args['dayNamesMin'] = shortdays;
        }
        if (Ossn.isLangString('datepicker:months')) {
            datepick_args['monthNamesShort'] = shortmonths;
        }
        $("#relation-status-date").datepicker(datepick_args);

        $('body').on('click', '.add-family-member', function() {
            $('.family-member-add-form').toggle();
        });
        $('body').on('click', '.add-relationship-member', function() {
            $('.relation-member-add-form').toggle();
        });
        $('body').on('change', '.relation-status-type', function() {
            $type = $(this).val();
            console.log($type);
            switch ($type) {
                case 'Single':
                case 'Separated':
                    $('.relation-status-inputs-level2').find('input').val("");
                    $('.relation-status-inputs-level2').find('select').val("");
                    $('.relation-status-inputs-level2').hide();
                    break;
                default:
                    $('.relation-status-inputs-level2').show();
            }
        });

    });
    $(document).ready(function() {
        $('body').on('click', '.family-member-accept', function() {
            $guid = $(this).attr('data-guid');
            var $container = $('#family-member-row-' + $guid);
            $privacy = $container.find('.family-member-privacy').val();
            Ossn.PostRequest({
                url: Ossn.site_url + "action/family/member/accept?guid=" + $guid + "&privacy=" + $privacy,
                beforeSend: function() {
                    $container.find('.family-member-request-btn').hide();
                    $container.find('.ossn-loading').show();
                },
                callback: function(callback) {
                    if (callback == 1) {
                        $container.fadeOut('slow');
						window.location.reload(true);
                    } else {
                        $container.find('.family-member-request-btn').show();
                        $container.find('.ossn-loading').hide();
                    }
                }
            });
        });
        $('body').on('click', '.family-relation-accept', function() {
            $guid = $(this).attr('data-guid');
            var $container = $('#family-member-row-' + $guid);
            $privacy = $container.find('.family-member-privacy').val();
            Ossn.PostRequest({
                url: Ossn.site_url + "action/family/relation/accept?guid=" + $guid + "&privacy=" + $privacy,
                beforeSend: function() {
                    $container.find('.family-member-request-btn').hide();
                    $container.find('.ossn-loading').show();
                },
                callback: function(callback) {
                    if (callback == 1) {
                        $container.fadeOut('slow');
						window.location.reload(true);
                    } else {
                        $container.find('.family-member-request-btn').show();
                        $container.find('.ossn-loading').hide();
                    }
                }
            });
        });
        $('body').on('change', '.family-members-edit', function() {
            $guid = $(this).attr('data-guid');
            $privacy = $(this).val();
            Ossn.PostRequest({
                url: Ossn.site_url + "action/family/member/edit?guid=" + $guid + "&privacy=" + $privacy,
                callback: function(callback) {}
            });
        });
        $('body').on('change', '.family-relation-privacy', function() {
            $guid = $(this).attr('data-guid');
            $privacy = $(this).val();
            Ossn.PostRequest({
                url: Ossn.site_url + "action/family/relation/edit?guid=" + $guid + "&privacy=" + $privacy,
                callback: function(callback) {}
            });
        });
    });
}