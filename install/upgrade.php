<?php
ignore_user_abort(1);

if ($config['version'] <= "8.3") {

    echo "DROP TABLE push_notification Table...  \t\t";
    $q = "DROP TABLE IF EXISTS `".$config['db']['pre']."push_notification`";
    @mysqli_query($mysqli, $q) OR error(mysqli_error($mysqli));
    echo "DROP TABLE push_notification success<br>";

    echo "CREATE TABLE push_notification Table...  \t\t";
    $q = "CREATE TABLE `".$config['db']['pre']."push_notification` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) DEFAULT NULL,
  `sender_name` varchar(255) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `owner_name` varchar(255) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_title` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `recd` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
    @mysqli_query($mysqli, $q) OR error(mysqli_error($mysqli));
    echo "CREATE TABLE push_notification success<br>";

    /*-----------------------------------------------------*/

    echo "DROP TABLE firebase_device_token Table...  \t\t";
    $q = "DROP TABLE IF EXISTS `".$config['db']['pre']."firebase_device_token`";
    @mysqli_query($mysqli, $q) OR error(mysqli_error($mysqli));
    echo "DROP TABLE firebase_device_token success<br>";

    echo "CREATE TABLE firebase_device_token Table...  \t\t";
    $q = "CREATE TABLE `".$config['db']['pre']."firebase_device_token` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `device_id` varchar(55) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `token` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
    @mysqli_query($mysqli, $q) OR error(mysqli_error($mysqli));
    echo "CREATE TABLE firebase_device_token success<br>";
}

if ($config['version'] < "9") {

    /*$sql = "INSERT INTO `".$config['db']['pre']."payments` (`payment_id`, `payment_install`, `payment_title`, `payment_folder`, `payment_desc`) VALUES (NULL, '0', 'Paytm', 'paytm', NULL);";
    mysqli_query($mysqli,$sql);*/

    $sql = "ALTER TABLE `".$config['db']['pre']."messages` ADD `post_id` INT(11) NULL DEFAULT NULL AFTER `message_type`";
    mysqli_query($mysqli,$sql);

    // add default values for new options
    update_option("blog_enable",'1');
    update_option("blog_banner",'1');
    update_option("show_blog_home",'1');
    update_option("blog_comment_enable",'1');
    update_option("blog_comment_approval",'2');
    update_option("blog_comment_user",'1');
    update_option("testimonials_enable",'1');
    update_option("show_testimonials_blog",'1');
    update_option("show_testimonials_home",'1');

    // create database tables
    $sql = "CREATE TABLE IF NOT EXISTS `".$config['db']['pre']."blog` (
          `id` int(10) NOT NULL AUTO_INCREMENT,
          `author` int(10) DEFAULT NULL,
          `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
          `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
          `image` varchar(255) DEFAULT NULL,
          `tags` text CHARACTER SET utf32 COLLATE utf32_unicode_ci,
          `status` enum('publish','pending') NOT NULL DEFAULT 'publish',
          `created_at` datetime DEFAULT NULL,
          `updated_at` datetime DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
    mysqli_query($mysqli,$sql);

    $sql = "CREATE TABLE IF NOT EXISTS `".$config['db']['pre']."blog_categories` (
          `id` int(10) NOT NULL AUTO_INCREMENT,
          `title` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
          `slug` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
          `position` int(10) NOT NULL DEFAULT '0',
          `active` enum('0','1') NOT NULL DEFAULT '1',
          PRIMARY KEY (`id`)
        ) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
    mysqli_query($mysqli,$sql);

    $sql = "CREATE TABLE IF NOT EXISTS `".$config['db']['pre']."blog_cat_relation` (
          `id` int(10) NOT NULL AUTO_INCREMENT,
          `blog_id` int(10) DEFAULT NULL,
          `category_id` int(10) DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
    mysqli_query($mysqli,$sql);

    $sql = "CREATE TABLE IF NOT EXISTS `".$config['db']['pre']."blog_comment` (
          `id` int(10) NOT NULL AUTO_INCREMENT,
          `blog_id` int(10) DEFAULT NULL,
          `user_id` int(10) DEFAULT NULL,
          `is_admin` enum('0','1') NOT NULL DEFAULT '0',
          `name` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
          `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
          `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
          `created_at` datetime DEFAULT NULL,
          `active` enum('0','1') NOT NULL DEFAULT '1',
          `parent` int(10) NOT NULL DEFAULT '0',
          PRIMARY KEY (`id`)
        ) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
    mysqli_query($mysqli,$sql);

    $sql = "CREATE TABLE IF NOT EXISTS `".$config['db']['pre']."testimonials` (
          `id` int(10) NOT NULL AUTO_INCREMENT,
          `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
          `designation` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
          `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
          `image` varchar(100) DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
    mysqli_query($mysqli,$sql);

    // insert demo data
    $sql = "INSERT INTO `".$config['db']['pre']."blog` (`author`, `title`, `description`, `image`, `tags`, `status`, `created_at`, `updated_at`) VALUES (1, 'First Blog', '<p>Consectetur adipisicing elitsed do eiusmod tempor incididunt ut labore et dolore magna aliqua Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commo do consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla paria tur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.<blockquote>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla paria tur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</blockquote><p>Elitsed do eiusmod tempor incididunt ut labore et dolore magna aliqua Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commo do consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla paria tur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim.</p><p></p></p>\n', NULL, 'travel fun, love', 'publish', '2020-01-15 23:05:15', '2020-01-17 19:12:18')";
    mysqli_query($mysqli,$sql);

    $sql = "INSERT INTO `".$config['db']['pre']."testimonials` (`name`, `designation`, `content`, `image`) VALUES ('Tony Stark', 'Social Marketing', 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla paria tur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', NULL)";
    mysqli_query($mysqli,$sql);

    // create required folders and files
    $srcfile = '../storage/products/default.png';
    $dstfile = '../storage/blog/';
    if(!is_dir($dstfile))
        mkdir ( $dstfile, 0755, true );
    @copy($srcfile, $dstfile.'default.png');

    $srcfile = '../storage/profile/default_user.png';
    $dstfile = '../storage/testimonials/';
    if(!is_dir($dstfile))
        mkdir ( $dstfile, 0755, true );
    @copy($srcfile, $dstfile.'default_user.png');
}

if ($config['version'] < "9.1") {
    update_option("non_active_msg",'1');
    update_option("non_active_allow",'0');
    update_option("cookie_link",'');
    update_option("cookie_consent",'1');
    update_option('socket_host', 'localhost');
    update_option('socket_port', '9300');
    update_option('listing_view', 'list');
    update_option('map_type', 'openstreet');
}

if ($config['version'] < "9.2") {
    update_option("quickchat_socket_on_off",'off');
}

if ($config['version'] < "9.3") {
    $sql = "CREATE TABLE IF NOT EXISTS `".$config['db']['pre']."mobile_numbers` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_id` INT(11) DEFAULT NULL,
        `mobile_number` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
        `verification_code` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
        `verified` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=Verified, 0=Not verified',
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
    mysqli_query($mysqli,$sql);

    // add mollie payment gateway
    $sql = "INSERT INTO `".$config['db']['pre']."payments` (`payment_id`, `payment_install`, `payment_title`, `payment_folder`, `payment_desc`) VALUES (NULL, '0', 'Mollie', 'mollie', 'You will be redirected to Mollie to complete payment.');";
    mysqli_query($mysqli,$sql);

    $sql = "CREATE TABLE `".$config['db']['pre']."plans` (
              `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
              `name` varchar(255) NOT NULL DEFAULT '',
              `badge` TEXT NULL DEFAULT NULL,
              `monthly_price` float DEFAULT NULL,
              `annual_price` float DEFAULT NULL,
              `lifetime_price` float DEFAULT NULL,
              `recommended` ENUM('yes','no') NOT NULL DEFAULT 'no',
              `settings` text NOT NULL,
              `taxes_ids` text,
              `status` tinyint(4) NOT NULL,
              `date` datetime NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    mysqli_query($mysqli,$sql);

    $sql = "CREATE TABLE `".$config['db']['pre']."taxes` (
              `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
              `internal_name` varchar(64) DEFAULT NULL,
              `name` varchar(64) DEFAULT NULL,
              `description` varchar(256) DEFAULT NULL,
              `value` DECIMAL(10,2) DEFAULT NULL,
              `value_type` enum('percentage','fixed') DEFAULT NULL,
              `type` enum('inclusive','exclusive') DEFAULT NULL,
              `billing_type` enum('personal','business','both') DEFAULT NULL,
              `countries` text COLLATE utf8mb4_unicode_ci,
              `datetime` datetime DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    mysqli_query($mysqli,$sql);

    $sql = "CREATE TABLE `".$config['db']['pre']."plan_options` (
              `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
              `title` varchar(255) DEFAULT NULL,
              `translation_lang` longtext COLLATE utf8mb4_unicode_ci,
              `translation_name` longtext COLLATE utf8mb4_unicode_ci,
              `position` int(10) DEFAULT NULL,
              `active` tinyint(1) NOT NULL DEFAULT '1'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    mysqli_query($mysqli,$sql);

    $sql = "CREATE TABLE `".$config['db']['pre']."user_options` (
              `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
              `user_id` int(11) DEFAULT NULL,
              `option_name` varchar(191) CHARACTER SET utf8mb4 DEFAULT NULL,
              `option_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    mysqli_query($mysqli,$sql);

    $sql = "ALTER TABLE `".$config['db']['pre']."adsense` ADD `size` text NULL DEFAULT NULL AFTER `slug`;";
    mysqli_query($mysqli,$sql);

    $sql = "INSERT INTO `".$config['db']['pre']."adsense` (`slug`, `size`, `provider_name`, `large_track_code`, `tablet_track_code`, `phone_track_code`, `status`) VALUES
    ('header_top', '728x90', 'Banner Ad', '', '', '', '0'),
    ('header_bottom', '728x90', 'Banner Ad', '', '', '', '0'),
    ('footer_top', '728x90', 'Banner Ad', '', '', '', '1'),
    ('home_page_below_search_section', '728x90', 'Banner Ad', '', '', '', '1'),
    ('home_page_below_category_section', '728x90', 'Banner Ad', '', '', '', '1'),
    ('home_page_below_featured_section', '728x90', 'Banner Ad', '', '', '', '1'),
    ('home_page_below_latest_section', '728x90', 'Banner Ad', '', '', '', '1'),
    ('home_page_below_search_section', '728x90', 'Banner Ad', '', '', '', '1'),
    ('home_page_left_sidebar', '120x600', 'Banner Ad', '', '', '', '1'),
    ('home_page_right_sidebar', '120x600', 'Banner Ad', '', '', '', '1'),
    ('search_page_sidebar', '125x125', 'Banner Ad', '', '', '', '1'),
    ('search_page_below_category', '728x90', 'Banner Ad', ' ', '', '', '1'),
    ('search_page_between_list_view', '728x90', 'Banner Ad', '', '', '', '1'),
    ('search_page_between_grid_view', '300x250', 'Banner Ad', '', '', '', '1'),
    ('detail_page_above_similar_section', '728x90', 'Banner Ad', '', '', '', '1'),
    ('detail_page_sidebar', '125x125', 'Banner Ad', '', '', '', '1'),
    ('profile_page_below_user_section', '728x90', 'Banner Ad', '', '', '', '1'),
    ('profile_page_left_sidebar', '120x600', 'Banner Ad', '', '', '', '1'),
    ('profile_page_right_sidebar', '120x600', 'Banner Ad', '', '', '', '1'),
    ('post_page_sidebar', '300x250', 'Banner Ad', '', '', '', '1'),
    ('inner_page_sidebar', '125x125', 'Banner Ad', '', '', '', '1'),
    ('inner_page_horizontal_banner', '728x90', 'Banner Ad', '', '', '', '1');";
    mysqli_query($mysqli,$sql);

    $sql = "ALTER TABLE `".$config['db']['pre']."custom_fields` ADD `icon` text NULL DEFAULT NULL AFTER `custom_order`;";
    mysqli_query($mysqli,$sql);

    $sql = "ALTER TABLE `".$config['db']['pre']."user` CHANGE `group_id` `group_id` VARCHAR(16) NOT NULL DEFAULT 'free';";
    mysqli_query($mysqli,$sql);

    $sql = "ALTER TABLE `".$config['db']['pre']."upgrades` CHANGE `sub_id` `sub_id` VARCHAR(16) NOT NULL DEFAULT '0';";
    mysqli_query($mysqli,$sql);

    $sql = "ALTER TABLE `".$config['db']['pre']."upgrades` ADD `pay_mode` ENUM('one_time','recurring') NOT NULL DEFAULT 'one_time' AFTER `user_id`;";
    mysqli_query($mysqli,$sql);

    $sql = "ALTER TABLE `".$config['db']['pre']."transaction` ADD `payment_id` VARCHAR(64) NULL DEFAULT NULL AFTER `status`;";
    mysqli_query($mysqli,$sql);

    $sql = "ALTER TABLE `".$config['db']['pre']."transaction` ADD `frequency` ENUM('MONTHLY','YEARLY','LIFETIME') NULL DEFAULT NULL AFTER `transaction_method`;";
    mysqli_query($mysqli,$sql);

    $sql = "ALTER TABLE `".$config['db']['pre']."transaction` ADD `billing` TEXT NULL DEFAULT NULL AFTER `frequency`;";
    mysqli_query($mysqli,$sql);

    $sql = "ALTER TABLE `".$config['db']['pre']."transaction` ADD `taxes_ids` TEXT NULL DEFAULT NULL AFTER `billing`;";
    mysqli_query($mysqli,$sql);

    $sql = "ALTER TABLE `".$config['db']['pre']."transaction` ADD `base_amount` DOUBLE(9,2) NULL DEFAULT NULL AFTER `amount`;";
    mysqli_query($mysqli,$sql);

    $sql = "ALTER TABLE `".$config['db']['pre']."transaction` ADD `currency_code` VARCHAR(3) NULL DEFAULT NULL AFTER `base_amount`;";
    mysqli_query($mysqli,$sql);


    $free_plan = json_encode(array(
        'id' => 'free',
        'name' => 'Free',
        'badge' => '',
        'settings' => array(
            'ad_limit' => 5,
            'ad_duration' => 30,
            'featured_project_fee' => 10,
            'featured_duration' => 30,
            'urgent_project_fee' => 10,
            'urgent_duration' => 30,
            'highlight_project_fee' => 10,
            'highlight_duration' => 30,
            'top_search_result' => 'no',
            'show_on_home' => 'no',
            'show_in_home_search' => 'no'
        ),
        'status' => 0
    ));
    update_option('free_membership_plan', $free_plan);

    $trial_plan = json_encode(array(
        'id' => 'trial',
        'name' => 'Trial',
        'badge' => '',
        'days' => 7,
        'settings' => array(
            'ad_limit' => 5,
            'ad_duration' => 30,
            'featured_project_fee' => 10,
            'featured_duration' => 30,
            'urgent_project_fee' => 10,
            'urgent_duration' => 30,
            'highlight_project_fee' => 10,
            'highlight_duration' => 30,
            'top_search_result' => 'yes',
            'show_on_home' => 'yes',
            'show_in_home_search' => 'yes'
        ),
        'status' => 0
    ));
    update_option('trial_membership_plan', $trial_plan);

    update_option("paypal_payment_mode",'one_time');
    update_option("stripe_payment_mode",'one_time');

    update_option('show_membershipplan_home', '1');
    update_option('quickad_custom_fields_per_service', '0');
}

if (version_compare($config['version'] , "9.8",'<')) {

    update_option('non_login_sendemail_allow', '1');
    update_option('non_login_phone_show', '1');
    update_option('qbm_enable_approval', '');

    $sql = "CREATE TABLE IF NOT EXISTS `".$config['db']['pre']."mobile_numbers` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `user_id` int(11) DEFAULT NULL,
      `mobile_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
      `verification_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
      `verified` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=Verified, 0=Not verified',
      PRIMARY KEY (`id`),
      UNIQUE KEY `user_id` (`user_id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    mysqli_query($mysqli,$sql);

    $sql = "ALTER TABLE `".$config['db']['pre']."mobile_numbers` CHANGE `mobile_number` `mobile_number` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;";
    mysqli_query($mysqli,$sql);
}

if (version_compare($config['version'] , "10",'<')) {
    $sql = "INSERT INTO `".$config['db']['pre']."payments` (`payment_id`, `payment_install`, `payment_title`, `payment_folder`, `payment_desc`) VALUES 
    (NULL, '0', 'iyzico', 'iyzico', NULL),
    (NULL, '0', 'Midtrans', 'midtrans', NULL),
    (NULL, '0', 'Paytabs', 'paytabs', NULL),
    (NULL, '0', 'Telr', 'telr', NULL),
    (NULL, '0', 'Razorpay', 'razorpay', NULL)";
    mysqli_query($mysqli,$sql);
}
if (version_compare($config['version'] , "10.2",'<')) {
    $sql = "INSERT INTO `".$config['db']['pre']."payments` (`payment_id`, `payment_install`, `payment_title`, `payment_folder`, `payment_desc`) VALUES 
    (NULL, '0', 'Flutterwave', 'flutterwave', NULL)";
    mysqli_query($mysqli,$sql);
}
?>