<?php
# Database Configuration
define( 'DB_NAME', 'wp_dawgstar' );
define( 'DB_USER', 'dawgstar' );
define( 'DB_PASSWORD', 'u4VjBb9jI1xA16Fxtaft' );
define( 'DB_HOST', '127.0.0.1' );
define( 'DB_HOST_SLAVE', '127.0.0.1' );
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', 'utf8_unicode_ci');
$table_prefix = 'wp_';

# Security Salts, Keys, Etc
define('AUTH_KEY',         'de?n;xtXug+w|0L4:5=d:9gV+$-,.9?EwhNr:GvBn>hItSeVkl}Ii)-_R2LuW+K/');
define('SECURE_AUTH_KEY',  '9A$j3YTr5:_JauN$|4#(|YI^#qFa+a;uU0Ke/8k[hKcgt[84`pC(TSc,W9COu|@O');
define('LOGGED_IN_KEY',    'nV>~4Ei;?^Z2oCV2L<p_1YCA@6$NBWc9 ~QW1@n^]4WDnEnBdt+==Zt3,Np=Y4J(');
define('NONCE_KEY',        'eq]U{i{z0!pHg{m&PZc --cefM]?eY6uI`=Vy=-^ouFjX~j>)=VfByC 09R5+`?)');
define('AUTH_SALT',        'd$(+2KLyyNImgu:2J-##&~[+kZ~.x+Z>BdJUe`bva|2DfS5_v%c>8y!?FICNX97@');
define('SECURE_AUTH_SALT', '}h!$Oi5Jg?eQ{DQm^|Y|FnHwF|j}+|E{!Atpdfz5#d.;D&,U.7,/D+@Ze|L.-<kW');
define('LOGGED_IN_SALT',   ',sHIHH}:T6=WFcj!d6$i=6;r/{cL,5E_72md>zZ5U[ n=PxGom]LE.?:4~09}9c;');
define('NONCE_SALT',       '8-#C|td|fW?2h5}zq},bFhll.gb69kV|^I/T%)Fl|u+d{x(m-66eO;]>&h*r5A|g');


# Localized Language Stuff

define( 'WP_CACHE', TRUE );

define( 'WP_AUTO_UPDATE_CORE', false );

define( 'PWP_NAME', 'dawgstar' );

define( 'FS_METHOD', 'direct' );

define( 'FS_CHMOD_DIR', 0775 );

define( 'FS_CHMOD_FILE', 0664 );

define( 'PWP_ROOT_DIR', '/nas/wp' );

define( 'WPE_APIKEY', 'cf0ded108d5846973251d2710f9807882294044d' );

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

$wpe_all_domains=array ( 0 => 'dawgstar.wpengine.com', );

$wpe_varnish_servers=array ( 0 => 'pod-100337', );

$wpe_special_ips=array ( 0 => '104.196.146.230', );

$wpe_netdna_domains=array ( );

$wpe_netdna_domains_secure=array ( );

$wpe_netdna_push_domains=array ( );

$wpe_domain_mappings=array ( );

$memcached_servers=array ( );
define('WPLANG','');

# WP Engine ID


# WP Engine Settings






# That's It. Pencils down
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
require_once(ABSPATH . 'wp-settings.php');

$_wpe_preamble_path = null; if(false){}
