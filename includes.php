<?php

// Enqueue custom styles and load after themes
require_once( get_stylesheet_directory() . '/includes/gci_styles_enqueue.php');

//Removed Woocommerce functionality
require_once( get_stylesheet_directory() . '/includes/gci_woocommerce_removals.php');

// Custom User fields functions
require_once( get_stylesheet_directory() . '/includes/custom_meta_fields/company_meta_page_field.php');

// PMBus admin custom pages and menu
require_once( get_stylesheet_directory() . '/includes/admin_menu/add_pmbus_admin_menu.php');
require_once( get_stylesheet_directory() . '/includes/admin_menu/pmbus_members_page_build.php');
require_once( get_stylesheet_directory() . '/includes/admin_menu/pmbus_admin_add_new_member.php');
// Display Membership types and user information on admin page
require_once( get_stylesheet_directory() . '/includes/admin_menu/groups_users_list_members.php');
require_once( get_stylesheet_directory() . '/includes/admin_menu/dev_testing/pmbus_dev_testing_page.php');


// Single Column product display
require_once( get_stylesheet_directory() . '/includes/single_column_product_display.php');
// Custom GCI Product Table display
require_once( get_stylesheet_directory() . '/includes/gci_show_product_info.php');

require_once( get_stylesheet_directory() . '/includes/company_taxonomy.php');

// Group Memberships
require_once( get_stylesheet_directory() . '/includes/user_group_memberships.php');
// Products by Category Shortcode
require_once( get_stylesheet_directory() . '/includes/shortcodes/products_by_category.php');
// List Companies with Products Shortcode
require_once( get_stylesheet_directory() . '/includes/shortcodes/list_companies.php');
// List active PMBus Members [Companies taxonomy] shortcode
require_once( get_stylesheet_directory() . '/includes/shortcodes/list_pmbus_members.php');

// Custom User Meta 'Company'
require_once( get_stylesheet_directory() . '/includes/custom_meta_fields/user_company_meta_field.php');
