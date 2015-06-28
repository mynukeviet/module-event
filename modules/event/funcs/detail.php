<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (hongoctrien@2mit.org)
 * @Copyright (C) 2015 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 21 Jun 2015 00:23:50 GMT
 */

if ( ! defined( 'NV_IS_MOD_EVENT' ) ) die( 'Stop!!!' );

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

$array_data = array();
$_sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE status=1 AND alias=' . $db->quote( $alias_url );
$array_data = $db->query( $_sql )->fetch();
if( empty( $array_data ) )
{
	Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
	die();
}

if( ! empty( $array_data['homeimgfile'] ) and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $array_data['homeimgfile'] ) )
{
	$array_data['homeimgfile'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $array_data['homeimgfile'];
}
else
{
	$array_data['homeimgfile'] = NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/no_image.gif';
}

$contents = nv_theme_event_detail( $array_data );

if( !empty( $array_data['keywords'] ) )
{
	$key_words = $array_data['keywords'];
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
