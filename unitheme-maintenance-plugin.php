<?php
/*
Plugin Name: Uni-theme Maintenance Mode
Plugin URI: http://uni-theme.net/
Description: (Eng) Adds a "Maintenance Mode" page to your site (sending a "503 Service Unavailable" status). Includes MailChimp mail subscriptions. / (Ukr) Плагін додає сторінку-заглушку типу "На реконструкції" (надсилається статус "503 Сервіс недоступний"). Включає форму підписки на ел. пошту з допомогою MailChimp.
Author: Vitaliy Kiyko
Author URI: http://uni-theme.net/
Version: 1.0.2
Tags: maintenance, maintenance mode
License: GPL2
*/

if ( ! defined( 'UNI_MAINTENANCE_WP_PLUGIN_DIR' ) )
    define( 'UNI_MAINTENANCE_WP_PLUGIN_DIR', WP_CONTENT_URL. '/plugins/unitheme-maintenance-mode' );
if( !load_plugin_textdomain('unitheme-m-m','/wp-content/languages/') )
    load_plugin_textdomain('unitheme-m-m','/wp-content/plugins/unitheme-maintenance-mode/languages/');

class uni_maintenance {

    protected $_exception_urls;
    protected $_options_slug;
    protected $_shortname;
    protected $_options_value;

