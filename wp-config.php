<?php
# Database Configuration
define( 'DB_NAME', 'wp_waxtronaut' );
define( 'DB_USER', 'waxtronaut' );
define( 'DB_PASSWORD', '1cPADtVVVBZJppH2RlhS' );
define( 'DB_HOST', '127.0.0.1' );
define( 'DB_HOST_SLAVE', '127.0.0.1' );
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', 'utf8_unicode_ci');
$table_prefix = 'w4x_';

# Security Salts, Keys, Etc
define('AUTH_KEY',         '>hL2I]^8leHzr](hD=}M< (Lc_$J|l_-Bt,kJ&C9B4>aZ,}yy2f-:ltaxK-y+{8x');
define('SECURE_AUTH_KEY',  '+3?-@<+$,HU9HPu?D^U%O/~h*]n:elE/r9X0I|2w*r+A9nkJ*{IEFyM6Wvn(SQpI');
define('LOGGED_IN_KEY',    '|(H% ?M}p0NSsu,wG1|y}%;i2&L,N9-SXdfzi#qA.V+o #56gPwGcS`LS;JWg4xa');
define('NONCE_KEY',        '9_f.ZlF0|Q9?F=%{{<jZ|{F/_!A=:<@U5NPblD)S<mDsu:K3S@{GuOq,,,*1Qw*V');
define('AUTH_SALT',        'gzY^65<sJ{Y@Lnk,]9;R`}>%yw3;GzO9wWVy[MG_Y;:QUrKZ%(9fvc(&Al+56u6t');
define('SECURE_AUTH_SALT', 'K)jo?tC:Tx%|jnR5g0X6;8>A]yhtZBJp0OQ(w{9,<fI=YQuX/R9~pjl:1{=8[TcD');
define('LOGGED_IN_SALT',   '>!P1.TcTRv9Tqb{jvV-b?1}f{mqYbO(sdHPzf=>sriba+-lP]Tg]q)ZW?;iW|D3C');
define('NONCE_SALT',       '_wx3j($,(6x_ u.@ffM|%sk8_~^$+`hV+o8{.b<v&h+; yb]OO&lRTR|=J_n%a5T');


# Localized Language Stuff

define( 'WP_CACHE', TRUE );

define( 'WP_AUTO_UPDATE_CORE', false );

define( 'PWP_NAME', 'waxtronaut' );

define( 'FS_METHOD', 'direct' );

define( 'FS_CHMOD_DIR', 0775 );

define( 'FS_CHMOD_FILE', 0664 );

define( 'PWP_ROOT_DIR', '/nas/wp' );

define( 'WPE_APIKEY', '3a226bb29d5093a4633971f4ec5966b95cf6cab4' );

define( 'WPE_CLUSTER_ID', '100337' );

define( 'WPE_CLUSTER_TYPE', 'pod' );

define( 'WPE_ISP', true );

define( 'WPE_BPOD', false );

define( 'WPE_RO_FILESYSTEM', false );

define( 'WPE_LARGEFS_BUCKET', 'largefs.wpengine' );

define( 'WPE_SFTP_PORT', 2222 );

define( 'WPE_LBMASTER_IP', '' );

define( 'WPE_CDN_DISABLE_ALLOWED', true );

define( 'DISALLOW_FILE_MODS', FALSE );

define( 'DISALLOW_FILE_EDIT', FALSE );

define( 'DISABLE_WP_CRON', false );

define( 'WPE_FORCE_SSL_LOGIN', false );

define( 'FORCE_SSL_LOGIN', false );

/*SSLSTART*/ if ( isset($_SERVER['HTTP_X_WPE_SSL']) && $_SERVER['HTTP_X_WPE_SSL'] ) $_SERVER['HTTPS'] = 'on'; /*SSLEND*/

define( 'WPE_EXTERNAL_URL', false );

define( 'WP_POST_REVISIONS', FALSE );

define( 'WPE_WHITELABEL', 'wpengine' );

define( 'WP_TURN_OFF_ADMIN_BAR', false );

define( 'WPE_BETA_TESTER', false );

umask(0002);

$wpe_cdn_uris=array ( );

$wpe_no_cdn_uris=array ( );

$wpe_content_regexs=array ( );

$wpe_all_domains=array ( 0 => 'waxtronaut.com', 1 => 'waxtronaut.wpengine.com', 2 => 'www.waxtronaut.com', );

$wpe_varnish_servers=array ( 0 => 'pod-100337', );

$wpe_special_ips=array ( 0 => '104.196.146.230', );

$wpe_ec_servers=array ( );

$wpe_largefs=array ( );

$wpe_netdna_domains=array ( 0 =>  array ( 'match' => 'www.waxtronaut.com', 'zone' => '1fsy5a30feql22k9673d0yfa', 'enabled' => true, ), 1 =>  array ( 'match' => 'waxtronaut.com', 'zone' => '76lgdu5a9ckzd403q4f24ec7', 'enabled' => true, ), );

$wpe_netdna_domains_secure=array ( );

$wpe_netdna_push_domains=array ( );

$wpe_domain_mappings=array ( );

$memcached_servers=array ( );


# WP Engine ID


# WP Engine Settings






define('WP_DEBUG', false);

# That's It. Pencils down
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
require_once(ABSPATH . 'wp-settings.php');

$_wpe_preamble_path = null; if(false){}
