<?php
if(!defined('ABSPATH'))exit;
function is_data_entry_user(){return in_array('data_entry',(array)wp_get_current_user()->roles,true);}
function bdf_doctor_caps(){return array('read','upload_files','edit_doctor','read_doctor','delete_doctor','edit_doctors','edit_others_doctors','publish_doctors','read_private_doctors','delete_doctors','delete_private_doctors','delete_published_doctors','delete_others_doctors','edit_private_doctors','edit_published_doctors');}
function fix_wpforms_caps_for_data_entry(){
 $role=get_role('data_entry');
 if(!$role){add_role('data_entry','Data Entry',array('read'=>true));$role=get_role('data_entry');}
 if($role)foreach(bdf_doctor_caps() as $cap)$role->add_cap($cap);
 $admin=get_role('administrator'); if($admin)foreach(bdf_doctor_caps() as $cap)$admin->add_cap($cap);
}
add_action('init','fix_wpforms_caps_for_data_entry');
add_action('admin_menu',function(){
 if(!is_data_entry_user())return;
 foreach(array('index.php','edit.php','edit-comments.php','themes.php','plugins.php','users.php','tools.php','options-general.php','edit.php?post_type=page','wpforms-overview') as $menu)remove_menu_page($menu);
},9999);
function reset_data_entry_role_on_activate(){remove_role('data_entry');fix_wpforms_caps_for_data_entry();}
