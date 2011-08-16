<?php
    global $unitheme_maintenance;
?>
<!DOCTYPE html>
<html lang="uk">
<head>
<title><?php bloginfo('name'); ?></title>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<meta name="description" content="<?php bloginfo('description'); ?>" />

<link href='http://fonts.googleapis.com/css?family=Lobster&subset=cyrillic,latin' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=PT+Sans:regular,italic,bold&subset=cyrillic,latin' rel='stylesheet' type='text/css'>
<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<link rel="stylesheet" href="<?php echo UNI_MAINTENANCE_WP_PLUGIN_DIR . '/'; ?><?php if (get_option('unitheme_maintenance_mode_style')) { echo get_option('unitheme_maintenance_mode_style'); } else { echo 'simple-beige'; } ?>.css" media="screen" />

<script type="text/javascript" src="<?php echo UNI_MAINTENANCE_WP_PLUGIN_DIR . '/'; ?>js/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="<?php echo UNI_MAINTENANCE_WP_PLUGIN_DIR . '/'; ?>js/script-js.js"></script>
</head>
<body>
<div id="wrapper">
<div class="inner">
    <div class="glow-top">
    <div class="glow-bottom">

	<header>

		<div class="socials">
        <?php if (get_option('unitheme_maintenance_mode_rss_link')) { ?>
            <a class="rss-link" href="<?php echo get_option('unitheme_maintenance_mode_rss_link'); ?>" title="RSS"></a>
        <?php } ?>
        <?php if (get_option('unitheme_maintenance_mode_fb_link')) { ?>
            <a class="fb-link" href="<?php echo get_option('unitheme_maintenance_mode_fb_link'); ?>" title="Facebook"></a>
        <?php } ?>
        <?php if (get_option('unitheme_maintenance_mode_twi_link')) { ?>
            <a class="twi-link" href="<?php echo get_option('unitheme_maintenance_mode_twi_link'); ?>" title="Twitter"></a>
        <?php } ?>
        <?php if (get_option('unitheme_maintenance_mode_vk_link')) { ?>
            <a class="vk-link" href="<?php echo get_option('unitheme_maintenance_mode_vk_link'); ?>" title="Vkontakte"></a>
        <?php } ?>
        <?php if (get_option('unitheme_maintenance_mode_picasa_link')) { ?>
            <a class="picasa-link" href="<?php echo get_option('unitheme_maintenance_mode_picasa_link'); ?>" title="Picasa"></a>
        <?php } ?>
        <?php if (get_option('unitheme_maintenance_mode_orkut_link')) { ?>
            <a class="orkut-link" href="<?php echo get_option('unitheme_maintenance_mode_orkut_link'); ?>" title="Orkut"></a>
        <?php } ?>
        <?php if (get_option('unitheme_maintenance_mode_li_link')) { ?>
            <a class="li-link" href="<?php echo get_option('unitheme_maintenance_mode_li_link'); ?>" title="LinkedIn"></a>
        <?php } ?>
        <?php if (get_option('unitheme_maintenance_mode_blogger_link')) { ?>
            <a class="blogger-link" href="<?php echo get_option('unitheme_maintenance_mode_blogger_link'); ?>" title="Blogger"></a>
        <?php } ?>
        <?php if (get_option('unitheme_maintenance_mode_flickr_link')) { ?>
            <a class="flickr-link" href="<?php echo get_option('unitheme_maintenance_mode_flickr_link'); ?>" title="Flickr"></a>
        <?php } ?>
        </div>
        <div class="clear"></div>

	</header>

	<section class="content">

		<article>

			<h1><?php echo wp_kses(get_option('unitheme_maintenance_mode_title_text'), array() ); ?></h1>

			<h4><?php echo wp_kses(get_option('unitheme_maintenance_mode_general_text'), array('a' => array('href' => array(),'title' => array()),'strong' => array(), 'em' => array()) ); ?></h4>

            <?php if (get_option('unitheme_maintenance_mode_mailchimp_api')) { ?>
            <div class="mail-chimp">
                <form name="EmailSubscription" id="signup" action="<?php $_SERVER['PHP_SELF']; ?>" method="get">

			    <span id="response">
				<?php

				function storeAddress(){
					if(!$_GET['email_input']){ return __("Будь-ласка, вкажіть правильну адресу", "unitheme-m-m"); }

					if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$/i", $_GET['email_input'])) {
						return __("Адреса е-пошти не правильна", "unitheme-m-m");
					}

					require_once('MCAPI.class.php');

	                $mc_api = get_option('unitheme_maintenance_mode_mailchimp_api');
	                $api = new MCAPI('' . $mc_api . '');

                    $mc_list = get_option('unitheme_maintenance_mode_mailchimp_list');
	                $list_id = "$mc_list";

					if($api->listSubscribe($list_id, $_GET['email_input'], '') === true) {
						return __("Успішно! Перевірте вашу скриньку, щоб підтвердити підписку.", "unitheme-m-m");
					}else{
						return __("Помилка: ", "unitheme-m-m") . $api->errorMessage;
					}

				}

				if($_GET['email_submit']){ echo storeAddress(); }

				?>
			    </span>

                    <input class="email-input" name="email_input" type="text" tabindex="1">
                    <input class="email-submit" name="email_submit" type="submit" value="<?php _e("Підписатись", "unitheme-m-m"); ?>" tabindex="2">
                </form>
                <small class="email-note"><?php _e("*Ваша адреса ел. скриньки ніколи не буде використана для розсилки спаму і/або передана третім особам.", "unitheme-m-m"); ?></small>
            </div>
            <?php } ?>

        </article>

    </section>

    <a class="login" href="<?php echo wp_login_url(); ?>" title="Admin"><span>&rsaquo;</span><?php _e(" Admin", "unitheme-m-m"); ?></a>
    <div class="clear"></div>

    </div>
    </div>
</div>
</div>


	<footer>
        <?php if (get_option('unitheme_maintenance_mode_logo') == "") { ?>
        <img src="<?php echo UNI_MAINTENANCE_WP_PLUGIN_DIR . '/'; ?>images/simple/logo.png" title="<?php bloginfo('name'); ?>" width="104" height="127">
        <?php } else { ?>
        <img src="<?php echo get_option('unitheme_maintenance_mode_logo'); ?>" title="<?php bloginfo('name'); ?>">
        <?php } ?>
		<p><?php echo wp_kses(get_option('unitheme_maintenance_mode_footer_text'), array('a' => array('href' => array(),'title' => array()),'strong' => array(), 'em' => array()) ); ?></p>
	</footer>

</body>
</html>