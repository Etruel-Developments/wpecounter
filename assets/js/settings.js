jQuery(document).ready(function ($) {
    // If any role is checked, uncheck "All roles"
    $('#views_counter_rol_list').on('change', '.views_counter_rol_role', function () {
        if ($(this).is(':checked')) {
            $('#views_counter_rol_all').prop('checked', false);
        }
    });
    // If "All roles" is checked, uncheck all others
    $('#views_counter_rol_list').on('change', '#views_counter_rol_all', function () {
        if ($(this).is(':checked')) {
            $('.views_counter_rol_role').prop('checked', false);
        }
    });
});