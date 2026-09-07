<?php
/**
 * Doctor Finder application module.
 *
 * Preserves the existing Doctor Finder functionality.
 */

if (!defined('ABSPATH')) exit;

// ১. Register Doctor CPT & Separate Taxonomies
function dr_finder_v37_full_init() {
    register_post_type('doctor', array(
        'labels' => array(
            'name'               => 'Doctors',
            'singular_name'      => 'Doctor',
            'add_new'            => 'Add New Doctor',
            'add_new_item'       => 'Add New Doctor Info',
            'edit_item'          => 'Edit Doctor Info',
            'all_items'          => 'All Doctors',
            'menu_name'          => 'Doctors'
        ),
        'public' => true,
        'supports' => array('title', 'thumbnail'),
        'menu_icon' => 'dashicons-businessman',
        'has_archive' => true,
    ));

    register_taxonomy('manual_div', 'doctor', array('label' => 'বিভাগ (Manual)', 'hierarchical' => true, 'show_ui' => true, 'show_admin_column' => true));
    register_taxonomy('manual_dist', 'doctor', array('label' => 'জেলা (Manual)', 'hierarchical' => true, 'show_ui' => true, 'show_admin_column' => true));
    register_taxonomy('manual_upz', 'doctor', array('label' => 'উপজেলা (Manual)', 'hierarchical' => true, 'show_ui' => true, 'show_admin_column' => true));
    
}
add_action('init', 'dr_finder_v37_full_init');

// Representative public Bangladesh location sample.
function get_bd_hardcoded_database_full() {
    // Representative sample only. Production deployments can extend/replace this dataset.
    return array(
        'Dhaka' => array(
            'Dhaka' => array('Dhanmondi', 'Gulshan', 'Mirpur', 'Mohammadpur', 'Uttara'),
            'Gazipur' => array('Gazipur Sadar', 'Kaliakair', 'Sreepur'),
        ),
        'Chattogram' => array(
            'Chattogram' => array('Hathazari', 'Patiya', 'Sitakunda'),
            "Cox's Bazar" => array("Cox's Bazar Sadar", 'Ramu', 'Teknaf'),
        ),
        'Rajshahi' => array(
            'Rajshahi' => array('Paba', 'Puthia', 'Rajshahi Sadar'),
        ),
    );
}

// ৩. Backend Meta Box
add_action('add_meta_boxes', function(){
    add_meta_box('dr_v37_meta', 'ডাক্তারের তথ্য ও হার্ডকোড লোকেশন', 'dr_meta_html_v37', 'doctor', 'normal', 'high');
});

