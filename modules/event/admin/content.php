<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2015 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Mon, 22 Jun 2015 15:41:26 GMT
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

if ( $nv_Request->isset_request( 'get_alias_title', 'post' ) )
{
	$alias = $nv_Request->get_title( 'get_alias_title', 'post', '' );
	$alias = change_alias( $alias );
	die( $alias );
}

$row = array();
$error = array();
$row['id'] = $nv_Request->get_int( 'id', 'post,get', 0 );
if ( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$row['title'] = $nv_Request->get_title( 'title', 'post', '' );
	$row['alias'] = $nv_Request->get_title( 'alias', 'post', '' );
	$row['alias'] = ( empty($row['alias'] ))? change_alias( $row['title'] ) : change_alias( $row['alias'] );
	$row['catid'] = $nv_Request->get_int( 'catid', 'post', 0 );
	$row['address'] = $nv_Request->get_title( 'address', 'post', '' );
	$row['quantity'] = $nv_Request->get_int( 'quantity', 'post', 0 );
	$row['image'] = $nv_Request->get_title( 'image', 'post', '' );
	if( is_file( NV_DOCUMENT_ROOT . $row['image'] ) )
	{
		$row['image'] = substr( $row['image'], strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' ) );
	}
	else
	{
		$row['image'] = '';
	}
	$row['hometext'] = $nv_Request->get_string( 'hometext', 'post', '' );
	$row['bodytext'] = $nv_Request->get_editor( 'bodytext', '', NV_ALLOWED_HTML_TAGS );
	if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string( 'start_time', 'post' ), $m ) )
	{
		$_hour = 0;
		$_min = 0;
		$row['start_time'] = mktime( $_hour, $_min, 0, $m[2], $m[1], $m[3] );
	}
	else
	{
		$row['start_time'] = 0;
	}
	if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string( 'end_time', 'post' ), $m ) )
	{
		$_hour = 0;
		$_min = 0;
		$row['end_time'] = mktime( $_hour, $_min, 0, $m[2], $m[1], $m[3] );
	}
	else
	{
		$row['end_time'] = 0;
	}

	if( empty( $row['title'] ) )
	{
		$error[] = $lang_module['error_required_title'];
	}
	elseif( empty( $row['catid'] ) )
	{
		$error[] = $lang_module['error_required_catid'];
	}
	elseif( empty( $row['address'] ) )
	{
		$error[] = $lang_module['error_required_address'];
	}
	elseif( empty( $row['hometext'] ) )
	{
		$error[] = $lang_module['error_required_hometext'];
	}
	elseif( empty( $row['bodytext'] ) )
	{
		$error[] = $lang_module['error_required_bodytext'];
	}

	if( empty( $error ) )
	{
		try
		{
			if( empty( $row['id'] ) )
			{

				$row['adduser'] = 0;
				$row['add_time'] = 0;
				$row['edit_time'] = 0;

				$stmt = $db->prepare( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . ' (title, alias, catid, adduser, address, quantity, image, hometext, bodytext, start_time, end_time, add_time, edit_time, status) VALUES (:title, :alias, :catid, :adduser, :address, :quantity, :image, :hometext, :bodytext, :start_time, :end_time, :add_time, :edit_time, :status)' );

				$stmt->bindParam( ':adduser', $row['adduser'], PDO::PARAM_INT );
				$stmt->bindParam( ':add_time', $row['add_time'], PDO::PARAM_INT );
				$stmt->bindParam( ':edit_time', $row['edit_time'], PDO::PARAM_INT );
				$stmt->bindValue( ':status', 1, PDO::PARAM_INT );


			}
			else
			{
				$stmt = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET title = :title, alias = :alias, catid = :catid, address = :address, quantity = :quantity, image = :image, hometext = :hometext, bodytext = :bodytext, start_time = :start_time, end_time = :end_time WHERE id=' . $row['id'] );
			}
			$stmt->bindParam( ':title', $row['title'], PDO::PARAM_STR );
			$stmt->bindParam( ':alias', $row['alias'], PDO::PARAM_STR );
			$stmt->bindParam( ':catid', $row['catid'], PDO::PARAM_INT );
			$stmt->bindParam( ':address', $row['address'], PDO::PARAM_STR );
			$stmt->bindParam( ':quantity', $row['quantity'], PDO::PARAM_INT );
			$stmt->bindParam( ':image', $row['image'], PDO::PARAM_STR );
			$stmt->bindParam( ':hometext', $row['hometext'], PDO::PARAM_STR, strlen($row['hometext']) );
			$stmt->bindParam( ':bodytext', $row['bodytext'], PDO::PARAM_STR, strlen($row['bodytext']) );
			$stmt->bindParam( ':start_time', $row['start_time'], PDO::PARAM_INT );
			$stmt->bindParam( ':end_time', $row['end_time'], PDO::PARAM_INT );

			$exc = $stmt->execute();
			if( $exc )
			{
				nv_del_moduleCache( $module_name );
				Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
				die();
			}
		}
		catch( PDOException $e )
		{
			trigger_error( $e->getMessage() );
			die( $e->getMessage() ); //Remove this line after checks finished
		}
	}
}
elseif( $row['id'] > 0 )
{
	$row = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $row['id'] )->fetch();
	if( empty( $row ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
		die();
	}
}
else
{
	$row['id'] = 0;
	$row['title'] = '';
	$row['alias'] = '';
	$row['catid'] = 0;
	$row['address'] = '';
	$row['quantity'] = 0;
	$row['image'] = '';
	$row['hometext'] = '';
	$row['bodytext'] = '';
	$row['start_time'] = 0;
	$row['end_time'] = 0;
}

if( empty( $row['start_time'] ) )
{
	$row['start_time'] = '';
}
else
{
	$row['start_time'] = date( 'd/m/Y', $row['start_time'] );
}

if( empty( $row['end_time'] ) )
{
	$row['end_time'] = '';
}
else
{
	$row['end_time'] = date( 'd/m/Y', $row['end_time'] );
}
if( ! empty( $row['image'] ) and is_file( NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['image'] ) )
{
	$row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['image'];
}

if( defined( 'NV_EDITOR' ) ) require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
$row['bodytext'] = htmlspecialchars( nv_editor_br2nl( $row['bodytext'] ) );
if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
{
	$row['bodytext'] = nv_aleditor( 'bodytext', '100%', '300px', $row['bodytext'] );
}
else
{
	$row['bodytext'] = '<textarea style="width:100%;height:300px" name="bodytext">' . $row['bodytext'] . '</textarea>';
}

$array_catid_event = array();
$_sql = 'SELECT id,title FROM nv4_vi_event_cat';
$_query = $db->query( $_sql );
while( $_row = $_query->fetch() )
{
	$array_catid_event[$_row['id']] = $_row;
}

$row['quantity'] = !empty( $row['quantity'] ) ? $row['quantity'] : '';

$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'MODULE_UPLOAD', $module_upload );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'ROW', $row );
foreach( $array_catid_event as $value )
{
	$xtpl->assign( 'OPTION', array(
		'key' => $value['id'],
		'title' => $value['title'],
		'selected' => ($value['id'] == $row['catid']) ? ' selected="selected"' : ''
	) );
	$xtpl->parse( 'main.select_catid' );
}

if( ! empty( $error ) )
{
	$xtpl->assign( 'ERROR', implode( '<br />', $error ) );
	$xtpl->parse( 'main.error' );
}
if( empty( $row['id'] ) )
{
	$xtpl->parse( 'main.auto_get_alias' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

$page_title = $lang_module['content'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';