	function __construct()
	{
        $this->_exception_urls = array( 'wp-login.php', '/plugins/', 'wp-admin/', 'upgrade.php', 'trackback/', 'feed/' );
        $this->_options_slug = 'unitheme-maintenance-options-page';
        $this->_shortname = 'unitheme_maintenance_mode';
		add_action( 'admin_menu', array( &$this, 'admin_init' ) );
		add_action( 'init', array( &$this, 'maintenance_active' ) );

        $this->_options_value = array (

        array( "name" => __("Uni-theme Maintenance Mode: опції", "unitheme-m-m"),
	        "type" => "title" ),

        array( "name" => __("Головні налаштування", "unitheme-m-m"),
	        "type" => "section" ),
        array( "type" => "open" ),

        array( "name" => __("Увімкнути Maintenance Mode?", "unitheme-m-m"),
	        "desc" => __("З увімкненим Maintenance Mode сайт буде закрито для звичайних відвідувачів. В той час як Адміністратор сайту зможе й надалі перегядати сайт.", "unitheme-m-m"),
	        "id" => $this->_shortname."_enabled",
	        "type" => "select",
            "options" => array( "no", "yes" ),
	        "std" => "no" ),

        array( "name" => __("Вибрати тему", "unitheme-m-m"),
	        "desc" => __("Ми передбачили кілька тем оформлення сторінки. Ви можете вибрати будь-яку.", "unitheme-m-m"),
	        "id" => $this->_shortname."_style",
	        "type" => "select",
            "options" => array( "simple-beige", "simple-green" ),
	        "std" => "simple-beige" ),

        array( "name" => __("Змінити лого", "unitheme-m-m"),
	        "desc" => __("По замовчуванні замість логотипу відображається одне із стандартних зображень теми. Ви можете замінити це зображення на своє лого. Вкажіть повну URL-адресу до файлу лого.", "unitheme-m-m"),
	        "id" => $this->_shortname."_logo",
	        "type" => "text",
	        "std" => "" ),

        array( "name" => __("Заголовок", "unitheme-m-m"),
	        "desc" => __("Вкажіть текст заголовка.", "unitheme-m-m"),
	        "id" => $this->_shortname."_title_text",
	        "type" => "text",
	        "std" => __("Сайт на технічному обслуговуванні", "unitheme-m-m") ),

        array( "name" => __("Опис", "unitheme-m-m"),
	        "desc" => __("Вкажіть текст опису. <strong>Дозволені html-теги: &lt;a href=&quot;&quot; title=&quot;&quot;&gt;, &lt;strong&gt;, &lt;em&gt;</strong>.", "unitheme-m-m"),
	        "id" => $this->_shortname."_general_text",
	        "type" => "textarea",
	        "std" => __("Просимо вибачення за можливі незручності. Це планова технічна перевірка. Ми скоро повернемось!", "unitheme-m-m") ),

        array( "name" => __("Футер", "unitheme-m-m"),
	        "desc" => __("Вкажіть текст для футера. <strong>Дозволені html-теги: &lt;a href=&quot;&quot; title=&quot;&quot;&gt;, &lt;strong&gt;, &lt;em&gt;</strong>.", "unitheme-m-m"),
	        "id" => $this->_shortname."_footer_text",
	        "type" => "textarea",
	        "std" => __("Всі права застережено 2011 &copy; <a href=\"http://example.com\">www.example.com</a>", "unitheme-m-m") ),

        array( "type" => "close" ),

        array( "name" => __("Соціальні сервіси", "unitheme-m-m"),
	        "type" => "section" ),
        array( "type" => "open" ),

        array( "name" => __("RSS", "unitheme-m-m"),
	        "desc" => __("Вкажіть URL-адресу підписки на RSS. Відповідна іконка на сайті з'явиться автоматично.", "unitheme-m-m"),
	        "id" => $this->_shortname."_rss_link",
	        "type" => "text",
	        "std" => "" ),

        array( "name" => __("Facebook", "unitheme-m-m"),
	        "desc" => __("Вкажіть URL-адресу свого FB екаунта. Відповідна іконка на сайті з'явиться автоматично.", "unitheme-m-m"),
	        "id" => $this->_shortname."_fb_link",
	        "type" => "text",
	        "std" => "" ),

        array( "name" => __("Twitter", "unitheme-m-m"),
	        "desc" => __("Вкажіть URL-адресу свого Twitter екаунта. Відповідна іконка на сайті з'явиться автоматично.", "unitheme-m-m"),
	        "id" => $this->_shortname."_twi_link",
	        "type" => "text",
	        "std" => "" ),

        array( "name" => __("Vkontakte", "unitheme-m-m"),
	        "desc" => __("Вкажіть URL-адресу свого Vkontakte екаунта. Відповідна іконка на сайті з'явиться автоматично.", "unitheme-m-m"),
	        "id" => $this->_shortname."_vk_link",
	        "type" => "text",
	        "std" => "" ),

        array( "name" => __("Picasa", "unitheme-m-m"),
	        "desc" => __("Вкажіть URL-адресу свого Picasa екаунта. Відповідна іконка на сайті з'явиться автоматично.", "unitheme-m-m"),
	        "id" => $this->_shortname."_picasa_link",
	        "type" => "text",
	        "std" => "" ),

        array( "name" => __("Orkut", "unitheme-m-m"),
	        "desc" => __("Вкажіть URL-адресу свого Orkut екаунта. Відповідна іконка на сайті з'явиться автоматично.", "unitheme-m-m"),
	        "id" => $this->_shortname."_orkut_link",
	        "type" => "text",
	        "std" => "" ),

        array( "name" => __("LinkedIn", "unitheme-m-m"),
	        "desc" => __("Вкажіть URL-адресу свого LinkedIn екаунта. Відповідна іконка на сайті з'явиться автоматично.", "unitheme-m-m"),
	        "id" => $this->_shortname."_li_link",
	        "type" => "text",
	        "std" => "" ),

        array( "name" => __("Blogger", "unitheme-m-m"),
	        "desc" => __("Вкажіть URL-адресу свого Blogger екаунта. Відповідна іконка на сайті з'явиться автоматично.", "unitheme-m-m"),
	        "id" => $this->_shortname."_blogger_link",
	        "type" => "text",
	        "std" => "" ),

        array( "name" => __("Flickr", "unitheme-m-m"),
	        "desc" => __("Вкажіть URL-адресу свого Flickr екаунта. Відповідна іконка на сайті з'явиться автоматично.", "unitheme-m-m"),
	        "id" => $this->_shortname."_flickr_link",
	        "type" => "text",
	        "std" => "" ),

        array( "type" => "close" ),

        array( "name" => __("Налаштування підписки MailChimp", "unitheme-m-m"),
	        "type" => "section" ),
        array( "type" => "open" ),

        array( "name" => __("Вкажіть свій MailChimp API Key", "unitheme-m-m"),
	        "desc" => __("Відвідувачі матимуть змогу підписатись по електронній пошті через сервіс <a href=\"http://mailchimp.com/\">MailChimp</a>. Вкажіть тут свій MailChimp API Key і форма підписки з'явиться автоматично. Ключ ви зможете знайти у своєму MailChimp екаунті, у розділі Налаштування.", "unitheme-m-m"),
	        "id" => $this->_shortname."_mailchimp_api",
	        "type" => "text",
	        "std" => "" ),

        array( "name" => __("Вкажіть Unique Id вибраного списку розсилок", "unitheme-m-m"),
	        "desc" => __("Unique Id списку розсилок ви зможете знайти у налаштуваннях списку. Цей унікальний номер вказує до якого саме списку будуть додані підписані користувачі.", "unitheme-m-m"),
	        "id" => $this->_shortname."_mailchimp_list",
	        "type" => "text",
	        "std" => "" ),

        array( "type" => "close" )

        );
	}