function dr_meta_html_v37($post) {
    $bd_data = get_bd_hardcoded_database_full();
    $fields = array(
        'designation' => 'পদবি', 'workplace' => 'হাসপাতাল', 'clinic' => 'চেম্বার',
        'phone' => 'ফোন', 'address' => 'বিস্তারিত ঠিকানা', 'map_link' => 'গুগল ম্যাপ লিংক',
        'experience' => 'অভিজ্ঞতা (বছর)'
    );
    foreach ($fields as $k => $l) echo '<p><strong>'.$l.'</strong><br><input type="text" name="'.$k.'" value="'.esc_attr(get_post_meta($post->ID,$k,true)).'" style="width:100%; padding:8px;"></p>';
    
    echo '<p><strong>ফেসবুক লিংক</strong><br><input type="text" name="facebook_link" value="'.esc_attr(get_post_meta($post->ID, 'facebook_link', true)).'" style="width:100%; padding:8px;"></p>';
    echo '<p><label><input type="checkbox" name="vbac_support" value="1" '.checked(get_post_meta($post->ID, 'vbac_support', true), 1, false).'> <strong>VBAC সাপোর্ট আছে</strong></label></p>';

    $c_div  = get_post_meta($post->ID, 'division', true);
    $c_dist = get_post_meta($post->ID, 'district', true);
    $c_upz  = get_post_meta($post->ID, 'upazila', true);

    echo '<p><strong>বিভাগ</strong><br><select name="division" id="be-div" style="width:100%;"><option value="">সিলেক্ট করুন</option>';
    foreach(array_keys($bd_data) as $d) echo '<option value="'.$d.'" '.selected($c_div,$d,false).'>'.$d.'</option>';
    echo '</select></p>';
    echo '<p><strong>জেলা</strong><br><select name="district" id="be-dist" style="width:100%;">';
    if($c_div && isset($bd_data[$c_div])) foreach(array_keys($bd_data[$c_div]) as $dst) echo '<option value="'.$dst.'" '.selected($c_dist,$dst,false).'>'.$dst.'</option>';
    echo '</select></p>';
    echo '<p><strong>উপজেলা</strong><br><select name="upazila" id="be-upz" style="width:100%;">';
    if($c_div && $c_dist && isset($bd_data[$c_div][$c_dist])) foreach($bd_data[$c_div][$c_dist] as $u) echo '<option value="'.$u.'" '.selected($c_upz,$u,false).'>'.$u.'</option>';
    echo '</select></p>';
    ?>
    <script>
    jQuery(document).ready(function($){
        var db = <?php echo json_encode($bd_data); ?>;
        $('#be-div').on('change', function(){
            var div = $(this).val(), h = '<option value="">জেলা সিলেক্ট করুন</option>';
            if(db[div]) $.each(db[div], function(dst){ h += '<option value="'+dst+'">'+dst+'</option>'; });
            $('#be-dist').html(h); $('#be-upz').html('');
        });
        $('#be-dist').on('change', function(){
            var div = $('#be-div').val(), dst = $(this).val(), h = '<option value="">উপজেলা সিলেক্ট করুন</option>';
            if(db[div] && db[div][dst]) $.each(db[div][dst], function(i,u){ h += '<option value="'+u+'">'+u+'</option>'; });
            $('#be-upz').html(h);
        });
    });
    </script>
    <?php
}

add_action('save_post', function($post_id){
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    $meta = array('designation','workplace','clinic','phone','address','map_link','division','district','upazila','facebook_link','vbac_support','experience');
    foreach($meta as $m) {
        if(isset($_POST[$m])) {
            update_post_meta($post_id, $m, sanitize_text_field($_POST[$m]));
        } elseif($m == 'vbac_support') {
            delete_post_meta($post_id, 'vbac_support');
        }
    }
});

// ৪. CSV Import
add_action('admin_menu', function(){
    add_submenu_page('edit.php?post_type=doctor', 'Import CSV', 'Import CSV', 'manage_options', 'dr-csv', 'dr_import_v37_page');
});
function dr_import_v37_page() {
    if (isset($_POST['up_csv']) && !empty($_FILES['csv_f']['tmp_name'])) {
        $f = fopen($_FILES['csv_f']['tmp_name'], "r"); fgetcsv($f); $c = 0;
        while (($r = fgetcsv($f, 1000, ",")) !== FALSE) {
            $pid = wp_insert_post(array('post_title' => $r[0], 'post_type' => 'doctor', 'post_status' => 'publish'));
            update_post_meta($pid, 'designation', $r[1]); update_post_meta($pid, 'workplace', $r[2]);
            update_post_meta($pid, 'clinic', $r[3]);      update_post_meta($pid, 'phone', $r[4]);
            update_post_meta($pid, 'division', $r[5]);    update_post_meta($pid, 'district', $r[6]);
            update_post_meta($pid, 'upazila', $r[7]);     update_post_meta($pid, 'address', $r[8]);
            if(isset($r[9]))  update_post_meta($pid, 'facebook_link', $r[9]);
            if(isset($r[10])) update_post_meta($pid, 'vbac_support', $r[10]);
            if(isset($r[11])) update_post_meta($pid, 'experience', $r[11]);
            $c++;
        }
        fclose($f); echo "<div class='updated'><p>$c Doctors Imported!</p></div>";
    }
    ?>
    <div class="wrap"><h1>Import CSV</h1><form method="post" enctype="multipart/form-data"><input type="file" name="csv_f"><input type="submit" name="up_csv" class="button button-primary" value="Import Now"></form></div>
    <?php
}
