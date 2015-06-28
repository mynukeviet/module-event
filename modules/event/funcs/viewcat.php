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

$contents = '';
$cache_file = '';
if( ! defined( 'NV_IS_MODADMIN' ) and $page < 5 )
{
	$cache_file = NV_LANG_DATA . '_' . $module_info['template'] . '_' . $op . '_' . $page . '_' . NV_CACHE_PREFIX . '.cache';
	if( ( $cache = nv_get_cache( $module_name, $cache_file ) ) != false )
	{
		$contents = $cache;
	}
}

if( nv_user_in_groups( $global_array_event_cat[$catid]['groups_view'] ) )
{
	if( empty( $contents ) )
	{
		if( !empty( $global_array_event_cat[$catid]['keywords'] ) )
		{
			$key_words = $global_array_event_cat[$catid]['keywords'];
		}

		$array_data = array();
		$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_event_cat[$catid]['alias'];

		$db->sqlreset()
		  ->select( 'COUNT(*)' )
		  ->from( NV_PREFIXLANG . '_' . $module_data )
		  ->where( 'status=1 AND catid=' . $catid );

		$all_page = $db->query( $db->sql() )->fetchColumn();

		$db->select( '*' )
		  ->order( 'id DESC' )
		  ->limit( $per_page )
		  ->offset( ($page - 1) * $per_page );

		$_query = $db->query( $db->sql() );
		while( $row = $_query->fetch() )
		{
			$image = NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['homeimgfile'];
			if( $row['homeimgfile'] != '' and file_exists( $image ) )
			{
				$width = 175;
				$height = 150;

				$row['imgsource'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['homeimgfile'];
				$imginfo = nv_is_image( $image );
				$basename = basename( $image );
				if( $imginfo['width'] > $width or $imginfo['height'] > $height )
				{
					$basename = preg_replace( '/(.*)(\.[a-zA-Z]+)$/', $module_name . '_' . $row['id'] . '_\1_' . $width . '-' . $height . '\2', $basename );
					if( file_exists( NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $basename ) )
					{
						$row['imgsource'] = NV_BASE_SITEURL . NV_TEMP_DIR . '/' . $basename;
					}
					else
					{
						require_once NV_ROOTDIR . '/includes/class/image.class.php';
						$image = new image( $image, NV_MAX_WIDTH, NV_MAX_HEIGHT );

						$thumb_width = $width;
						$thumb_height = $height;
						$maxwh = max( $thumb_width, $thumb_height );
						if( $image->fileinfo['width'] > $image->fileinfo['height'] )
						{
							$width = 0;
							$height = $maxwh;
						}
						else
						{
							$width = $maxwh;
							$height = 0;
						}

						$image->resizeXY( $width, $height );
						$image->cropFromCenter( $thumb_width, $thumb_height );
						$image->save( NV_ROOTDIR . '/' . NV_TEMP_DIR, $basename );
						if( file_exists( NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $basename ) )
						{
							$row['imgsource'] = NV_BASE_SITEURL . NV_TEMP_DIR . '/' . $basename;
						}
					}
				}
			}
			elseif( nv_is_url( $row['homeimgfile'] ) )
			{
				$row['imgsource'] = $row['homeimgfile'];
			}
			elseif( ! empty( $module_config[$module]['show_no_image'] ) )
			{
				$row['imgsource'] =  NV_BASE_SITEURL . $module_config[$module]['show_no_image'];
			}
			else
			{
				$row['imgsource'] = NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/no_image.gif';
			}
			$array_data[$row['id']] = $row;
		}

		$nv_alias_page = nv_alias_page( $page_title, $base_url, $all_page, $per_page, $page );
		$contents = nv_theme_event_viewlist( $array_data, $nv_alias_page );

		if( ! defined( 'NV_IS_MODADMIN' ) and $contents != '' and $cache_file != '' )
		{
			nv_set_cache( $module_name, $cache_file, $contents );
		}
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