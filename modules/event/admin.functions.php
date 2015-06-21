<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (hongoctrien@2mit.org)
 * @Copyright (C) 2015 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 21 Jun 2015 00:23:50 GMT
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

define( 'NV_IS_FILE_ADMIN', true );

/**
 * nv_fix_order()
 *
 * @param integer $parentid
 * @param integer $order
 * @param integer $lev
 * @return
 */
function nv_fix_order( $table_name, $parentid = 0, $sort = 0, $lev = 0 )
{
	global $db, $db_config, $module_data;

	$sql = 'SELECT id, parentid FROM ' . $table_name . ' WHERE parentid=' . $parentid . ' ORDER BY weight ASC';
	$result = $db->query( $sql );
	$array_order = array( );
	while( $row = $result->fetch( ) )
	{
		$array_order[] = $row['id'];
	}
	$result->closeCursor( );
	$weight = 0;
	if( $parentid > 0 )
	{
		++$lev;
	}
	else
	{
		$lev = 0;
	}
	foreach( $array_order as $order_i )
	{
		++$sort;
		++$weight;

		$sql = 'UPDATE ' . $table_name . ' SET weight=' . $weight . ', sort=' . $sort . ', lev=' . $lev . ' WHERE id=' . $order_i;
		$db->query( $sql );

		$sort = nv_fix_order( $table_name, $order_i, $sort, $lev );
	}

	$numsub = $weight;

	if( $parentid > 0 )
	{
		$sql = "UPDATE " . $table_name . " SET numsub=" . $numsub;
		if( $numsub == 0 )
		{
			$sql .= ",subid=''";
		}
		else
		{
			$sql .= ",subid='" . implode( ",", $array_order ) . "'";
		}
		$sql .= " WHERE id=" . intval( $parentid );
		$db->query( $sql );
	}
	return $sort;
}