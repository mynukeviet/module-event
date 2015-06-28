<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (hongoctrien@2mit.org)
 * @Copyright (C) 2015 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 21 Jun 2015 00:23:50 GMT
 */

if ( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

define( 'NV_IS_MOD_EVENT', true );
require_once NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

$page = 1;
$per_page = 10;
$catid = 0;
$parentid = 0;
$alias_url = '';
$alias_cat_url = isset( $array_op[0] ) ? $array_op[0] : '';

// Categories
foreach( $global_array_event_cat as $array_event_cat )
{
	$global_array_event_cat[$array_event_cat['id']]['link'] =  NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $array_event_cat['alias'];
	if( $alias_cat_url == $array_event_cat['alias'] )
	{
		$catid = $array_event_cat['id'];
		$parentid = $array_event_cat['parentid'];
	}
}

if( $op == 'main' )
{
	if( empty( $catid ) )
	{
		if( preg_match( '/^page\-([0-9]+)$/', ( isset( $array_op[0] ) ? $array_op[0] : '' ), $m ) )
		{
			$page = ( int )$m[1];
		}
	}
	else
	{
		if( sizeof( $array_op ) == 2 and preg_match( '/^([a-z0-9\-]+)$/i', $array_op[1] ) and ! preg_match( '/^page\-([0-9]+)$/', $array_op[1], $m2 ) )
		{
			$op = 'detail';
			$alias_url = $array_op[1];
		}
		else
		{
			$op = 'viewcat';
		}

		$parentid = $catid;
		while( $parentid > 0 )
		{
			$array_cat_i = $global_array_event_cat[$parentid];
			$array_mod_title[] = array(
				'catid' => $parentid,
				'title' => $array_cat_i['title'],
				'link' => $array_cat_i['link']
			);
			$parentid = $array_cat_i['parentid'];
		}
		sort( $array_mod_title, SORT_NUMERIC );
	}
}