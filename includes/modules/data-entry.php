<?php
// ৯. Data Entry Role — WPForms Access Fix
// ========================================================

// Role check helper
function is_data_entry_user() {
    $user = wp_get_current_user();
    return in_array('data_entry', (array) $user->roles);
}

// Data Entry Role তৈরি ও WPForms caps যোগ
function fix_wpforms_caps_for_data_entry() {
    $role = get_role('data_entry');

    if (!$role) {
        add_role('data_entry', 'Data Entry', array('read' => true));
        $role = get_role('data_entry');
    }

    // Doctor CPT caps (আগের মতোই)
    $doctor_caps = array(
        'upload_files',
        'edit_posts',
        'edit_others_posts',
        'publish_posts',
        'delete_posts',
        'delete_others_posts',
    );
    foreach ($doctor_caps as $cap) {
        $role->add_cap($cap);
    }

    // WPForms caps
    $wpforms_caps = array(
        'wpforms_view_entries',
        'wpforms_edit_entries',
        'wpforms_delete_entries',
        'wpforms_view_others_entries',
        'wpforms_edit_others_entries',
        'wpforms_delete_others_entries',
        'wpforms_view_forms',
        'wpforms_edit_forms',
        'wpforms_create_forms',
        'wpforms_delete_forms',
    );
    foreach ($wpforms_caps as $cap) {
        $role->add_cap($cap);
    }
}
add_action('init', 'fix_wpforms_caps_for_data_entry');

// WPForms সব internal cap filter bypass
$_svt_wpforms_filters = array(
    'wpforms_manage_cap',
    'wpforms_get_entries_cap',
    'wpforms_admin_entries_edit_cap',
    'wpforms_admin_entries_list_cap',
    'wpforms_admin_entries_delete_cap',
    'wpforms_admin_forms_list_cap',
);
foreach ($_svt_wpforms_filters as $_svt_filter) {
    add_filter($_svt_filter, function($cap) {
        if (is_data_entry_user()) return 'read';
        return $cap;
    }, 9999);
}

// WPForms entries page — user_has_cap override
add_action('admin_init', function() {
    if (!is_data_entry_user()) return;
    add_filter('user_has_cap', function($caps) {
        $caps['wpforms_view_entries']        = true;
        $caps['wpforms_edit_entries']        = true;
        $caps['wpforms_view_others_entries'] = true;
        $caps['wpforms_delete_entries']      = true;
        $caps['wpforms_view_forms']          = true;
        return $caps;
    });
});

// WPForms admin menu force add করুন data_entry role এর জন্য
add_action('admin_menu', function() {
    if (!is_data_entry_user()) return;

    add_menu_page(
        'WPForms', 'WPForms', 'read',
        'wpforms-overview', '',
        'dashicons-feedback', 25
    );
    add_submenu_page(
        'wpforms-overview', 'All Forms', 'All Forms',
        'read', 'wpforms-overview'
    );
    add_submenu_page(
        'wpforms-overview', 'Entries', 'Entries',
        'read', 'wpforms-entries'
    );
}, 5);

// Dashboard menu cleanup — data_entry role এর জন্য অপ্রয়োজনীয় menu সরানো
add_action('admin_menu', function() {
    if (!is_data_entry_user()) return;

    $remove = array(
        'index.php',
        'edit.php',
        'edit-comments.php',
        'themes.php',
        'plugins.php',
        'users.php',
        'tools.php',
        'options-general.php',
        'edit.php?post_type=page',
    );
    foreach ($remove as $menu) {
        remove_menu_page($menu);
    }
}, 9999);

// Plugin activate হলে role reset করে নতুন caps apply করুন
function reset_data_entry_role_on_activate() {
    remove_role('data_entry');
    fix_wpforms_caps_for_data_entry();
}
// ========================================================
