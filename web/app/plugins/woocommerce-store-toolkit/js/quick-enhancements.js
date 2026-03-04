jQuery(document).ready(function ($) {
  // Attach a change event listener to checkboxes inside .quick-enhancements-grid-item
  $('.quick-enhancements-grid-item .switch input[type="checkbox"]').on('change', function () {
    var $checkbox = $(this); // Cache the checkbox jQuery object
    var settingName = $checkbox.attr('name'); // Get the name attribute of the checkbox
    var settingToggleValue = $checkbox.is(':checked') ? '1' : '0'; // Determine the value based on checked status
    var nonce = $('input[name="woo_st_quick_enhancements_nonce"]').val(); // Get the nonce value

    // Fetch extra data if needed.
    var settingExtraData = Array();

    // Extra data is stored in any other fields below the parent().parent() of the checkbox. It will have a name of $settingName . '_extra_data[]'. There could be more than 1 extra data field.
    $checkbox
      .parent()
      .parent()
      .find('.extra-data-field')
      .each(function () {
        var fieldValue = $(this).val();
        // Add the extra data to the settingExtraData array.
        settingExtraData.push(fieldValue);
      });

    // Make the AJAX call to the backend
    $.ajax({
      url: ajaxurl, // ajaxurl is a global variable defined by WordPress for AJAX calls
      type: 'POST',
      data: {
        action: 'woo_st_save_quick_enhancement', // The WordPress action hook to target
        setting_name: settingName, // The name of the setting being changed
        setting_value: settingToggleValue, // The new value of the setting
        extra_data: settingExtraData, // Any extra data you want to send
        _wpnonce: nonce, // The security nonce
      },
      success: function (response) {},
      error: function () {
        // Log an error if the AJAX call fails.
        console.log('Error saving setting.');
      },
    });
  });

  // Attach a change event listener to any field marked with the class .extra-data-field. When it changes, we will save the new value by triggering the change event on the parent checkbox.
  $('.extra-data-field').on('change', function () {
    $(this).parent().find('.switch input[type="checkbox"]').trigger('change');
  });
});
