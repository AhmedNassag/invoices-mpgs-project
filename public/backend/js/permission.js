"use strict";

function toggleElementProperties(id, checked) {
    const element = jQuery('#' + id);

    element.prop({
        disabled: !checked,
        checked: checked
    });
}

jQuery('.mainmodule').each(function () {
    let mainModule = jQuery(this).attr('id');

    let mainIdCreate = mainModule + "_create";
    let mainIdEdit = mainModule + "_edit";
    let mainIdDestroy = mainModule + "_destroy";
    let mainIdShow = mainModule + "_show";

    let isChecked = jQuery('#' + mainModule).is(':checked');

    toggleElementProperties(mainIdCreate, isChecked);
    toggleElementProperties(mainIdEdit, isChecked);
    toggleElementProperties(mainIdDestroy, isChecked);
    toggleElementProperties(mainIdShow, isChecked);
});

function processCheck(event) {
    let mainModule = jQuery(event).attr('id');

    let mainIdCreate = mainModule + "_create";
    let mainIdEdit = mainModule + "_edit";
    let mainIdDestroy = mainModule + "_destroy";
    let mainIdShow = mainModule + "_show";

    let isChecked = jQuery('#' + mainModule).is(':checked');

    toggleElementProperties(mainIdCreate, isChecked);
    toggleElementProperties(mainIdEdit, isChecked);
    toggleElementProperties(mainIdDestroy, isChecked);
    toggleElementProperties(mainIdShow, isChecked);
}