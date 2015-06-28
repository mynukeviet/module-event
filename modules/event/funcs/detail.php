<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (hongoctrien@2mit.org)
 * @Copyright (C) 2015 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 21 Jun 2015 00:23:50 GMT
 */

if ( ! defined( 'NV_IS_MOD_EVENT' ) ) die( 'Stop!!!' );

$key_words = $module_info['keywords'];

$array_data = array();
$_sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE status=1 AND alias=' . $db->quote( $alias_url );
$array_data = $db->query( $_sql )->fetch();
if( empty( $array_data ) )
{
	Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
	die();
}

if( nv_user_in_groups( $global_array_event_cat[$catid]['groups_view'] ) and nv_user_in_groups( $array_data['groups_view'] ) )
{
	if( ! empty( $array_data['homeimgfile'] ) and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $array_data['homeimgfile'] ) )
	{
		$array_data['homeimgfile'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $array_data['homeimgfile'];
	}
	else
	{
		$array_data['homeimgfile'] = NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/no_image.gif';
	}

	//metatag image facebook
	$meta_property['og:image'] = NV_MY_DOMAIN . $array_data['homeimgfile'];

	$contents = nv_theme_event_detail( $array_data );

	$page_title = $array_data['title'];
	if( !empty( $array_data['keywords'] ) )
	{
		$key_words = $array_data['keywords'];
	}


}
else
{
	$nv_redirect = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true );
	redict_link( $lang_module['detail_no_permission'], $lang_module['redirect_to_back'], $nv_redirect );
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';