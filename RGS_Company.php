<?php
/*
 ____   _   ____         ____          
|  _ \ (_) / ___|  __ _ / ___|   __ _  
| |_) || || |  _  / _` |\___ \  / _` | 
|  _ < | || |_| || (_| | ___) || (_| | 
|_| \_\|_| \____| \__,_||____/  \__,_|                                          
*/
//----------------------------------------------------------
/**
 * @class RGS_Company
 * @fullname RiGaSa Companion
 * @package RGS_Company
 * @category Core
 * @filesource assets/plugins/Entreprise/RGS_Company.php
 * @version 0.0.1
 * @created 2020-10-02
 * @author  Ri.Ga.Sa <rigasa@rigasa.ch>
 * @updated 2020-10-02
 * @copyright 2020 Ri.Ga.Sa
 * @license http://www.php.net/license/3_01.txt  PHP License 3.01
 * pChart http://pchart.sourceforge.net/screenshots.php
*/                                                                                    
//--------------------------------------
if ( ! defined( 'ABSPATH' ) ) exit; // SECURITY : Exit if accessed directly
//--------------------------------------
if( ! class_exists( 'RGS_Company' ) ):
	//----------------------------------
	class RGS_Company
	{
		//------------------------------
		//--------------------------------------------------
		private static $instance; // THE only instance of the class
		/**
		 * Identifier, namespace
		 */
		protected $themeKey = '';

		/**
		 * The option value in the database will be based on get_stylesheet()
		 * so child themes don't share the parent theme's option value.
		 */
		protected $optionKey = '';
		//--------------------------------------------------
		/**
		 * Slug
		 *
		 * @var string
		 */
		static public $gSlug;
		//---
		static public $gFile;
		static public $gDir;
		static public $gUrl;
		//---
		static public $gBasename;
		// Plugin Hierarchy
		static public $gAssetsDir;
		static public $gAssetsUrl;
			
		static public $gLangDir;
		static public $gClassesDir;

		static public $gJsDir;
		static public $gJsUrl;

		static public $gCssDir;
		static public $gCssUrl;

		static public $gImgDir;
		static public $gImgUrl;

		static public $gLibsDir;
		static public $gLibsUrl;

		static public $gBackendDir;
		static public $gBackendUrl;

		static public $gDatasDir;
		static public $gDatasUrl;
		
		static public $gWidgetsDir;
		static public $gWidgetsUrl;
		//---
		static public $gTemplatesDir;
		static public $gTemplatesUrl;
		//---
		static public $gViewsDir;
		static public $gViewsUrl;
		//---
		static public $gTags;
		static public $gResponses;
		static public $gResults;
		//---
		static public $gAdminPageId;
		//---
		const K_SLUG = 'rgsCompany';
		const K_PREFIX = 'rgsCompany-';
		const K_VERS = '1.0.0';
		
		//------------------------------
		public function __construct( $args = NULL )
		{
			// Set option key based on get_stylesheet()
			if ( NULL === $args ) :
				$args[ 'themeKey' ] = self::K_SLUG;
			endif;

			// Set option key based on get_stylesheet()
			$this->themeKey  = $args[ 'themeKey' ];
			$this->optionKey = $this->themeKey . '_options'; 
			self::setupGlobals_fn();
			self::loadDependencies_fn();
			self::setupHooks_fn();
		}
		//--------------------------------------------------
		/**
		 * Return an instance of this class.
		 *
		 * @access public
		 *
		 * @return object A single instance of this class.
		 * @static
		 */
		public static function getInstance_fn() 
		{
			// If the single instance hasn't been set, set it now.
			if ( NULL == self::$instance ) :
				self::$instance = new self;
			endif;

			return self::$instance;
		}
		//--------------------------------------------------	
		public static function getThemeKey_fn()
		{
			return self::$themeKey;
		}
		//--------------------------------------------------	
		public static function getOptionKey_fn()
		{
			return self::$optionKey;
		}
		//--------------------------------------------------
		// Constructor Methods
		//--------------------------------------------------
		/**
		 * Sets some globals for the class
		 *
		 * @access private
		 */
		private function setupGlobals_fn() 
		{
			//---
			$this->_version        	= self::K_VERS;
			self::$gFile          	= __FILE__;
			self::$gDir     		= trailingslashit( dirname( self::$gFile ) );
	
			self::$gUrl				= trailingslashit( get_site_url() ) . str_replace( ABSPATH, '', self::$gDir );
			//---
			$lName 					= basename( self::$gDir );
			$gBasename 				= explode( $lName, self::$gFile );
			self::$gBasename       	= $lName . $gBasename[ 1 ];
			//
			// Directories Hierarchy
			//
			self::$gBackendDir   	= self::$gDir . trailingslashit( 'admin' );
			self::$gBackendUrl   	= self::$gUrl . trailingslashit( 'admin' );
			
			self::$gDatasDir   		= self::$gDir . trailingslashit( 'datas' );
			self::$gDatasUrl   		= self::$gUrl . trailingslashit( 'datas' );

			self::$gAssetsDir   	= self::$gDir . trailingslashit( 'assets' );
			self::$gAssetsUrl   	= self::$gUrl . trailingslashit( 'assets' );
			
			self::$gLangDir     	= self::$gAssetsDir . trailingslashit( 'languages' );
			self::$gClassesDir    	= self::$gAssetsDir . trailingslashit( 'classes' );
			
			self::$gJsDir      		= self::$gAssetsDir . trailingslashit( 'scripts' );
			self::$gJsUrl      		= self::$gAssetsUrl . trailingslashit( 'scripts' );
			
			self::$gCssDir     		= self::$gAssetsDir . trailingslashit( 'styles' );
			self::$gCssUrl     		= self::$gAssetsUrl . trailingslashit( 'styles' );
			
			self::$gImgDir      	= self::$gAssetsDir . trailingslashit( 'images' );
			self::$gImgUrl     		= self::$gAssetsUrl . trailingslashit( 'images' );

			self::$gLibsDir			= self::$gAssetsDir . trailingslashit( 'libraries' );
			self::$gLibsUrl			= self::$gAssetsUrl . trailingslashit( 'libraries' );
			
			self::$gWidgetsDir   	= self::$gAssetsDir . trailingslashit( 'widgets' );
			self::$gWidgetsUrl   	= self::$gAssetsUrl . trailingslashit( 'widgets' );
			//---
			self::$gTemplatesDir 	= self::$gAssetsDir. trailingslashit( 'templates'  );
			self::$gTemplatesUrl 	= self::$gAssetsUrl. trailingslashit( 'templates'  );
			//---
			self::$gViewsDir 	= self::$gAssetsDir. trailingslashit( 'views'  );
			self::$gViewsUrl 	= self::$gAssetsUrl. trailingslashit( 'views'  );
			//---
			self::$gSlug 			= sanitize_title( self::K_SLUG );
			//---
			self::$gTags = array();
			self::$gResponses = array();
			self::$gResults = array();
			//---
			self::$gAdminPageId = null;
			//---
		}
		//--------------------------------------------------
		/**
		 * Load the required dependencies for this theme.
		 *
		 * Include the following files that make up the theme:
		 *
		 * @since    0.1.0
		 * @access   private
		 */
		private function loadDependencies_fn() 
		{
			//------------------------------------------------------
			$fileRequired = self::$gDir . 'RGS_CompanyMBoxes.php';
			if( file_exists( $fileRequired ) ):
				require_once( $fileRequired );
			endif;
			//------------------------------------------------------
			$fileRequired = self::$gDir . 'RGS_CompanySettings.php';
			if( file_exists( $fileRequired ) ):
				require_once( $fileRequired );
			endif;
			//------------------------------------------------------
			$fileRequired = self::$gLibsDir . 'ZC.php';
			if( file_exists( $fileRequired ) ):
				require_once( $fileRequired );
			endif;
			//------------------------------------------------------
			$fileRequired = self::$gDir . 'RGS_CompanySingle.php';
			if( file_exists( $fileRequired ) ):
				require_once( $fileRequired );
			endif;
			//------------------------------------------------------
			$fileRequired = self::$gDir . 'RGS_CompanyInquest.php';
			if( file_exists( $fileRequired ) ):
				require_once( $fileRequired );
			endif;
			//------------------------------------------------------
			$fileRequired = self::$gDir . 'RGS_FormStats.php';
			if( file_exists( $fileRequired ) ):
				require_once( $fileRequired );
			endif;
			//------------------------------------------------------
			$fileRequired = self::$gDir . 'RGS_CompanyTemplates.php';
			if( file_exists( $fileRequired ) ):
				require_once( $fileRequired );
			endif;
			//------------------------------------------------------
			$fileRequired = self::$gDir . 'RGS_Shortcodes.php';
			if( file_exists( $fileRequired ) ):
				require_once( $fileRequired );
			endif;
			//------------------------------------------------------
			$fileRequired = self::$gDir . 'RGS_CompanyCampaigns.php';
			if( file_exists( $fileRequired ) ):
				require_once( $fileRequired );
			endif;
			//------------------------------------------------------
			unset($fileRequired);
		}
		//--------------------------------------------------
		private function setupHooks_fn() 
		{
			//
			// SETUPS
			add_action( 'after_setup_theme', array( __CLASS__, 'overrideParent_fn' ), 10 );
			add_action( 'after_setup_theme', array( __CLASS__, 'removeFeatures_fn' ), 11 );
			add_action( 'after_setup_theme', array( __CLASS__, 'setupTheme_fn' ), 12 );
			//
			add_action( 'init', array( __CLASS__, 'loadTextdomain_fn' ), 0 );
			//----------------------------------------------------
			// CREATE CPT
			add_action( 'init', array( __CLASS__, 'registerCPT_fn' ), 1 );
			//
			//----------------------------------------------------
			// ADMIN MENU OPTIONS AND VIEWERS
			add_action( 'admin_menu', array( __CLASS__, 'adminMenu_fn' ) );
			//
			if(is_admin()):
				// Styles for admin

				add_action('admin_print_styles', array( __CLASS__, 'adminStyles_fn'), 11);

				//
				add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueueScripts_fn'), 11 );
			
				add_action( 'admin_print_scripts-post-new.php', array( __CLASS__, 'adminEnqueueScripts_fn'), 12 );
				add_action( 'admin_print_scripts-post.php', array( __CLASS__, 'adminEnqueueScripts_fn'), 12 );
			
				// Add columns in list
				add_filter( 'manage_' . self::getCPT_fn() . '_posts_columns', array( __CLASS__, 'manageAdminColumns_fn') );
				add_action( 'manage_' . self::getCPT_fn() . '_posts_custom_column', array( __CLASS__, 'setAdminColumns_fn'), 10, 2 );
				add_filter( 'manage_edit-' . self::getCPT_fn() . '_sortable_columns', array( __CLASS__, 'sortableColumns_fn') );
				//
			else:
				add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueueFrontend_fn'), 11 );
				add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueueScripts_fn'), 11 );
			endif;
			// SINGLE
			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueueSingle_fn'), 11 );
			add_action( 'before_delete_post', array(__CLASS__, 'deleteAttachedItems_fn' ) );
	}
		//------------------------------
	//|-----------------
	//| Hooks Methods
	//|------------------
		//--------------------------------------------------
		static function registerCPT_fn()
		{
			$TD = self::getTD_fn();
			
			$labels = array(
				'name'                  => _x( 'Companies', 'Post Type General Name', $TD ),
				'singular_name'         => _x( 'Company', 'Post Type Singular Name', $TD ),
				'menu_name'             => __( 'Companies', $TD ),
				'name_admin_bar'        => __( 'Company', $TD ),
				'archives'              => __( 'Company Archives', $TD ),
				'attributes'            => __( 'Company Attributes', $TD ),
				'parent_item_colon'     => __( 'Parent Company:', $TD ),
				'all_items'             => __( 'All Companies', $TD ),
				'add_new_item'          => __( 'Add New Company', $TD ),
				'add_new'               => __( 'Add Company', $TD ),
				'new_item'              => __( 'New Company', $TD ),
				'edit_item'             => __( 'Edit Company', $TD ),
				'update_item'           => __( 'Update Company', $TD ),
				'view_item'             => __( 'View Company', $TD ),
				'view_items'            => __( 'View Companies', $TD ),
				'search_items'          => __( 'Search Company', $TD ),
				'not_found'             => __( 'Not found', $TD ),
				'not_found_in_trash'    => __( 'Not found in Trash', $TD ),
				'featured_image'        => __( 'Featured Image', $TD ),
				'set_featured_image'    => __( 'Set featured image', $TD ),
				'remove_featured_image' => __( 'Remove featured image', $TD ),
				'use_featured_image'    => __( 'Use as featured image', $TD ),
				'insert_into_item'      => __( 'Insert into company', $TD ),
				'uploaded_to_this_item' => __( 'Uploaded to this company', $TD ),
				'items_list'            => __( 'Companies list', $TD ),
				'items_list_navigation' => __( 'Companies list navigation', $TD ),
				'filter_items_list'     => __( 'Filter companies list', $TD ),
			);
			$rewrite = array(
				'slug'                  => __( 'Company', $TD ),
				'with_front'            => true,
				'pages'                 => true,
				'feeds'                 => false,
			);
			$args = array(
				'label'                 => __( 'Companies', $TD ),
				'description'           => __( 'Company Description', $TD ),
				'labels'                => $labels,
				'supports'              => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
				//'taxonomies'            => array( self::getSlug_fn() . '-type', self::getSlug_fn() . '-post_tag' ),
				'hierarchical'          => true,
				'public'                => true,
				//'show_ui'               => true,
				//'show_in_menu'          => true,
				'menu_position'         => 16,
				'menu_icon'             => 'dashicons-groups',
				//'show_in_nav_menus'     => true,
				'capability_type'       => 'page',
				//'show_in_admin_bar'     => true,
				//'can_export'            => false,
				'has_archive'           => true,
				//'exclude_from_search'   => true,
				//'publicly_queryable'    => true,
				'query_var'             => true,
				'rewrite'               => $rewrite,
				//'show_in_rest'          => false,
			);
			register_post_type( self::getCPT_fn(), $args );
			//----------
			// TAXONOMIES
			//----------
			//self::registerTaxoCat_fn();
			//self::registerTaxoCampaigns_fn();
			//
			
		}
		//--------------------------------------------------
		static function overrideParent_fn()
		{
		
		}
		//--------------------------------------------------
		static function removeFeatures_fn()
		{
			
		}
		//--------------------------------------------------
		static function setupTheme_fn()
		{
			
		}
		//--------------------------------------------------
		static function loadTextdomain_fn()
	  	{
			$locale = get_locale();
			$domain = self::getTD_fn();
			$mofile = self::$gLangDir . $domain . '-' . $locale . '.mo';
			load_textdomain( $domain, $mofile ); 
	  	}
		//--------------------------------------------------
		static function adminEnqueueScripts_fn()
		{
			global $post_type;
    		if( self::getSlug_fn() == $post_type ) :
				//
				if( file_exists( self::$gJsDir . 'adminCompany.js' ) ):
					wp_enqueue_script( 
						self::getSlug_fn() . '_admin', 
						self::$gJsUrl . 'adminCompany.js', 
						array( self::getSlug_fn() ), 
						'0.0.1', 
						true 
					);
				endif;
			
			endif;
		}
		//--------------------------------------------------
		static function loadScripts_fn()
		{
			if( file_exists( self::$gJsDir . 'Company.js' ) ):
				//
				wp_enqueue_media();
				//
				if( is_admin() ):
					wp_enqueue_editor();
				endif;
				//
				// src : https://www.zingchart.com/docs/chart-types/radar
				wp_enqueue_script( 
					self::getSlug_fn() . '_charts', 
					'https://cdn.zingchart.com/zingchart.min.js', 
					array( 'jquery' ), 
					'0.0.1', 
					true 
				);
				//
				wp_enqueue_script( 
					self::getEnqueueName_fn(), 
					self::$gJsUrl . 'Company.js', 
					array( self::getSlug_fn() . '_charts' ), 
					'0.0.1', 
					true 
				);
				//-------------------------
				$localize = self::getLocalize_fn();
				$_args = apply_filters( self::getEnqueueName_fn() .'_localize', $localize );
				$localize = array_replace_recursive( $localize, $_args );
				//
				wp_localize_script( 
					self::getEnqueueName_fn(), 
					'oCompany', 
					$localize
				); 
			
			endif;
		}
		//-----------------------------------------------
		static function getEnqueueName_fn()
		{
			return self::getSlug_fn();
		}
		//--------------------------------------------------
		static function enqueueScripts_fn()
		{
			//
			global $post_type;
			
			$inContext = FALSE;
			
			if( $post_type ) :
				if( self::getSlug_fn() == $post_type ) :
					//
					$inContext = TRUE;
					//
				endif;
			else:
				// Check 
				if(function_exists('get_current_screen') ) :
					$screen = get_current_screen();
					if( is_object( $screen ) and isset( $screen->post_type ) ) :
						if( self::getSlug_fn() == $screen->post_type ) :
							//
							$inContext = TRUE;
							//
						endif;
					endif;
				endif;
			endif;
			//
			if( ! $inContext ):
				return;
			endif;
			//------------------------------------------------------
			self::loadScripts_fn();
			//------------------------------------------------------
			//------------------------------------------------------
		}
		//--------------------------------------------------
		static function enqueueFrontend_fn()
		{
			if( file_exists( self::$gCssDir . 'companyFrontend.css' ) ):
				wp_enqueue_style( 
					self::getSlug_fn() . '_frontend', 
					self::$gCssUrl . 'companyFrontend.css', 
					array(  ), 
					'0.0.1', 
					'all' 
				);
			endif;
		}
		//--------------------------------------------------
		static function enqueueSingle_fn()
		{
			if( is_singular( self::getSlug_fn() ) || is_post_type_archive( self::getSlug_fn() ) ) :
				if( file_exists( self::$gCssDir . 'companySingle.css' ) ):
					wp_enqueue_style( 
						self::getSlug_fn() . '_single', 
						self::$gCssUrl . 'companySingle.css', 
						array(  ), 
						'0.0.1', 
						'all' 
					);
				endif;
			endif;
		}
		//--------------------------------------------------
		static function enqueueSettingsScripts_fn()
		{
			if( file_exists( self::$gJsDir . 'settingsPage.js' ) ):
				wp_enqueue_script( 
					self::getSlug_fn() . '_settings', 
					self::$gJsUrl . 'settingsPage.js', 
					array( self::getSlug_fn() ), 
					'0.0.1', 
					true 
				);
			endif;
		}
		//--------------------------------------------------
		static function getLocalize_fn()
		{
			$SLUG = self::getSlug_fn();
			$TD = self::getTD_fn();
			$options = RGS_CompanySettings::getOption_fn();
			//
			return array( 
				'ajaxUrl' 			=> admin_url( 'admin-ajax.php' ),
				'nonce' 			=> wp_create_nonce( $SLUG . '-nonce' ),
				'isAdmin' 			=> is_admin()? true: false,
				'isFrontPage' 		=> is_front_page()? true: false,
				'slug' 				=> $SLUG,
				'assetsUrl' 		=> self::$gAssetsUrl,
				'stylesUrl'			=> self::$gCssUrl,
				'scriptsUrl'		=> self::$gJsUrl,
				'imgUrl' 			=> self::$gImgUrl,
				'options' 			=> $options,
				'delay' 			=> 1000,
				'isSingleOrPage' 	=> ( is_page() || is_single() )? true: false,
				'taxoCampaign'		=> self::getTaxoCampaignName_fn(),
				'pagePermalink' 	=> get_the_permalink(),
				'lang' 				=> array(
					'yes' 				=> __( 'Yes', $TD ),
					'delMedia' 			=> __( 'Are you sure you want to delete this media?', $TD ),
					'noMediaToDel'		=> __( 'No media to delete!', $TD ),
					'noLogoId'			=> __( 'LOGO ID NOT EXISTS !', $TD ),
					'delEmpty'			=> __( 'Please select the items to delete.', $TD ),
					'delInquest'		=> __( 'Are you sure you want to delete inquest(s)?', $TD )

				)
			);
		}
		//--------------------------------------------------
		// CPT
		//--------------------------------------------------
		static function getSlug_fn()
		{
			return 'company';
		}
		//--------------------------------------------------
		static function getTD_fn()
		{
			return 'company';
		}
		//--------------------------------------------------
		static function getCPT_fn()
		{
			return 'company';
		}
		//--------------------------------------------------
		// TAXOS
		//--------------------------------------------------
		static function getTaxoCampaignName_fn()
		{
			return self::getCPT_fn() . '-campaign';
		}
		//--------------------------------------------------
		static function registerTaxoCat_fn() {
			
			$TD = self::getTD_fn();
			
			$labels = array(
				'name'                       => _x('Types', 'Taxonomy General Name', $TD),
				'singular_name'              => _x('Type', 'Taxonomy Singular Name', $TD),
				'menu_name'                  => __('Types', $TD),
				'all_items'                  => __('All Types', $TD),
				'parent_item'                => __('Parent Type', $TD),
				'parent_item_colon'          => __('Parent Type:', $TD),
				'new_item_name'              => __('New Type Name', $TD),
				'add_new_item'               => __('Add New Type', $TD),
				'edit_item'                  => __('Edit Type', $TD),
				'update_item'                => __('Update Type', $TD),
				'view_item'                  => __('View Type', $TD),
				'separate_items_with_commas' => __('Separate types with commas', $TD),
				'add_or_remove_items'        => __('Add or remove types', $TD),
				'choose_from_most_used'      => __('Choose from the most used', $TD),
				'popular_items'              => __('Popular Types', $TD),
				'search_items'               => __('Search Types', $TD),
				'not_found'                  => __('Not Found', $TD),
				'no_terms'                   => __('No types', $TD),
				'items_list'                 => __('Types list', $TD),
				'items_list_navigation'      => __('Types list navigation', $TD),
			);
			$rewrite = array(
				'slug'                       => _x('type', 'property_type', $TD),
				'with_front'                 => true,
				'hierarchical'               => false,
			);
			$args = array(
				'labels'                     => $labels,
				'hierarchical'               => true,
				'public'                     => true,
				'show_ui'                    => true,
				'show_admin_column'          => true,
				'show_in_nav_menus'          => true,
				'show_tagcloud'              => false,
				'rewrite'                    => $rewrite,
				'show_in_rest'               => false,
			);
			
			register_taxonomy(self::getSlug_fn() . '-type', array(self::getSlug_fn()), $args);
		}
		//--------------------------------------------------
		static function registerTaxoCampaigns_fn() {
			
			$TD = self::getTD_fn();
			$TAXO = self::getTaxoCampaignName_fn();
			$CPT = self::getSlug_fn();
			
			$labels = array(
				'name'                       => _x('Campaigns', 'Taxonomy General Name', $TD),
				'singular_name'              => _x('Campaign', 'Taxonomy Singular Name', $TD),
				'menu_name'                  => __('Campaigns', $TD),
				'all_items'                  => __('All Campaigns', $TD),
				'parent_item'                => __('Parent Campaign', $TD),
				'parent_item_colon'          => __('Parent Campaign:', $TD),
				'new_item_name'              => __('New Campaign Name', $TD),
				'add_new_item'               => __('Add New Campaign', $TD),
				'edit_item'                  => __('Edit Campaign', $TD),
				'update_item'                => __('Update Campaign', $TD),
				'view_item'                  => __('View Campaign', $TD),
				'separate_items_with_commas' => __('Separate campaigns with commas', $TD),
				'add_or_remove_items'        => __('Add or remove Campaigns', $TD),
				'choose_from_most_used'      => __('Choose from the most used', $TD),
				'popular_items'              => __('Popular Campaigns', $TD),
				'search_items'               => __('Search Campaigns', $TD),
				'not_found'                  => __('Not Found', $TD),
				'no_terms'                   => __('No campaign', $TD),
				'items_list'                 => __('Campaigns list', $TD),
				'items_list_navigation'      => __('Campaigns list navigation', $TD),
			);
			$rewrite = array(
				'slug'                       => _x('campaign', 'property_type', $TD),
				'with_front'                 => true,
				'hierarchical'               => false,
			);
			$args = array(
				'labels'                     => $labels,
				'hierarchical'               => true,
				'public'                     => true,
				'show_ui'                    => true,
				'show_admin_column'          => true,
				'show_in_nav_menus'          => true,
				'show_tagcloud'              => false,
				'rewrite'                    => $rewrite,
				'show_in_rest'               => false,
			);
			
			register_taxonomy( $TAXO, array( $CPT ), $args );
			//----------------
			// CUSTOM FIELDS
			//----------------
			// Add 
			add_action( $TAXO . '_add_form_fields', array( __CLASS__, 'campaignTaxoAddCustomFields_fn' ), 10, 2 );  
  
			// Save Added
			add_action( 'created_' . $TAXO, array( __CLASS__, 'campaignTaxoSaveCustomFields_fn' ), 10, 2 ); 
			
			// Edit  
			add_action( $TAXO . '_edit_form_fields', array( __CLASS__, 'campaignTaxoEditCustomFields_fn' ), 10, 2 );  
  
			// Save Edited
			add_action( 'edited_' . $TAXO, array( __CLASS__, 'campaignTaxoSaveCustomFields_fn' ), 10, 2 ); 
		}
		//--------------------------------------------------
		// TAXONOMIES CUSTOM FIELDS
		//--------------------------------------------------
		function campaignTaxoGetCustomFields_fn( $termID = 0 ) 
		{  
			//
			$termMETAS = array();
			if($termID > 0 ) :
				$termMETAS['startDate'] = ( get_term_meta( $termID, 'startDate', true ) ) ? get_term_meta( $termID, 'startDate', true ) : ''; 
				$termMETAS['endDate'] = ( get_term_meta( $termID, 'endDate', true ) ) ? get_term_meta( $termID, 'endDate', true ) : ''; 
				$termMETAS['cLogo'] = ( get_term_meta( $termID, 'cLogo', true ) ) ? get_term_meta( $termID, 'cLogo', true ) : ''; 
			endif;
			//
			return $termMETAS;
			//
		}
		//--------------------------------------------------
		function campaignTaxoAddCustomFields_fn( $tag ) 
		{  
			// A callback function to add a custom field to our "campaign" taxonomy 
			$TD = self::getTD_fn();
			$DATE = date('d/m/Y');
			// 
			$termMETAS = self::campaignTaxoGetCustomFields_fn( $tag->term_id );
			//
			$startDate = $termMETAS['startDate']; 
			$endDate = $termMETAS['endDate']; 
			$cLogo = $termMETAS['cLogo']; 
			//
		?>  
		<div class="form-field">
			<label for="startDate"><?php _e('Start Date', $TD); ?></label>  

			<input type="date" name="term_meta[startDate]" id="term_meta[startDate]" value="<?php echo $startDate; ?>"><br />  
			<span class="description"><?php _e('The start date for campaign'); ?></span>  
	   	</div>
		
		<div class="form-field">
			<label for="endDate"><?php _e('End Date', $TD); ?></label> 

			<input type="date" name="term_meta[endDate]" id="term_meta[endDate]" value="<?php echo $endDate; ?>"><br />  
			<span class="description"><?php _e('The end date for campaign'); ?></span>   
	   	</div>

		<div class="form-field">
			<label for="cLogoID"><?php _e( 'Logo', $TD ); ?></label>

			<input type="hidden" name="term_meta[cLogo]" id="cLogoID" value="<?php echo $cLogo; ?>">
			
			<div id="cLogoWrapper">
			 <?php if ( $cLogo ) : ?>
			   <?php echo wp_get_attachment_image ( $cLogo, 'thumbnail', FALSE, array('class' => 'cLogoImage') ); ?>
			 <?php endif; ?>
		   </div>
		   <p>
			 <input type="button" class="button button-secondary cAddMediaButton" id="cAddMediaButton" name="cAddMediaButton" value="<?php _e( 'Add media', $TD ); ?>" data-uploader-title="<?php _e('Add media to campaign', $TD ); ?>" data-uploader-button-text="<?php _e('Add media', $TD ); ?>" />
			 <input type="button" class="button button-secondary cRemoveMediaButton" id="cRemoveMediaButton" name="cRemoveMediaButton" value="&times; <?php _e( 'Remove Image', $TD ); ?>" />
		   </p>
			<br />  
			<span class="description"><?php _e('The logo for campaign'); ?></span>  
	   	</div>
		<br><br>

		<?php  
		} 
		//--------------------------------------------------
		function campaignTaxoEditCustomFields_fn( $tag ) 
		{  
			// A callback function to add a custom field to our "campaign" taxonomy 
			$TD = self::getTD_fn();
		    // 
			$termMETAS = self::campaignTaxoGetCustomFields_fn( $tag->term_id );
			//
			$startDate = $termMETAS['startDate']; 
			$endDate = $termMETAS['endDate']; 
			$cLogo = $termMETAS['cLogo']; 
			//
		?>  
			<tr class="form-field">
				<th scope="row"><label for="startDate"><?php _e('Start Date', $TD); ?></label></th>
				<td>
					<input type="date" name="term_meta[startDate]" id="term_meta[startDate]" value="<?php echo $startDate; ?>" />
					<p class="description"><?php _e('The start date for campaign'); ?></p>
				</td>
			</tr>

			<tr class="form-field">
				<th scope="row"><label for="endDate"><?php _e('End Date', $TD); ?></label></th>
				<td>
					<input type="date" name="term_meta[endDate]" id="term_meta[endDate]" value="<?php echo $endDate; ?>" />
					<p class="description"><?php _e('The end date for campaign'); ?></p>
				</td>
			</tr>

			<tr class="form-field">
				<th scope="row"><label for="cLogoID"><?php _e( 'Logo', $TD ); ?></label></th>
				<td>
					<input type="hidden" name="term_meta[cLogo]" id="cLogoID" value="<?php echo $cLogo; ?>" />
					
					<div id="cLogoWrapper">
					 <?php if ( $cLogo ) : ?>
					   <?php echo wp_get_attachment_image ( $cLogo, 'thumbnail', FALSE, array('class' => 'cLogoImage') ); ?>
					 <?php endif; ?>
				    </div>
					
					<p>
					 <input type="button" class="button button-secondary cAddMediaButton" id="cAddMediaButton" name="cAddMediaButton" value="<?php _e( 'Add media', $TD ); ?>" data-uploader-title="<?php _e('Add image to campaign', $TD ); ?>" data-uploader-button-text="<?php _e('Add image', $TD ); ?>" />
						
					 <input type="button" class="button button-secondary cRemoveMediaButton" id="cRemoveMediaButton" name="cRemoveMediaButton" value="&times; <?php _e( 'Remove Image', $TD ); ?>" />
				   </p>
					<br />  
					
					<p class="description"><?php _e('The logo for campaign'); ?></p>
				</td>
			</tr>
			
		<?php  
		} 
		//--------------------------------------------------
		function campaignTaxoSaveCustomFields_fn( $term_id ) 
		{ 
			//
			$termMETAS = isset($_POST['term_meta']) ? $_POST['term_meta'] : NULL;
			// A callback function to save our extra taxonomy field(s) 
			if ( isset( $termMETAS ) ) : 
				//
				foreach ( $termMETAS as $metaKEY => $metaVALUE ):
					//
					update_term_meta( $term_id, $metaKEY, $metaVALUE );
					//
				endforeach; 
				//
			endif;
			//
		}
		//--------------------------------------------------
		static function adminStyles_fn()
		{
			if( file_exists( RGS_Company::$gCssDir  . 'adminStyles.css' ) ):
				
				wp_enqueue_style( 
					self::getSlug_fn() . '_adminStyles', 
					RGS_Company::$gCssUrl . 'adminStyles.css', 
					array( ), 
					'0.0.1', 
					'all' 
				);
			endif;
		}
		//--------------------------------------------------
		// ADMIN MENU
		//--------------------------------------------------
		static function getAdminMenuId_fn()
		{
			return self::getSlug_fn() . '-settings';
		}
		//--------------------------------------------------
		static function adminMenu_fn()
		{
			self::$gAdminPageId = add_submenu_page(
				'edit.php?post_type=' . self::getSlug_fn(),
				__( 'Companies Settings', self::getTD_fn() ),
				'<i class="wp-menu-image dashicons-before dashicons-admin-settings"></i> ' . __( 'Settings', self::getTD_fn() ),
				'manage_options',
				self::getAdminMenuId_fn(),
				array( __CLASS__, 'adminMenuPage_fn' )
			);
			
			add_action( 'admin_print_styles-' . self::$gAdminPageId, array( __CLASS__, 'adminEnqueueStyles_fn' ) );
			
			// Adds my_help_tab when my_admin_page loads
    		add_action( 'load-' . self::$gAdminPageId, array( __CLASS__, 'adminAddHelpTab_fn' ) );
			
			$inquestPageId = RGS_CompanyInquest::getSubMenu_fn();
			#$campaignsPageId = RGS_CompanyCampaigns::adminMenu_fn();
			
			
		}
		//--------------------------------------------------
		static function adminMenuPage_fn()
		{
			// check user capabilities
    		if ( ! current_user_can( 'manage_options' ) ) :
        		return;
    		endif;
			
			if( is_file( self::$gViewsDir . 'settingsPage.php' ) ):
				include_once(self::$gViewsDir . 'settingsPage.php');
			else:
				echo '<h1>';
				esc_html_e( 'No Settings Page Loaded!', self::getTD_fn() ); 
				echo '</h1>';
			endif;
			
		}
		//--------------------------------------------------
		static function adminAddHelpTab_fn()
		{
			$screen = get_current_screen();
			if ( $screen->id != self::$gAdminPageId ) { return; }
			//
			$screen->add_help_tab( array(
				'id' => self::getSlug_fn() . '_helpTab',
				'title' => __('Company Help Tab', self::getTD_fn()),
				'content' => '<p>'
				. __( 'Descriptive content that will show in Company Help Tab body goes here.', self::getTD_fn() )
				. '</p>',
			) );
			// Load settings page scripts
			if(is_admin()):
				add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueueSettingsScripts_fn'), 11 );
        	endif;
		}
		//---------------------------------------------------------------
		static function adminEnqueueStyles_fn()
		{
			
			if( file_exists( RGS_Company::$gCssDir  . 'adminSettings.css' ) ):
				
				wp_enqueue_style( 
					self::getSlug_fn() . '_adminSettings', 
					RGS_Company::$gCssUrl . 'adminSettings.css', 
					array( ), 
					'0.0.1', 
					'all' 
				);
			endif;
			
		}
		//--------------------------------------------------
		// Columns
		//--------------------------------------------------
		static function manageAdminColumns_fn( $columns )
		{
			$columns = array(
				'cb' => $columns['cb'],
      			'image' => __( 'Image' ),
				'title' => __( 'Title' ),
				'mail' => __('Mail address', self::getTD_fn()),
				'nb' => __('Number of employees', self::getTD_fn()),
				'post_id' => __('ID', self::getTD_fn())
			);
			
			return $columns;
		}
		//--------------------------------------------------
		static function setAdminColumns_fn( $column, $post_id )
		{
			$metas = get_post_meta( $post_id, RGS_CompanyMBoxes::getOptionNameMB_fn() );
			$thumb = get_the_post_thumbnail( $post_id, array(60, 60) );
			$metas = isset($metas[0]) ? $metas[0]: '';

			switch ( $column ) :
				// display featured image
				case 'image':
					echo isset($thumb) ? $thumb : '';
					break;
				case 'post_id':
					echo $post_id;
					break;
				case 'mail':
					echo isset($metas['REF_MailAddress']) ? $metas['REF_MailAddress'] : '';
					break;
				case 'nb':
					echo isset($metas['REF_NbEmployees']) ? $metas['REF_NbEmployees'] : '';
					break;
			endswitch;
		}
		//--------------------------------------------------
		static function sortableColumns_fn( $columns ) {
  			$columns['post_id'] = 'ID';
  			return $columns;
		}
		//--------------------------------------------------
		// METHODS
		//--------------------------------------------------
		static function getMoreDays_fn( $date = null, $more = 14, $format = 'Y-m-d' )
		{
			//
			if( ! $format ):
				$format = 'Y-m-d'; //'d/m/Y';
			endif;
			//
			$moreDays = $more; // 2 weeks
			if( ! $date ):
				$date = date($format);
			endif;
			//
			return date($format, strtotime($date . ' +' . $moreDays . ' days'));
			//
		}
		//--------------------------------------------------
		static function isDateRange_fn($debut, $end, $format='Y-m-d') {
			$curDate = date($format);
			return ($curDate >= $debut and $curDate <= $end);

		}
		//--------------------------------------------------
		static function getRefsDatas_fn( $post_id )
		{
			#$refDatas = (array) get_post_meta($post_id, RGS_CompanyMBoxes::getOptionNameMB_fn(), TRUE);
			$REF_MailAddress = get_post_meta($post_id, 'REF_MailAddress', TRUE);
			$REF_NbEmployees = get_post_meta($post_id, 'REF_NbEmployees', TRUE);
			$REF_Shortcode = get_post_meta($post_id, 'REF_Shortcode', TRUE);
			$REF_FormID = get_post_meta($post_id, 'REF_FormID', TRUE);
			$REF_Template = get_post_meta($post_id, 'REF_Template', TRUE);
			$REF_Campaigns = get_post_meta($post_id, 'REF_Campaigns', TRUE);
			//
			if ( empty( $refDatas ) ) :
				$refDatas = array();
				$refDatas['REF_MailAddress']= !empty($REF_MailAddress) ? $REF_MailAddress : ''; 
				$refDatas['REF_NbEmployees'] = !empty($REF_NbEmployees) ? $REF_NbEmployees : 0;
				$refDatas['REF_Shortcode']= !empty($REF_Shortcode) ? $REF_Shortcode : ''; 
				$refDatas['REF_FormID'] = !empty($REF_FormID) ? $REF_FormID : 329; 
				$refDatas['REF_Template'] = !empty($REF_Template) ? $REF_Template : 'companySingle.php'; 
				$refDatas['REF_Campaigns'] = !empty($REF_Campaigns) ? $REF_Campaigns : array(); 
			endif;

			if(! empty($refDatas['REF_Shortcode']) ):
				$refDatas['REF_Shortcode'] = addslashes( $refDatas['REF_Shortcode'] );
			endif;
			//
			return $refDatas;
			//
		}
		//--------------------------------------------------
		static function setTemplateSingle_fn($postID)
		{
			/*
			add_post_meta($postID, '_wp_page_template', 'companySingle.php' );
			*/
		}
		//--------------------------------------------------
		static function deleteAttachedFile_fn( $id )
		{
			$_wp_attached_file = get_post_meta( $id, '_wp_attached_file', true);
			$original = basename($_wp_attached_file);
			$pos = strpos(strrev($original), '.');
			if (strpos($original, '.') !== false) :
				$ext = explode('.', strrev($original));
				$ext = strrev($ext[0]);
			else :
				$ext = explode('-', strrev($original));
				$ext = strrev($ext[0]);
			endif;

			$pattern = $uploadpath['basedir'].'/'.dirname($_wp_attached_file).'/'.basename($original, '.'.$ext).'-[0-9]*x[0-9]*.'.$ext;
			$original= $uploadpath['basedir'].'/'.dirname($_wp_attached_file).'/'.basename($original, '.'.$ext).'.'.$ext;
			if (getimagesize($original)){
				$thumbs = glob($pattern);
				if (is_array($thumbs) && count($thumbs) > 0){
					foreach($thumbs as $thumb)
						unlink($thumb);
				}
			}
			wp_delete_attachment( $id, true );

		}
		//--------------------------------------------------
		static function deleteAttachedItems_fn( $postID )
		{
			$subposts = get_children(array(
				'post_parent' => $postID,
				'post_type'   => 'any',
				'posts_per_page' => -1,
				'post_status' => 'any'
			));

			// Subposts
			if (is_array($subposts) && count($subposts) > 0) :
				$uploadpath = wp_upload_dir();

				foreach($subposts as $subpost) :
					self::deleteAttachedFile_fn($subpost->ID);
				endforeach;
			endif;

			// Image
			self::deleteAttachedFile_fn($postID);

			// Inquests
			RGS_CompanyInquest::deleteInquestsByPost_fn($postID, 'COMPANY_ID' );
			//
		}
		//--------------------------------------------------
		static function isCustomPostType_fn( $cpt = NULL )
		{
			$allCPT = get_post_types( array ( '_builtin' => FALSE ) );
			// there are no custom post types
			if ( empty ( $allCPT ) )
				return FALSE;

			$customTypes      = array_keys( $allCPT );

			return in_array( $cpt, $customTypes );
		}
		//--------------------------------------------------
		static function getTaxo_fn($POSTID, $TAXO = '')
		{
			$taxoHTML = '';
			//
			if($POSTID > 0 ):
				if(empty($TAXO)) :
					$TAXO = RGS_Company::getSlug_fn() . '-campaign';
				endif;
				$isInRange = FALSE;
				//
				$terms = get_the_terms( $POSTID, $TAXO );
				$countInRange = array();
				//
				foreach($terms as $term):
					//
					$termID = $term->term_id;
					$termName = $term->name;
					// 
					$termMETAS = RGS_Company::campaignTaxoGetCustomFields_fn( $term->term_id );
					$startDate = $termMETAS['startDate']; 
					$endDate = $termMETAS['endDate']; 
					$cLogo = $termMETAS['cLogo']; 
					//
					$termInRange = TRUE; //RGS_Company::isDateRange_fn($startDate, $endDate);
					//
					if($termInRange) :
						//
						$termHTML = '<div id="term-' . $term->term_id . '" class="campaign-row" style="border:1px solid #000000; padding:6px;">';
						$image = wp_get_attachment_image_src ( $cLogo, 'thumbnail', FALSE );
						$imgSrc = '';
						//
						if( is_array( $image ) and isset( $image[0] ) ):
							$imgSrc = $image[0] ;
						endif;
						//
						if( ! empty( $imgSrc ) ):
							$termHTML .= '<span class="campaign-cell-image" style="float:left; margin-right:6px;"><img width="auto" height="66" src="' . $imgSrc . '" class="cLogoImage" alt="" style="height: 66px; width: auto; margin:0px;"></span>';
						endif;

						$termHTML .= '<span class="campaign-cell-title"><strong>'. $termName . '</strong></span><br>';
						$termHTML .= '<span class="campaign-cell-start"><i>'.__('Start', $TD) . ': </i>' . $startDate . '</span><br>';
						$termHTML .= '<span class="campaign-cell-end"><i>'.__('End', $TD) . ': </i>' . $endDate . '</span>';
						$termHTML .= '</div>';
						//
						$countInRange[] = $termHTML;
						//
					endif;
					//
				endforeach;
				//---------------------
				if( ! empty($countInRange) ):

					$taxoHTML = '<div id="campaigns-list" style="width:calc( 100% - 0px); padding-bottom:10px; ">';
					$taxoHTML .= '<h2 style="margin-bottom: 8px;">' . __('Campaigns', $TD) .'</h2>';
					foreach($countInRange as $termDisplay ):
						$taxoHTML .= $termDisplay;
					endforeach;
					$isInRange = TRUE;

					$taxoHTML .= '</div>';
				endif;
			//---------------------
			endif;
			//---------------------
			return $taxoHTML;
		}
		//--------------------------------------------------
		//--------------------------------------------------
		//--------------------------------------------------
		//--------------------------------------------------
		//--------------------------------------------------
		//--------------------------------------------------
		//--------------------------------------------------
		
	};
	//------------------------------------------------------
	if( ! function_exists( 'rgsCompany_fn' ) ):
		function rgsCompany_fn() 
		{
			return RGS_Company::getInstance_fn();
		};
	endif;
	//------------------------------------------------------
	if( ! isset( $GLOBALS[ 'RGS_Company' ] ) ):
		$GLOBALS[ 'RGS_Company' ] = rgsCompany_fn();
	endif;
	//------------------------------------------------------
endif;
//----------------------------------------------------------
