
<?php

// enqueue styles for child theme
// @ https://digwp.com/2016/01/include-styles-child-theme/

// Include GCI Customized files
require_once( get_stylesheet_directory() . '/includes.php');


// Override theme default specification for product # per row
// Change number or products per row to 3
add_filter('loop_shop_columns', 'loop_columns');


//Display products in tabular format on customer pages
add_action('woocommerce_after_shop_loop_item_title', 'gci_show_product_info');


// Add custom taxonomy of type Company
add_action('init', 'wpshout_register_taxonomy');


// Add custom Company field to 'Edit Page' in dashboard
add_action( 'add_meta_boxes', 'cd_meta_box_add_company' );
// Save custom fields to user meta when update is pressed
add_action( 'save_post', 'cd_meta_box_save_company' );


// Add menu item PMBus Admin and correlating pages
add_action( 'admin_menu', 'add_pmbus_admin_menu' );


// Adding custom field creating and saving to update and create new user pages
add_action( 'show_user_profile', 'user_membership_field' );
add_action( 'edit_user_profile', 'user_membership_field' );
add_action( 'user_new_form', 'user_membership_field' );
add_action( 'personal_options_update', 'my_save_custom_user_profile_fields' );
add_action( 'edit_user_profile_update', 'my_save_custom_user_profile_fields' );
add_action( 'user_new_update', 'my_save_custom_user_profile_fields' );
