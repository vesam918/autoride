<?php
/**
 * Show messages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/notices/notice.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! $notices ) {
	return;
}
	
$content=null;
foreach ($notices as $notice)
{
    if(!is_null($content)) $content.='<br/>';
    $content.=$notice['notice'];
}
        
$shortcode=
'
    [vc_autoride_theme_notice icon="information" header_text="'.__('Information','autoride').'" subheader_text="'.$content.'"]
';

echo Autoride_ThemePlugin::doShortcode('ARCAutorideCore',$shortcode);