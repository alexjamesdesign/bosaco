jQuery(function ($) {
    "use strict";
    $(document).ready(function () {
        $(document).on('click', '.adv-ld-panel__edit-row', function (e) {
            e.preventDefault();
            var parent = $(this).parents('.adv-ld-panel__row');
            buildFilledRow(parent.attr('data-id'), $(this).parents('.adv-ld-panel__row'));
        });

        $(document).on('click', '.adv-ld-panel__delete-row', function (e) {
            e.preventDefault();
            var parent = $(this).parents('.adv-ld-panel__row');
            var id = parent.data('id');
            if (id == '' || id == null) {
                parent.remove();
            } else {
                $('.adv-confirm-popup').attr('data-id', id).show();
            }
        });

        $('.adv-confirm-popup__reject').click(function(e) {
            e.preventDefault();
            $(this).parent().parent().attr('data-id', '').hide();
        });

        $('.adv-confirm-popup__confirm').click(function (e) {
            e.preventDefault();
            var parent = $(this).parent().parent();
            var id = parent.attr('data-id');
            $.ajax({
                type: 'post',
                url: ALDajax.ajaxurl,
                dataType: 'json',
                data: {
                    'action': 'delete_number',
                    'id': id
                },
                success: function (result) {
                    $('.adv-ld-panel__row[data-id=' + id + ']').remove();
                    parent.attr('data-id', '').hide();
                }
            });
        });

        $(document).on('submit', '.adv-ld__number-form', function (e) {
            e.preventDefault();

            var id = $(this).parent().data('id');
            var row = $(this).parent();

            var fData = new FormData(this);
            fData.append('action', 'save_number');
            if (id) {
                fData.append('id', id);
            }

            $.ajax({
                type: 'post',
                url: ALDajax.ajaxurl,
                dataType: 'json',
                processData: false,
                contentType: false,
                data: fData,
                success: function (result) {
                    $.get(ALDajax.plugin_url + 'resources/views/admin/ld/plain-row.twig', function (data) {
                        var alter = row.html(data);
                        alter.find('.adv-ld-panel__location-name').html(result.number.location_name);
                        alter.find('.adv-ld-panel__location-label').html(result.number.location_label);
                        alter.find('.adv-ld-panel__calltag').html(result.number.calltag);
                        alter.find('.adv-ld-panel__seo').html(result.number.seo);
                        alter.find('.adv-ld-panel__ppc').html(result.number.ppc);
                        alter.find('.adv-ld-panel__insights').html(result.number.insights);
                        alter.find('.adv-ld-panel__tracking-label').html(result.number.tracking_label);
                        row.attr('data-id', result.number.id);
                    });
                }
            });
        });

        /**
         * Control the hover popups across the advanced ld plugin
         */
        $('.adv-ld-panel__info-pop').hover(
            function () {
                $('.adv-ld-panel__pop-box').show();
                $('.adv-ld-panel__pop-box').css('left', ($(this).position().left - 100));
                $('.adv-ld-panel__pop-box').css('top', ($(this).offset().top - 30));
                $('.adv-ld-panel__pop-box').css('opacity', 1);
                $('.adv-ld-panel__pop-box').html($(this).data('info'));
            },
            function () {
                $('.adv-ld-panel__pop-box').hide();
                $('.adv-ld-panel__pop-box').css('opacity', 0);
            }
        );

        $('.adv-ld__new-row').click(function (e) {
            e.preventDefault();
            $('.adv-ld-panel__row--example').clone().removeClass('adv-ld-panel__row--example').appendTo('#adv-number-list').css('display', 'flex');
        });

        $(document).on("change paste keyup", "input[name=location-name]", function () {
            var val = $(this).val();
            var start = this.selectionStart,
                end = this.selectionEnd;
            val = val.toLowerCase();
            val = val.replace(" ", "-");
            val = val.replace("_", "-");
            $(this).val(val);
            this.setSelectionRange(start, end);
        });
    });

    /**
     * Helper Functions
     */

    function buildFilledRow(id, row) {
        $.ajax({
            type: 'post',
            url: ALDajax.ajaxurl,
            dataType: 'json',
            data: {
                'action': 'get_number',
                'id': id
            },
            success: function (result) {
                $.get(ALDajax.plugin_url + 'resources/views/admin/ld/filled-row.twig', function (data) {
                    var alter = row.html(data);
                    alter.find('input[name=location-name]').val(result.number.location_name);
                    alter.find('input[name=location-label]').val(result.number.location_label);
                    alter.find('input[name=calltag]').val(result.number.calltag);
                    alter.find('input[name=seo]').val(result.number.seo);
                    alter.find('input[name=ppc]').val(result.number.ppc);
                    alter.find('input[name=insights]').val(result.number.insights);
                    alter.find('input[name=tracking-label]').val(result.number.tracking_label);
                });
            }
        });
    }
});