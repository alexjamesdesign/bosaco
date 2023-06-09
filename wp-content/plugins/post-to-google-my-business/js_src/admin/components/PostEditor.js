import MediaUploader from "./MediaUploader";

import * as $ from 'jquery';

//require("jquery-ui-timepicker-addon");
import "jquery-ui-timepicker-addon";
import "jquery-ui-timepicker-addon/src/jquery-ui-timepicker-addon.css";
import {addFilter, applyFilters} from "@wordpress/hooks";

let PostEditor = function(ajax, ajax_prefix, default_fields, nonce, timepicker_disabled, loadingCallback){
    let ajaxEnabled = ajax || false;
    const postEditorInstance = this;
    const counterFields = $('.pgmb-field-with-counter');

    const postFormContainer = $(".mbp-post-form-container");

    let fieldPrefix = "mbp_form_fields";

    this.mediaUploader = new MediaUploader($('.mediaupload_selector'));
    if(!ajaxEnabled){
        let staticImage = $('.mbp-post-attachment');
        if(staticImage.val()){
            this.mediaUploader.loadItem($('.mbp-attachment-type').val(), staticImage.val(),staticImage.val());
        }
    }


    function debounce(func, timeout = 300){
        let timer;
        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => { func.apply(this, args); }, timeout);
        };
    }

    this.setFieldPrefix = function(prefix){
        this.mediaUploader.setFieldName(prefix);
        fieldPrefix = prefix;
    };

    let eventStartDate = $('#event_start_date');
    let eventEndDate = $('#event_end_date');

    if(!timepicker_disabled){
        $.timepicker.datetimeRange(
            eventStartDate,
            eventEndDate,
            {
                showOn: "button",
                //buttonImageOnly: true,
                buttonText: "",
                minInterval: (1000*60*60), // 1hr
                dateFormat : 'yy-mm-dd',
                timeFormat: 'HH:mm',
                minDate : 0,
                constrainInput: false,
                start: {}, // start picker options
                end: {} // end picker options
            }
        );
    }


    $('.mbp-validate-date').on("keyup change", debounce(function (event) {
        let closestDateDisplay = $(event.currentTarget).closest('td').find('.mbp-validated-date-display');
        if ($(event.currentTarget).val() === "") {
            //$('#event_start_date_validator').html('');
            $(closestDateDisplay).html('');
            return false;
        }
        const data = {
            'action': ajax_prefix + '_check_date',
            'mbp_post_nonce': nonce,
            'timestring': $(event.currentTarget).val()
        };
        $.post(ajaxurl, data, function (response) {
            if (response.success) {
                $(closestDateDisplay).html(response.data);
                return true;
            } else {
                $(closestDateDisplay).html('Invalid date');
                return false;
            }
        });
    }));

    /**
     * Switch tabs by providing a selector for a valid tab
     *
     * @param clicked Selector for the clicked tab
     */
    this.switch_tab = function(clicked){
        $('.nav-tab', postFormContainer).removeClass("nav-tab-active");
        $(clicked).addClass("nav-tab-active");
        $('.mbp-fields > tbody > tr').hide();
        $('.mbp-fields > tbody > tr.' + $(clicked).data('fields')).show();
        $('input.mbp-topic-type').val($(clicked).data("topic")).trigger('change');

    };

    /**
     * Hook switch tab function to tabs
     */
    $('.nav-tab', postFormContainer).click(function(event) {
        event.preventDefault();
        postEditorInstance.switch_tab(this);
    });

    /**
     * Open the advanced post settings
     */
    $('.mbp-toggle-advanced').click(function(event) {
        event.preventDefault();
        const advanced_settings = $(".mbp-advanced-post-settings");
        if(advanced_settings.is(":hidden")){
            localStorage.openAdvanced = JSON.stringify(true);
        }else{
            localStorage.openAdvanced = JSON.stringify(false);
        }
        advanced_settings.slideToggle("slow");
    });

    /**
     * Reload the state of the advanced post settings dialog
     */
    if(localStorage.openAdvanced && JSON.parse(localStorage.openAdvanced) === true){
        const advanced_settings = $(".mbp-advanced-post-settings");
        advanced_settings.show();
    }

    /**
     * Trigger change on the post text field when it is changed externally, to update the character counter
     */
    counterFields.change(function () {
        $(this).trigger("keyup");
    });

    /**
     * Update text and word counter for the text field
     */
    counterFields.keyup(function () {
        let counter = $(this).parents('tr').find('.mbp-character-count');
        let count = $(this).val().length;
        let words = $(this).val().split(' ').length - 1;
        counter.text(count);
        if(count > $(this).data('maxchars')){
            counter.css('color', 'red');
        }else{
            counter.css('color', 'inherit');
        }
        $('.mbp-word-count').text(words);
    });

    // /**
    //  * Keep track of the state of the button option
    //  * @type {boolean} Button options are opened
    //  */
    // let ButtonOptionsOpened = false;
    //
    // /**
    //  * Show/hide Call to Action settings when checking/unchecking the CTA checkbox
    //  */
    // $('#mbp_button').change(function() {
    //     if(this.checked) {
    //         $(".mbp-button-settings").fadeIn("slow");
    //         ButtonOptionsOpened = true;
    //     }else{
    //         $(".mbp-button-settings").fadeOut("slow");
    //         ButtonOptionsOpened = false;
    //     }
    // });



    /**
     * Hide the "alternative URL" field if the CTA is set to "CALL"
     */
    $('.mbp-button-type').change(function() {
        const alternativeURL = $(".mbp-button-url");
        if(!$(this).val() || $(this).val() === 'CALL'){
            alternativeURL.fadeOut("slow");
            return;
        }

        alternativeURL.fadeIn("slow");

    });

    this.recurseMultiDimensionalFields = function(name, value, depth){
        if(!depth){ depth = '[' + name + ']'; }
        if ($.isArray(value) || $.isPlainObject(value)) {

            $.each(value, function (key, checkboxVal) {
                let newDepth = '';
                if($.isArray(value)){
                    newDepth = depth + '[]';
                }else{
                    newDepth = depth + '[' + key + ']';
                }
                postEditorInstance.recurseMultiDimensionalFields(key, checkboxVal, newDepth);
            });
            return;
        }

        if(value === "1" || value === "on"){
            value = true;
        }
        if(typeof value === 'boolean'){
            $('[name="' + fieldPrefix + depth + '"]').prop('checked', value);
        }else{
            $('[name^="' + fieldPrefix + depth + '"][value="' + value + '"]').prop('checked', true);
        }

    }

    /**
     * Repopulate the form fields from data object
     *
     * @param form_fields - object containing field names and values
     */
    this.loadFormFields = function(form_fields){
        $.each(form_fields, function(name, value){
            //let field = $('[name="' + fieldPrefix + '[' + name + ']"], [name="' + fieldPrefix + '[' + name + '][]"]');
            let field = $('[name^="' + fieldPrefix + '[' + name + ']"]');

            field = applyFilters('pgmb-load-posteditor-field', field, name, value);
            if(!field){
                return;
            }

            if(field.is(':checkbox') || field.is(':radio')) {
                //Uncheck everything first
                field.prop('checked', false);

                postEditorInstance.recurseMultiDimensionalFields(name, value);

            }else if(field.is('select') && !value) {
                field.val("");
            }else{
                field.val(value);
            }
            field.change();
        });

        if(form_fields.mbp_post_attachment && form_fields.mbp_attachment_type){
            //mediaupload.loadItem(form_fields.mbp_attachment_type, form_fields.mbp_post_attachment, form_fields.mbp_post_attachment);
            this.mediaUploader.loadItem(form_fields.mbp_attachment_type, form_fields.mbp_post_attachment, form_fields.mbp_post_attachment);
        }

        const tab = $('a[data-topic="'+ form_fields.mbp_topic_type +'"]');
        this.switch_tab(tab);
    };

    this.resetForm = function(){
        this.mediaUploader.clearItems();
    };

    this.loadDefaultFormFields = function (){
        this.loadFormFields(default_fields);
    };

    /**
     * Trigger dynamic changes on the form when the form is loaded statically
     */
    if(!ajaxEnabled){
        //trigger changes when the form is not loaded through ajax
        $('.mbp-validate-date').trigger("change");
        $('#mbp_button').trigger("change");
        $('.mbp-button-type').trigger("change");
        $(counterFields).trigger("keyup");

        //Switch to the appropriate tab
        let topicType = $("input.mbp-topic-type").val();
        let tab = $('a[data-topic="'+ topicType +'"]');
        this.switch_tab(tab);
    }

};

export default PostEditor;