    function admin_init()
	{
		add_options_page( 'Uni-theme Maintenance Mode Plugin Options', 'Uni-theme Maintenance Plugin', 'manage_options', $this->_options_slug, array( &$this, 'build_options_page' ) );
        wp_enqueue_style( "unitheme-m-mfunctions", UNI_MAINTENANCE_WP_PLUGIN_DIR."/unitheme-m-m-functions.css", false, "1.0", "all" );
        wp_enqueue_script( "unitheme-m-m-js", UNI_MAINTENANCE_WP_PLUGIN_DIR."/js/unitheme-m-m-js.js", false, "1.0" );
	}

    function build_options_page()
    {

        if ( !current_user_can('manage_options') )
        {
        wp_die( __('Ви не маєте достатніх прав для перегляду цієї сторінки.') );
        }

        if ( isset($_REQUEST['saved']) ) { echo '<div id="message" class="updated fade"><p><strong>' . __("Uni-theme Maintenance Mode: налаштування збережено.", "unitheme-m-m") . '</strong></p></div>'; }

        if ( isset($_POST['save_settings']) ) $this->_unitheme_maintenance_save($_POST);

?>
<div class="wrap unimm_wrap">
<h2><?php _e("Uni-theme Maintenance Mode: Налаштування плагіна", "unitheme-m-m"); ?></h2>

<div class="unimm_opts">
<form method="post">

<?php foreach ($this->_options_value as $value) {
switch ( $value['type'] ) {
case "title":
?>
<div class="unimm-logo"></div>

<?php
break;

case "open":

?>

    <div>

<?php
break;

case "close":
?>

    </div>

<?php break;

case "section":
?>
    <h3><?php echo $value['name']; ?></h3>
<?php
break;

case 'text':

?>

<div class="unimm_input unimm_text">
	<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
 	<input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php echo stripslashes(htmlspecialchars(get_option( $value['id'], $value['std'] ),ENT_QUOTES)); ?>" />
 <small><?php echo $value['desc']; ?></small><div class="clearfix"></div>

 </div>
<?php
break;

case 'textarea':
?>

<div class="unimm_input unimm_textarea">
	<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
 	<textarea name="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" cols="" rows=""><?php echo stripslashes(htmlspecialchars(get_option( $value['id'], $value['std'] ),ENT_QUOTES)); ?></textarea>
 <small><?php echo $value['desc']; ?></small><div class="clearfix"></div>

 </div>

<?php
break;

case 'select':
?>

<div class="unimm_input unimm_select">
	<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>

<select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
<?php foreach ($value['options'] as $option) { ?>
		<option <?php if (get_option( $value['id'] ) == $option) { echo 'selected="selected"'; } ?>><?php echo $option; ?></option><?php } ?>
</select>

	<small><?php echo $value['desc']; ?></small><div class="clearfix"></div>
</div>
<?php
break;

case "checkbox":
?>

<div class="unimm_input unimm_checkbox">
	<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>

<?php if(get_option($value['id'])){ $checked = "checked=\"checked\""; }else{ $checked = "";} ?>
<input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />


	<small><?php echo $value['desc']; ?></small><div class="clearfix"></div>
 </div>
<?php break;

}
}
?>

<div style="clear:both;"></div>
<input class="unimm-plugin-button" name="save_settings" type="submit" value="<?php _e("Зберегти зміни", "unitheme-m-m"); ?>" />
</form>
</div>
</div>
<?php
    }

    protected function _unitheme_maintenance_save($form_data)
    {
		foreach ($form_data as $k => $v) {
		  if ( !($k == 'save_settings') ) {
		    update_option( $k, stripslashes($v) );
          }
        }
    }

    public function maintenance_active()
    {
      if ( get_option('unitheme_maintenance_mode_enabled') == 'yes' ) {
        if ( !$this->check_user_capability() && !$this->is_url_excluded() )
        {
            nocache_headers();
            header("HTTP/1.0 503 Service Unavailable");
            include('maintenance-page.php');
            exit();
        }
      }
    }

    public function check_user_capability()
    {
        if ( is_super_admin() || current_user_can('manage_options') ) return true;

        return false;
    }

    public function is_url_excluded()
    {
        foreach ( $this->_exception_urls as $url ){
            if ( strstr( $_SERVER['PHP_SELF'], $url) ) return true;
        }
        if ( strstr($_SERVER['QUERY_STRING'], 'feed=') ) return true;
        return false;
    }

}

add_action( 'init', 'uni_plugin_init', 5 );
function uni_plugin_init()
{
    global $unitheme_maintenance;
    $unitheme_maintenance = new uni_maintenance();
}

?>