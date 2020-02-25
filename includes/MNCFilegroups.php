<?php

namespace mnc;


class MNCFilegroups {

	const CPT_MNCFILEGROUP = 'mnc_filegroups';
	const TAXONOMY_FOLDER = 'mnc_folder';

	public static function registerCPT() {

		$key_cpt     = self::CPT_MNCFILEGROUP;
		$tax_folder  = self::TAXONOMY_FOLDER;
		$text_domain = $key_cpt;

		$labels = [
			'name'                  => _x( 'Dokumentgruppen', 'Post Type General Name', $text_domain ),
			'singular_name'         => _x( 'Dokumentgruppe', 'Post Type Singular Name', $text_domain ),
			'menu_name'             => __( 'MNC DokGruppe', $text_domain ),
			'name_admin_bar'        => __( 'MNC DOKGruppe', $text_domain ),
			'archives'              => __( 'Dokumentgruppenarchiv', $text_domain ),
			'parent_item_colon'     => __( 'Parent Item:', $text_domain ),
			'all_items'             => __( 'Alle Dokumentgruppen', $text_domain ),
			'add_new_item'          => __( 'Neue Dokumentgruppe', $text_domain ),
			'add_new'               => __( 'Neue Dokumentgruppe hinzufügen', $text_domain ),
			'new_item'              => __( 'Neue Dokumentgruppe', $text_domain ),
			'edit_item'             => __( 'bearbeiten', $text_domain ),
			'update_item'           => __( 'aktualisieren', $text_domain ),
			'view_item'             => __( 'anschauen', $text_domain ),
			'search_items'          => __( 'durchsuchen', $text_domain ),
			'not_found'             => __( 'Dokumentgruppe nicht gefunden', $text_domain ),
			'not_found_in_trash'    => __( 'Dokumentgruppe nicht im Papierkorb gefunden', $text_domain ),
			'featured_image'        => __( 'Anhang', $text_domain ),
			'set_featured_image'    => __( 'Set featured image', $text_domain ),
			'remove_featured_image' => __( 'Remove featured image', $text_domain ),
			'use_featured_image'    => __( 'Use as featured image', $text_domain ),
			'insert_into_item'      => __( 'Zu Dokumentgruppe hinzufügen', $text_domain ),
			'uploaded_to_this_item' => __( 'Zu Dokumentgruppe hochladen', $text_domain ),
			'items_list'            => __( 'Dokumentgruppen auflisten', $text_domain ),
			'items_list_navigation' => __( 'list navigation', $text_domain ),
			'filter_items_list'     => __( 'Filter Dokumentgruppen', $text_domain ),
		];
		$args   = [
			'label'               => __( 'Dokumentgruppen', $text_domain ),
			'description'         => __( 'Dokumentgruppen listen', $text_domain ),
			'labels'              => $labels,
			'supports'            => [ 'title', 'editor' ],
			'taxonomies'          => [ 'mnc_folder' ],
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 6,
			'menu_icon'           => 'dashicons-portfolio',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'capability_type'     => 'post',
			'rewrite'             => false,
			'query_var'           => false,
		];

		register_post_type( $key_cpt, $args );

		// Projektkategorie
		$labels = [
			'name'                       => _x( 'Ordner', 'Taxonomy General Name', $text_domain ),
			'singular_name'              => _x( 'Ordner', 'Taxonomy Singular Name', $text_domain ),
			'menu_name'                  => __( 'Ordner', $text_domain ),
			'all_items'                  => __( 'Alle Ordner', $text_domain ),
			'parent_item'                => __( 'Parent Item', $text_domain ),
			'parent_item_colon'          => __( 'Parent Item:', $text_domain ),
			'new_item_name'              => __( 'New Item Name', $text_domain ),
			'add_new_item'               => __( 'Add New Item', $text_domain ),
			'edit_item'                  => __( 'Edit Item', $text_domain ),
			'update_item'                => __( 'Update Item', $text_domain ),
			'view_item'                  => __( 'View Item', $text_domain ),
			'separate_items_with_commas' => __( 'Separate items with commas', $text_domain ),
			'add_or_remove_items'        => __( 'Add or remove items', $text_domain ),
			'choose_from_most_used'      => __( 'Choose from the most used', $text_domain ),
			'popular_items'              => __( 'Popular Items', $text_domain ),
			'search_items'               => __( 'Search Items', $text_domain ),
			'not_found'                  => __( 'Not Found', $text_domain ),
			'no_terms'                   => __( 'No items', $text_domain ),
			'items_list'                 => __( 'Items list', $text_domain ),
			'items_list_navigation'      => __( 'Items list navigation', $text_domain ),
		];
		$args   = [
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => false,
		];
		register_taxonomy( $tax_folder, [ $key_cpt ], $args );

		// Show the shortcode in a metabox so it can be copied
		// mmai
		add_action( 'add_meta_boxes', function () {
			add_meta_box(
				'mnc_mbox_postname',
				'Shortcode: (Copy & Paste)',
				// array( $this, 'display_postname' ),
				array( '\mnc\MNCFilegroups', 'display_shortcode' ),
				self::CPT_MNCFILEGROUP,
				'normal',
				'high'
			);
		} );

		self::modifyAdminColumns();

	}

	public static function modifyAdminColumns() {
		// Add Projektnummer to Column:

		add_filter( 'manage_' . self::CPT_MNCFILEGROUP . '_posts_columns', function ( $columns ) {
			return
				[
					'cb'                                => '<input type="checkbox" />',
					'title'                             => __( 'Titel' ),
					'shortcode'                         => __( 'Code' ),
					'taxonomy-' . self::TAXONOMY_FOLDER => __( 'Ordner' ),
				];
		} );

		add_filter( 'manage_' . self::CPT_MNCFILEGROUP . '_posts_custom_column', function ( $column, $post_id ) {
			switch ( $column ) {
				case 'shortcode':
					$code = self::get_shortcode( $post_id );
					echo( $code );
					break;
			}
		}, 10, 3 );

	}

	public static function get_shortcode( $post_id ) {
		return "[mnc_doclist id={$post_id}]";
	}

	public static function display_shortcode() {
		global $post;
		$text = self::get_shortcode( $post->ID );
		echo( $text );
	}


}