<?php
/*
Plugin Name: Indicações
Description:
Version: 1
Author: Francivan Castro
Author URI: https://parauapebas.pa.gov.br/
*/
// function to create the DB / Options / Defaults					
function ss_options_install() {

    global $wpdb;

    $table_name = $wpdb->prefix . "publicacao";
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
			`id` varchar(11) CHARACTER SET utf8 NOT NULL,
			`numero` varchar(10) CHARACTER SET utf8 NOT NULL,
			`ano` varchar(4) CHARACTER SET utf8 NOT NULL,
			`tipo` varchar(15) CHARACTER SET utf8 NOT NULL,
			`titulo` varchar(50) CHARACTER SET utf8 NOT NULL,
			`autor` varchar(50) CHARACTER SET utf8 NOT NULL,
			`data` varchar(50) CHARACTER SET utf8 NOT NULL,
			`ementa` TEXT CHARACTER SET utf8 NOT NULL,
			`pdf` varchar(255) CHARACTER SET utf8 NOT NULL,
			PRIMARY KEY (`id`)
          ) $charset_collate; ";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}

// run the install scripts upon plugin activation
register_activation_hook(__FILE__, 'ss_options_install');

//menu items
add_action('admin_menu','indicacoes_modifymenu');
function indicacoes_modifymenu() {
	
	//this is the main item for the menu
	add_menu_page('Indicações', //page title
	'Indicações	', //menu title
	'manage_options', //capabilities
	'indicacoes_list', //menu slug
	'indicacoes_list' //function
	);
	
	//this is a submenu
	add_submenu_page('indicacoes_list', //parent slug
	'Publicação', //page title
	'Adicionar', //menu title
	'manage_options', //capability
	'indicacoes_create', //menu slug
	'indicacoes_create'); //function
	
	//this submenu is HIDDEN, however, we need to add it anyways
	add_submenu_page(null, //parent slug
	'Publicação', //page title
	'Alterar', //menu title
	'manage_options', //capability
	'indicacoes_update', //menu slug
	'indicacoes_update'); //function

	add_submenu_page(null, //parent slug
	'Publicação', //page title
	'Alterar', //menu title
	'manage_options', //capability
	'update_view', //menu slug
	'update_view'); //function
}
define('ROOTDIR', plugin_dir_path(__FILE__));
require_once(ROOTDIR . 'indicacoes-list.php');
require_once(ROOTDIR . 'indicacoes-create.php');
require_once(ROOTDIR . 'indicacoes-update.php');
require_once(ROOTDIR . 'update-view.php');
