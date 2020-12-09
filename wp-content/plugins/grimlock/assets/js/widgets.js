'use strict';

/*global
    jQuery, wp, _, grimlock_widgets, ajaxurl
 */

/**
 * widgets.js
 *
 * Widgets enhancements for a better user experience.
 */

(function($){
    if ( ! window.grimlock ) {
        window.grimlock = {};
    }

    if ( ! window.grimlock.widgets ) {
        window.grimlock.widgets = {};
    }

    /**
     * Callback function for the 'click' event on the upload button.
     *
     * Displays the media uploader for selecting an image.
     *
     * @since 1.0.0
     */
    window.grimlock.widgets.uploadMedia = function (e) {
        var file_frame, json;

        /**
         * If an instance of file_frame already exists, then we can open it
         * rather than creating a new instance.
         */
        if (undefined !== file_frame) {
            file_frame.open();
            return;
        }

        /**
         * Use the wp.media library to define the settings of the Media
         * Uploader.
         */
        file_frame = wp.media.frames.file_frame = wp.media({
            title:  grimlock_widgets.frame_title,
            button: {
                text: grimlock_widgets.button_text
            },
            multiple: false
        });

        /**
         * Setup an event handler for what to do when an image has been
         * selected.
         */
        file_frame.on('select', function() {
            json = file_frame.state().get('selection').first().toJSON();

            if (0 < $.trim(json.url).length) {
                var $wrapper = $(e.target).parents('.grimlock_section_widget-image');

                $wrapper.find('.attachment-media-view').addClass([
                    'attachment-media-view-image',
                    'landscape'
                ].join(' '));

                $wrapper.find('.placeholder').addClass('hidden');

                $wrapper.find('.thumbnail')
                .children('img')
                .attr('src', json.url)
                .parent()
                .removeClass('hidden');

                $wrapper.find('.upload-actions').addClass('hidden');
                $wrapper.find('.remove-actions').removeClass('hidden');

                $wrapper.find('.attachment-media-src').val(json.id).change();
            }
        });

        file_frame.open();
    };

    /**
     * Callback function for the 'click' event on the remove button.
     *
     * Removes the media url from widget form and resets attachment view.
     *
     * @since 1.0.0
     */
    window.grimlock.widgets.removeMedia = function(e) {
        var $wrapper = $(e.target).parents('.grimlock_section_widget-image');

        $wrapper.find('.attachment-media-view').removeClass([
            'attachment-media-view-image',
            'landscape'
        ].join(' '));

        $wrapper.find('.placeholder').removeClass('hidden');
        $wrapper.find('.thumbnail').addClass('hidden');

        $wrapper.find('.upload-actions').removeClass('hidden');
        $wrapper.find('.remove-actions').addClass('hidden');

        $wrapper.find('.attachment-media-src').val('').change();
    };

    /**
     * Callback for the 'widget-added', 'widget-updated' and 'ajax-complete' events.
     *
     * Activate widget form inputs.
     *
     * @since 1.0.0
     */
    window.grimlock.widgets.init = function($parent) {
        var handleChange = function(e, ui) {
            // Check whether the input value has changed.
            if ('text' === e.target.type && !_.isUndefined(ui)) {
                var tmp   = $(e.target).data('tmp');
                var color = {
                    color: ui.color._color,
                    alpha: ui.color._alpha
                };

                // Check whether the input value has changed since last event.
                if (!_.isEqual(tmp, color)) {
                    $(e.target).data('tmp', color);
                    if (!_.isUndefined(tmp)) {
                        // @Hack: Trigger change on the "Clear" button.
                        $(e.target).parents('.wp-picker-container').find('input.wp-picker-clear').change();
                    }
                }
            } else if ('button' === e.target.type) {
                $(e.target).change();
            }
        };

        // Activate the color picker.
        $parent.find('.grimlock_section_widget-color-picker').wpColorPicker({
            defaultColor: false,
            change:       handleChange,
            clear:        handleChange,
            hide:         true,
            palettes:     true
        });

        // Activate the radio image buttons.
        $parent.find('.grimlock_section_widget-radio-image__buttonset').buttonset();

        var updateInputRangeValueIndicator = function() {
            var $valueIndicator = $(this).siblings('span.grimlock_input-range-value-indicator');
            var unit = $(this).data('unit');

            if (!$valueIndicator.length) {
                $valueIndicator = $('<span class="grimlock_input-range-value-indicator"></span>');
                $(this).after($valueIndicator);
            }

            $valueIndicator.html($(this).val() + ' ' + unit);
        }

        // Init value indicator for range inputs
        $parent.find('.grimlock-widget input[type="range"]').each(updateInputRangeValueIndicator);
        $parent.find('.grimlock-widget input[type="range"]').on('input change', updateInputRangeValueIndicator);

        // Handle fields visibility
        var handleGradientFieldsVisibility = function() {
            var $currentWidget     = $(this).closest('.grimlock-widget');
            var $gradientInputs    = $currentWidget.find('input[name$="[background_gradient_first_color]"], input[name$="[background_gradient_second_color]"], input[name$="[background_gradient_position]"]').closest('p');
            var $gradientDirection = $currentWidget.find('div[id$="background_gradient_direction"]');

            if ($(this).is(':checked')) {
                $gradientInputs.show();
                $gradientDirection.show();
            }
            else {
                $gradientInputs.hide();
                $gradientDirection.hide();
            }
        }

        $parent.find('.grimlock-widget input[name$="[background_gradient_displayed]"]').each(handleGradientFieldsVisibility)
        $parent.find('.grimlock-widget input[name$="[background_gradient_displayed]"]').change(handleGradientFieldsVisibility);

        var handleButtonFieldsVisibility = function() {
            var $currentWidget = $(this).closest('.grimlock-widget');
            var $buttonInputs  = $currentWidget.find('input[name$="[button_target_blank]"], input[name$="[button_link]"], input[name$="[button_text]"], select[name$="[button_format]"], select[name$="[button_size]"]').closest('p');

            if ($(this).is(':checked')) {
                $buttonInputs.show();
            }
            else {
                $buttonInputs.hide();
            }
        }

        $parent.find('.grimlock-widget input[name$="[button_displayed]"]').each(handleButtonFieldsVisibility)
        $parent.find('.grimlock-widget input[name$="[button_displayed]"]').change(handleButtonFieldsVisibility);

        // Handle widget tab changes.
        $('.grimlock-widget-tabs a').click(function(e) {
            e.preventDefault();
            var tabId = $(this).attr('href');
            $(this).parent().addClass('tabs').siblings('li').removeClass('tabs');
            $(this).closest('.grimlock-widget-tabs').siblings('.tabs-panel').hide();
            $(tabId).show();
        });

        // Reload parent terms list on each change of taxonomy list.
        var $taxonomy = $('.widget-grimlock_term_query_section_widget_taxonomy_field');
        var $terms    = $('.widget-grimlock_term_query_section_widget_parent_field');

        $taxonomy.on( 'change', function() {
            var data = {
                'action':     'grimlock_ajax_terms',
                'taxonomy':   $taxonomy.find('option:selected').val(),
                'ajax_nonce': grimlock_widgets.ajax_nonce
            };

            jQuery.post(ajaxurl, data, function(response) {
                $terms.html(response);
            });
        });


    };

    $(document).ready(function() {

        $(document).on('click', '.grimlock_section_widget-image .upload-button', function(e) {
            e.preventDefault();
            window.grimlock.widgets.uploadMedia(e);
        });

        $(document).on('click', '.grimlock_section_widget-image .remove-button', function(e) {
            e.preventDefault();
            window.grimlock.widgets.removeMedia(e);
        });

        // Initialize all widgets already in page.
        window.grimlock.widgets.init($('#wp_inactive_widgets, #widgets-right'));

        // Initialize added widgets.
        $(document).on('widget-added', function(e, widget) {
            window.grimlock.widgets.init(widget);
        });

        // Reinitialize updated widgets.
        $(document).on('widget-updated', function(e, widget) {
            window.grimlock.widgets.init(widget);
        });
    });
})(jQuery);
