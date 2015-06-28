<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (hongoctrien@2mit.org)
 * @Copyright (C) 2015 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 21 Jun 2015 00:23:50 GMT
 */

if ( ! defined( 'NV_IS_MOD_EVENT' ) ) die( 'Stop!!!' );

/**
 * nv_theme_event_viewlist()
 *
 * @param mixed $array_data
 * @return
 */
function nv_theme_event_viewlist ( $array_data, $nv_alias_page )
{
    global $global_config, $module_name, $module_file, $lang_module, $module_config, $module_info, $op, $global_array_event_cat;

    $xtpl = new XTemplate( 'viewlist.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
    $xtpl->assign( 'LANG', $lang_module );

	if( !empty( $array_data ) )
	{
		foreach( $array_data as $array_data )
		{
			if( NV_CURRENTTIME >= $array_data['start_time'] and NV_CURRENTTIME <= $array_data['end_time'] )
			{
				$array_data['status_str'] = $lang_module['status_2'];
			}
			elseif( NV_CURRENTTIME < $array_data['start_time'] )
			{
				$array_data['status_str'] = $lang_module['status_1'];
			}
			else
			{
				$array_data['status_str'] = $lang_module['status_0'];
			}

			$array_data['start_date'] = nv_date( 'd/m/Y', $array_data['start_time'] );
			$array_data['start_time'] = nv_date( 'H:i', $array_data['start_time'] );
			$array_data['end_date'] = nv_date( 'd/m/Y', $array_data['end_time'] );
			$array_data['end_time'] = nv_date( 'H:i', $array_data['end_time'] );

			$array_data['url_event'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_event_cat[$array_data['catid']]['alias'] . '/' . $array_data['alias'];
			$array_data['title_cat'] = $global_array_event_cat[$array_data['catid']]['title'];
			$array_data['url_cat'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_event_cat[$array_data['catid']]['alias'];

			$xtpl->assign( 'DATA', $array_data );

			if( $array_data['start_date'] == $array_data['end_date'] )
			{
				$xtpl->parse( 'main.data.loop.day' );
			}
			else
			{
				$xtpl->parse( 'main.data.loop.longday' );
			}

			$xtpl->parse( 'main.data.loop' );
		}

		if( !empty( $nv_alias_page ) )
		{
			$xtpl->assign( 'PAGE', $nv_alias_page );$xtpl->parse( 'main.data' );
			$xtpl->parse( 'main.data.page' );
		}

		$xtpl->parse( 'main.data' );
	}

    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

/**
 * nv_theme_event_detail()
 *
 * @param mixed $array_data
 * @return
 */
function nv_theme_event_detail ( $array_data )
{
    global $global_config, $module_name, $module_file, $lang_module, $module_config, $module_info, $op, $global_array_event_cat;

	if( NV_CURRENTTIME >= $array_data['start_time'] and NV_CURRENTTIME <= $array_data['end_time'] )
	{
		$array_data['status_str'] = $lang_module['status_2'];
	}
	elseif( NV_CURRENTTIME < $array_data['start_time'] )
	{
		$array_data['status_str'] = $lang_module['status_1'];
	}
	else
	{
		$array_data['status_str'] = $lang_module['status_0'];
	}

	$array_data['start_date'] = nv_date( 'd/m/Y', $array_data['start_time'] );
	$array_data['start_time'] = nv_date( 'H:i', $array_data['start_time'] );
	$array_data['end_date'] = nv_date( 'd/m/Y', $array_data['end_time'] );
	$array_data['end_time'] = nv_date( 'H:i', $array_data['end_time'] );

	$array_data['title_cat'] = $global_array_event_cat[$array_data['catid']]['title'];
	$array_data['url_cat'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_event_cat[$array_data['catid']]['alias'];

    $xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
    $xtpl->assign( 'LANG', $lang_module );
    $xtpl->assign( 'DATA', $array_data );

	if( $array_data['start_date'] == $array_data['end_date'] )
	{
		$xtpl->parse( 'main.day' );
	}
	else
	{
		$xtpl->parse( 'main.longday' );
	}

    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}