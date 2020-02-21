<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://mainetcare.de
 * @since      1.0.0
 *
 * @package    Mncfilegroups
 * @subpackage Mncfilegroups/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Mncfilegroups
 * @subpackage Mncfilegroups/admin
 * @author     MaiNetCare GmbH <info@mainetcare.com>
 */
class Mncfilegroups_Admin {

	const TEXT_DOMAIN = 'mncfilegroups';


	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mncfilegroups_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mncfilegroups_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/mncfilegroups-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mncfilegroups_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mncfilegroups_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/mncfilegroups-admin.js', array( 'jquery' ), $this->version, false );
	}

	public function register_cpts() {
		$this->register_cpt_filegroups();
	}

	protected function register_cpt_filegroups() {

		$labels = [
			'name'                  => _x( 'Dokumentgruppen', 'Post Type General Name', self::TEXT_DOMAIN ),
			'singular_name'         => _x( 'Dokumentgruppe', 'Post Type Singular Name', self::TEXT_DOMAIN ),
			'menu_name'             => __( 'MNC DokGruppe', self::TEXT_DOMAIN ),
			'name_admin_bar'        => __( 'MNC DOKGruppe', self::TEXT_DOMAIN ),
			'archives'              => __( 'Dokumentgruppenarchiv', self::TEXT_DOMAIN ),
			'parent_item_colon'     => __( 'Parent Item:', self::TEXT_DOMAIN ),
			'all_items'             => __( 'Alle Dokumentgruppen', self::TEXT_DOMAIN ),
			'add_new_item'          => __( 'Neue Dokumentgruppe', self::TEXT_DOMAIN ),
			'add_new'               => __( 'Neue Dokumentgruppe hinzufügen', self::TEXT_DOMAIN ),
			'new_item'              => __( 'Neue Dokumentgruppe', self::TEXT_DOMAIN ),
			'edit_item'             => __( 'bearbeiten', self::TEXT_DOMAIN ),
			'update_item'           => __( 'aktualisieren', self::TEXT_DOMAIN ),
			'view_item'             => __( 'anschauen', self::TEXT_DOMAIN ),
			'search_items'          => __( 'durchsuchen', self::TEXT_DOMAIN ),
			'not_found'             => __( 'Dokumentgruppe nicht gefunden', self::TEXT_DOMAIN ),
			'not_found_in_trash'    => __( 'Dokumentgruppe nicht im Papierkorb gefunden', self::TEXT_DOMAIN ),
			'featured_image'        => __( 'Anhang', self::TEXT_DOMAIN ),
			'set_featured_image'    => __( 'Set featured image', self::TEXT_DOMAIN ),
			'remove_featured_image' => __( 'Remove featured image', self::TEXT_DOMAIN ),
			'use_featured_image'    => __( 'Use as featured image', self::TEXT_DOMAIN ),
			'insert_into_item'      => __( 'Zu Dokumentgruppe hinzufügen', self::TEXT_DOMAIN ),
			'uploaded_to_this_item' => __( 'Zu Dokumentgruppe hochladen', self::TEXT_DOMAIN ),
			'items_list'            => __( 'Dokumentgruppen auflisten', self::TEXT_DOMAIN ),
			'items_list_navigation' => __( 'list navigation', self::TEXT_DOMAIN ),
			'filter_items_list'     => __( 'Filter Dokumentgruppen', self::TEXT_DOMAIN ),
		];
		$args   = [
			'label'               => __( 'Dokumentgruppen', self::TEXT_DOMAIN ),
			'description'         => __( 'Dokumentgruppen listen', self::TEXT_DOMAIN ),
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

		register_post_type( 'mnc_filegroups', $args );

		// Projektkategorie
		$labels = [
			'name'                       => _x( 'Ordner', 'Taxonomy General Name', self::TEXT_DOMAIN ),
			'singular_name'              => _x( 'Ordner', 'Taxonomy Singular Name', self::TEXT_DOMAIN ),
			'menu_name'                  => __( 'Ordner', self::TEXT_DOMAIN ),
			'all_items'                  => __( 'Alle Ordner', self::TEXT_DOMAIN ),
			'parent_item'                => __( 'Parent Item', self::TEXT_DOMAIN ),
			'parent_item_colon'          => __( 'Parent Item:', self::TEXT_DOMAIN ),
			'new_item_name'              => __( 'New Item Name', self::TEXT_DOMAIN ),
			'add_new_item'               => __( 'Add New Item', self::TEXT_DOMAIN ),
			'edit_item'                  => __( 'Edit Item', self::TEXT_DOMAIN ),
			'update_item'                => __( 'Update Item', self::TEXT_DOMAIN ),
			'view_item'                  => __( 'View Item', self::TEXT_DOMAIN ),
			'separate_items_with_commas' => __( 'Separate items with commas', self::TEXT_DOMAIN ),
			'add_or_remove_items'        => __( 'Add or remove items', self::TEXT_DOMAIN ),
			'choose_from_most_used'      => __( 'Choose from the most used', self::TEXT_DOMAIN ),
			'popular_items'              => __( 'Popular Items', self::TEXT_DOMAIN ),
			'search_items'               => __( 'Search Items', self::TEXT_DOMAIN ),
			'not_found'                  => __( 'Not Found', self::TEXT_DOMAIN ),
			'no_terms'                   => __( 'No items', self::TEXT_DOMAIN ),
			'items_list'                 => __( 'Items list', self::TEXT_DOMAIN ),
			'items_list_navigation'      => __( 'Items list navigation', self::TEXT_DOMAIN ),
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
		register_taxonomy( 'mnc_folder', [ 'mnc_filegroups' ], $args );

		add_action( 'add_meta_boxes', function () {
			add_meta_box(
				'mnc_mbox_postname',
				'Shortcode: (Copy & Paste)',
				array( $this, 'display_postname' ),
				'mnc_filegroups',
				'normal',
				'high'
			);
		} );


	}

	public function init_acf() {
		acf_add_local_field_group( array(
			'key'                   => 'group_5d39ce66b4ddb',
			'title'                 => 'MNC Filegroups',
			'fields'                => array(
				array(
					'key'               => 'field_5d39ce89d63f4',
					'label'             => 'Dokumente',
					'name'              => 'mnc_filegroup',
					'type'              => 'repeater',
					'instructions'      => '',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'collapsed'         => '',
					'min'               => 0,
					'max'               => 0,
					'layout'            => 'table',
					'button_label'      => '',
					'sub_fields'        => array(
						array(
							'key'               => 'field_5d39cecfd63f5',
							'label'             => 'Dokument',
							'name'              => 'mnc_file',
							'type'              => 'file',
							'instructions'      => 'Wählen Sie die Datei aus. Erlaubt sind zzt. PDF, Word-Dateien, Excel-Dateien',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'return_format'     => 'array',
							'library'           => 'all',
							'min_size'          => '',
							'max_size'          => '',
							'mime_types'        => 'pdf,doc,docx,xls,zip',
						),
					),
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'mnc_filegroups',
					),
				)
			),
			'menu_order'            => 0,
			'position'              => 'acf_after_title',
			'style'                 => 'seamless',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'        => '',
			'active'                => true,
			'description'           => '',
		) );
	}

	public function display_postname() {
		global $post;
		$text = "[mnc_doclist id={$post->ID}]";
		echo( $text );
	}

}
