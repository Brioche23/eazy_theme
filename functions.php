<?php

/**
 * Timber starter-theme
 * https://github.com/timber/starter-theme
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

/**
 * If you are installing Timber as a Composer dependency in your theme, you'll need this block
 * to load your dependencies and initialize Timber. If you are using Timber via the WordPress.org
 * plug-in, you can safely delete this block.
 */
$composer_autoload = __DIR__ . '/vendor/autoload.php';
if (file_exists($composer_autoload)) {
	require_once $composer_autoload;
	$timber = new Timber\Timber();
}

/**
 * This ensures that Timber is loaded and available as a PHP class.
 * If not, it gives an error message to help direct developers on where to activate
 */
if (!class_exists('Timber')) {

	add_action(
		'admin_notices',
		function () {
			echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url(admin_url('plugins.php#timber')) . '">' . esc_url(admin_url('plugins.php')) . '</a></p></div>';
		}
	);

	add_filter(
		'template_include',
		function ($template) {
			return get_stylesheet_directory() . '/static/no-timber.html';
		}
	);
	return;
}


/**
 * Adding custom options pages for
 * Generali
 * ...
 */
if (function_exists('acf_add_options_page')) {

	acf_add_options_page();
	acf_add_options_sub_page('General');
}

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = array('templates', 'views');

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;


/**
 * We're going to configure our theme inside of a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
class StarterSite extends Timber\Site
{
	/** Add timber support. */
	public function __construct()
	{
		add_action('after_setup_theme', array($this, 'theme_supports'));
		add_filter('timber/context', array($this, 'add_to_context'));
		add_filter('timber/twig', array($this, 'add_to_twig'));
		add_action('init', array($this, 'register_post_types'));
		add_action('init', array($this, 'register_taxonomies'));
		parent::__construct();
	}
	/** This is where you can register custom post types. */
	public function register_post_types()
	{
		register_post_type(
			'Prodotti',
			// CPT Options
			array(
				'labels' 			=> array(
					'name' 			=> __('Prodotti'),
					'singular_name' => __('Prodotto')
				),
				'public' 			=> true,
				'has_archive' 		=> true,
				'rewrite' 			=> array('slug' => 'prodotti'),
				'show_in_rest' 		=> true,
				'taxonomies' 		=> array('category', 'post_tag'),
				'supports'          => array('title', 'excerpt', 'thumbnail', 'revisions', 'custom-fields',),

			)
		);
	}
	/** This is where you can register custom taxonomies. */
	public function register_taxonomies()
	{
	}

	/** This is where you add some context
	 *
	 * @param string $context context['this'] Being the Twig's {{ this }}.
	 */
	public function add_to_context($context)
	{
		$context['foo']   = 'functions.php';
		$context['stuff'] = 'I am a value set in your functions.php file';
		$context['notes'] = 'These values are available everytime you call Timber::context();';
		$context['menu']  = new Timber\Menu();
		$context['menu_mobile']  = new Timber\Menu('Menu_Mobile');
		$context['site']  = $this;
		// Options
		$context['options'] = get_fields('option');
		return $context;
	}

	public function theme_supports()
	{
		// Add default posts and comments RSS feed links to head.
		add_theme_support('automatic-feed-links');

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support('title-tag');

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support('post-thumbnails');

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		/*
		 * Enable support for Post Formats.
		 *
		 * See: https://codex.wordpress.org/Post_Formats
		 */
		add_theme_support(
			'post-formats',
			array(
				'aside',
				'image',
				'video',
				'quote',
				'link',
				'gallery',
				'audio',
			)
		);

		add_theme_support('menus');
	}

	/** This Would return 'foo bar!'.
	 *
	 * @param string $text being 'foo', then returned 'foo bar!'.
	 */
	public function myfoo($text)
	{
		$text .= ' bar!';
		return $text;
	}

	/** This is where you can add your own functions to twig.
	 *
	 * @param string $twig get extension.
	 */
	public function add_to_twig($twig)
	{

		$twig->addFunction(new Timber\Twig_Function('add_script', function () {

			wp_register_script(
				'script',
				get_template_directory_uri() . '/static/site.js',
				[],
				'0.01'
			);
			wp_register_script('swiper', 'https://unpkg.com/swiper@8/swiper-bundle.min.js', null, null, true);
			wp_register_script('alpine_plugin', 'https://unpkg.com/@alpinejs/collapse@3.x.x/dist/cdn.min.js', null, null, true);
			wp_register_script('alpine', 'https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js', null, null, true);
			wp_nav_menu(array('theme_location' => 'primary'));

			wp_enqueue_script('swiper');
			wp_enqueue_script('alpine_plugin');
			wp_enqueue_script('alpine');
		}));

		$twig->addExtension(new Twig\Extension\StringLoaderExtension());
		// $twig->addFilter(new Twig\TwigFilter('myfoo', array($this, 'myfoo')));

		$twig->addFunction(new \Twig\TwigFunction('get_page_url', function ($query = [], $append = true) {
			$tmp = $append ? $_GET : [];
			foreach ($query as $key => $value) $tmp[$key] = $value;

			return '?' . http_build_query($tmp);
		}));

		// polylang functions
		if (function_exists('pll_current_language')) {
			$twig->addFunction(new Timber\Twig_Function('pll__', 'pll__'));
			$twig->addFunction(new Timber\Twig_Function('pll_e', 'pll_e'));
			$twig->addFunction(new Timber\Twig_Function('pll_current_language', 'pll_current_language'));
			$twig->addFunction(new Timber\Twig_Function('language_switcher', function () {
				pll_the_languages(array('show_flags' => 1, 'show_names' => 1));
			}));
		}


		return $twig;
	}
}

register_nav_menus(array(
	'main_menu' => 'Main Menu',
));


add_action('init', function () {
	pll_register_string('payoff', 'payoff');
	pll_register_string('about', 'about');
	pll_register_string('lets_discover', 'lets_discover');
	pll_register_string('discover', 'Discover');
	pll_register_string('news', 'News');
	pll_register_string('all_news', 'All News');
	pll_register_string('KIT', 'Keep in touch');
	pll_register_string('what_we_do', 'What we do');
	pll_register_string('services', 'Services');
	pll_register_string('primary_services', 'Primary Services');
	pll_register_string('secondary_services', 'Secondary Services');
	pll_register_string('market_movement', 'Market Movement');
	pll_register_string('inquiry', 'Please send us your inquiry!');
	pll_register_string('latest', 'Latest');
	pll_register_string('other', 'Other');
	pll_register_string('all_products', 'All products');
	pll_register_string('disc_collab', 'Let’s discover our collaborations');
	pll_register_string('disc_services', 'Let’s discover our services');
	pll_register_string('contact_us', 'Contact us');
	pll_register_string('request', 'Request');
	pll_register_string('other_products', 'Other products you might like');
	pll_register_string('news_insights', 'News and Insight');
	pll_register_string('contacts', 'Contacts');
	pll_register_string('find_us', 'Find us on the map');
	pll_register_string('terms', 'Terms & Conditions');
	pll_register_string('blank', 'blank');
});

wp_enqueue_style('hamburgers', get_template_directory_uri() . '/static/src/hamburgers/dist/hamburgers.css');
wp_enqueue_style('swiper_css', 'https://unpkg.com/swiper@8/swiper-bundle.min.css');

new StarterSite();
