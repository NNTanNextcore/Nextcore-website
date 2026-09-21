(function ($) {
    'use strict';

    $(function () {
        var $sections = $('#nextcore-team-sections');
        var $status = $('#nextcore-team-order-status');

        function collectOrder() {
            var payload = { sections: [], order: {} };
            $sections.children('.nextcore-team-section-box').each(function () {
                var $section = $(this);
                var sectionId = $section.data('section-id');
                payload.sections.push(sectionId);
                payload.order[sectionId] = $section.find('.nextcore-team-sortable').children('li').map(function () {
                    return $(this).data('id');
                }).get();
            });
            return payload;
        }

        function saveOrder() {
            var payload = collectOrder();
            $status.text(NextcoreTeamOrder.saving).removeClass('is-error is-success');
            $.post(NextcoreTeamOrder.ajaxUrl, {
                action: 'nextcore_team_save_order',
                nonce: NextcoreTeamOrder.nonce,
                sections: payload.sections,
                order: payload.order
            }).done(function (response) {
                if (response && response.success) {
                    $status.text(NextcoreTeamOrder.saved).addClass('is-success');
                } else {
                    $status.text(NextcoreTeamOrder.error).addClass('is-error');
                }
            }).fail(function () {
                $status.text(NextcoreTeamOrder.error).addClass('is-error');
            });
        }

        if ($sections.length) {
            $sections.sortable({
                handle: '.nextcore-team-section-handle',
                placeholder: 'nextcore-team-section-placeholder',
                update: saveOrder
            });
        }

        $('.nextcore-team-sortable').sortable({
            connectWith: '.nextcore-team-sortable',
            handle: '.nextcore-team-handle',
            placeholder: 'nextcore-team-sort-placeholder',
            update: function (event, ui) {
                if (this === ui.item.parent()[0]) {
                    saveOrder();
                }
            }
        });

        var $homeMembers = $('#nextcore-home-members');
        var homeMemberTemplate = $('#nextcore-home-member-template').html();

        if ($homeMembers.length) {
            $homeMembers.sortable({
                handle: '.nextcore-home-member-handle',
                placeholder: 'nextcore-home-member-placeholder'
            });

            $('#nextcore-add-home-member').on('click', function () {
                $homeMembers.append(homeMemberTemplate);
            });

            $homeMembers.on('click', '.nextcore-remove-home-member', function () {
                $(this).closest('.nextcore-home-member-row').remove();
            });
        }

        var $networks = $('#nextcore-social-networks');
        var nextNetworkIndex = 0;
        $networks.find('input[name$="[label]"]').each(function () {
            var match = this.name.match(/\[social_networks\]\[(\d+)\]/);
            if (match) {
                nextNetworkIndex = Math.max(nextNetworkIndex, Number(match[1]) + 1);
            }
        });
        $('#nextcore-add-social-network').on('click', function () {
            var index = nextNetworkIndex++;
            var row = '<div class="nextcore-social-network-row" style="grid-template-columns:1fr 1fr 110px 110px auto">' +
                '<input type="text" name="nextcore_team_settings[social_networks][' + index + '][label]" placeholder="VI">' +
                '<input type="text" name="nextcore_team_settings[social_networks][' + index + '][label_en]" placeholder="EN">' +
                '<input type="text" name="nextcore_team_settings[social_networks][' + index + '][key]" placeholder="key">' +
                '<input type="text" name="nextcore_team_settings[social_networks][' + index + '][icon]" placeholder="icon">' +
                '<button type="button" class="button nextcore-remove-social-network">Xóa</button>' +
                '</div>';
            $networks.append(row);
        });
        $networks.on('click', '.nextcore-remove-social-network', function () {
            $(this).closest('.nextcore-social-network-row').remove();
        });
    });
})(jQuery);
