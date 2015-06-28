<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2015 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 02 Jun 2015 07:53:31 GMT
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

if ( $nv_Request->isset_request( 'get_alias_title', 'post' ) )
{
	$alias = $nv_Request->get_title( 'get_alias_title', 'post', '' );
	$alias = change_alias( $alias );
	die( $alias );
}

//change status
if( $nv_Request->isset_request( 'change_status', 'post, get' ) )
{
	$id = $nv_Request->get_int( 'id', 'post, get', 0 );
	$content = 'NO_' . $id;

	$query = 'SELECT status FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE id=' . $id;
	$row = $db->query( $query )->fetch();
	if( isset( $row['status'] ) )
	{
		$status = ( $row['status'] ) ? 0 : 1;
		$query = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET status=' . intval( $status ) . ' WHERE id=' . $id;
		$db->query( $query );
		$content = 'OK_' . $id;
	}
	nv_del_moduleCache( $module_name );
	include NV_ROOTDIR . '/includes/header.php';
	echo $content;
	include NV_ROOTDIR . '/includes/footer.php';
	exit();
}

if( $nv_Request->isset_request( 'ajax_action', 'post' ) )
{
	$id = $nv_Request->get_int( 'id', 'post', 0 );
	$new_vid = $nv_Request->get_int( 'new_vid', 'post', 0 );
	$content = 'NO_' . $id;

	list( $id, $parentid ) = $db->query( 'SELECT id, parentid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE id=' . $id )->fetch( 3 );

	if( $new_vid > 0 )
	{
		$sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE id!=' . $id . ' AND parentid=' . $parentid . ' ORDER BY weight ASC';
		$result = $db->query( $sql );
		$weight = 0;
		while( $row = $result->fetch() )
		{
			++$weight;
			if( $weight == $new_vid ) ++$weight;
			$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET weight=' . $weight . ' WHERE id=' . $row['id'];
			$db->query( $sql );
		}
		$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET weight=' . $new_vid . ' WHERE id=' . $id;
		$db->query( $sql );
		$content = 'OK_' . $id;
	}
	nv_del_moduleCache( $module_name );
	include NV_ROOTDIR . '/includes/header.php';
	echo $content;
	include NV_ROOTDIR . '/includes/footer.php';
	exit();
}
if ( $nv_Request->isset_request( 'delete_id', 'get' ) and $nv_Request->isset_request( 'delete_checkss', 'get' ))
{
	$id = $nv_Request->get_int( 'delete_id', 'get' );
	$delete_checkss = $nv_Request->get_string( 'delete_checkss', 'get' );
	if( $id > 0 and $delete_checkss == md5( $id . NV_CACHE_PREFIX . $client_info['session_id'] ) )
	{
		list( $id, $parentid ) = $db->query( 'SELECT id, parentid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE id=' . $id )->fetch( 3 );

		$weight=0;
		$sql = 'SELECT weight FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE id =' . $db->quote( $id );
		$result = $db->query( $sql );
		list( $weight) = $result->fetch( 3 );

		$db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat  WHERE id = ' . $db->quote( $id ) . ' OR parentid=' . $id );
		if( $weight > 0)
		{
			$sql = 'SELECT id, weight FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE weight >' . $weight;
			$result = $db->query( $sql );
			while(list( $id, $weight) = $result->fetch( 3 ))
			{
				$weight--;
				$db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET weight=' . $weight . ' WHERE id=' . intval( $id ));
			}
		}

		$table_name = NV_PREFIXLANG . '_' . $module_data . '_cat';
		nv_fix_order( $table_name );

		nv_del_moduleCache( $module_name );
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&parentid=' . $parentid );
		die();
	}
}

$groups_list = nv_groups_list();
$row = array();
$error = array();
$row['id'] = $nv_Request->get_int( 'id', 'post,get', 0 );
$row['parentid'] = $nv_Request->get_int( 'parentid', 'get,post', 0 );
if ( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$row['parentid'] = $nv_Request->get_int( 'parentid', 'post', 0 );
	$data['parentid_old'] = $nv_Request->get_int( 'parentid_old', 'post', 0 );
	$row['title'] = $nv_Request->get_title( 'title', 'post', '' );
	$row['alias'] = $nv_Request->get_title( 'alias', 'post', '' );
	$row['keywords'] = $nv_Request->get_title( 'keywords', 'post', '', 1 );
	$row['description'] = $nv_Request->get_string( 'description', 'post', '' );
	$row['description'] = nv_nl2br( nv_htmlspecialchars( strip_tags( $row['description'] ) ), '<br />' );
	$row['descriptionhtml'] = $nv_Request->get_editor( 'descriptionhtml', '', NV_ALLOWED_HTML_TAGS );
	$_groups_view = $nv_Request->get_array( 'groups_view', 'post', array() );
	$row['groups_view'] = !empty( $_groups_view ) ? implode( ',', nv_groups_post( array_intersect( $_groups_view, array_keys( $groups_list ) ) ) ) : '';

	if( !empty( $row['alias'] ) )
	{
		$stmt = $db->prepare( 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE id !=' . $row['id'] . ' AND alias = :alias' );
		$stmt->bindParam( ':alias', $row['alias'], PDO::PARAM_STR );
		$stmt->execute();

		if( $stmt->fetchColumn() )
		{
			$weight = $db->query( 'SELECT MAX(id) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat' )->fetchColumn();
			$weight = intval( $weight ) + 1;
			$row['alias'] = $row['alias'] . '-' . $weight;
		}
	}

	if( empty( $row['title'] ) )
	{
		$error[] = $lang_module['error_required_title'];
	}

	if( empty( $error ) )
	{
		try
		{
			if( empty( $row['id'] ) )
			{
				$stmt = $db->prepare( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_cat (parentid, title, alias, keywords, description, descriptionhtml, weight, status) VALUES (:parentid, :title, :alias, :keywords, :description, :descriptionhtml, :weight, :status)' );

				$weight = $db->query( 'SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat' )->fetchColumn();
				$weight = intval( $weight ) + 1;
				$stmt->bindParam( ':weight', $weight, PDO::PARAM_INT );

				$stmt->bindValue( ':status', 1, PDO::PARAM_INT );


			}
			else
			{
				$stmt = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET parentid=:parentid, title = :title, alias = :alias, keywords = :keywords, description = :description, descriptionhtml = :descriptionhtml WHERE id=' . $row['id'] );
			}
			$stmt->bindParam( ':parentid', $row['parentid'], PDO::PARAM_INT );
			$stmt->bindParam( ':title', $row['title'], PDO::PARAM_STR );
			$stmt->bindParam( ':alias', $row['alias'], PDO::PARAM_STR );
			$stmt->bindParam( ':keywords', $row['keywords'], PDO::PARAM_STR );
			$stmt->bindParam( ':description', $row['description'], PDO::PARAM_STR, strlen( $row['description'] ) );
			$stmt->bindParam( ':descriptionhtml', $row['descriptionhtml'], PDO::PARAM_STR, strlen( $row['descriptionhtml'] ) );

			$exc = $stmt->execute();
			if( $exc )
			{
				$table_name = NV_PREFIXLANG . '_' . $module_data . '_cat';
				nv_fix_order( $table_name );
				nv_del_moduleCache( $module_name );
				Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&parentid=' . $row['parentid'] );
				die();
			}
		}
		catch( PDOException $e )
		{
			var_dump($e); die;
			trigger_error( $e->getMessage() );
			die( $e->getMessage() ); //Remove this line after checks finished
		}
	}
}
elseif( $row['id'] > 0 )
{
	$lang_module['cat_add'] = $lang_module['cat_edit'];
	$row = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE id=' . $row['id'] )->fetch();
	if( empty( $row ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
		die();
	}
}
else
{
	$row['id'] = 0;
	$row['title'] = '';
	$row['alias'] = '';
	$row['keywords'] = '';
	$row['description'] = '';
	$row['descriptionhtml'] = '';
}

// Fetch Limit
$show_view = false;
if ( ! $nv_Request->isset_request( 'id', 'post,get' ) )
{
	$show_view = true;
	$per_page = 20;
	$page = $nv_Request->get_int( 'page', 'post,get', 1 );
	$db->sqlreset()
		->select( 'COUNT(*)' )
		->from( '' . NV_PREFIXLANG . '_' . $module_data . '_cat' )
		->where( 'parentid=' . $row['parentid'] );

	$sth = $db->prepare( $db->sql() );

	$sth->execute();
	$num_items = $sth->fetchColumn();

	$db->select( '*' )
		->order( 'weight ASC' )
		->limit( $per_page )
		->offset( ( $page - 1 ) * $per_page );
	$sth = $db->prepare( $db->sql() );

	$sth->execute();
}

$sql = 'SELECT id, title, lev FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE id !=' . $row['id'] . ' AND status=1 ORDER BY sort ASC';
$result = $db->query( $sql );
$array_cat_list = array();
$array_cat_list[0] = array( '0', $lang_module['main_cat'] );

while( list( $id_i, $title_i, $lev_i ) = $result->fetch( 3 ) )
{
	$xtitle_i = '';
	if( $lev_i > 0 )
	{
		$xtitle_i .= '&nbsp;';
		for( $i = 1; $i <= $lev_i; $i++ )
		{
			$xtitle_i .= '---';
		}
	}
	$xtitle_i .= $title_i;
	$array_cat_list[] = array( $id_i, $xtitle_i );
}

$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'ROW', $row );

if( $show_view )
{
	$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
	$generate_page = nv_generate_page( $base_url, $num_items, $per_page, $page );
	if( !empty( $generate_page ) )
	{
		$xtpl->assign( 'NV_GENERATE_PAGE', $generate_page );
		$xtpl->parse( 'main.view.generate_page' );
	}
	while( $view = $sth->fetch() )
	{
		for( $i = 1; $i <= $num_items; ++$i )
		{
			$xtpl->assign( 'WEIGHT', array(
				'key' => $i,
				'title' => $i,
				'selected' => ( $i == $view['weight'] ) ? ' selected="selected"' : '') );
			$xtpl->parse( 'main.view.loop.weight_loop' );
		}
		if( $view['status'] == 1 )
		{
			$check = 'checked';
		}
		else
		{
			$check = '';
		}
		$xtpl->assign( 'CHECK', $check );
		$view['link_view'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;parentid=' . $view['id'];
		$view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $view['id'];
		$view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_id=' . $view['id'] . '&amp;delete_checkss=' . md5( $view['id'] . NV_CACHE_PREFIX . $client_info['session_id'] );
		$xtpl->assign( 'VIEW', $view );
		$xtpl->parse( 'main.view.loop' );
	}
	$xtpl->parse( 'main.view' );
}

if( defined( 'NV_EDITOR' ) )
{
	require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}
$row['descriptionhtml'] = nv_htmlspecialchars( nv_editor_br2nl( $row['descriptionhtml'] ) );
if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
{
	$_uploads_dir = NV_UPLOADS_DIR . '/' . $module_upload;
	$descriptionhtml = nv_aleditor( 'descriptionhtml', '100%', '200px', $row['descriptionhtml'], 'Basic', $_uploads_dir, $_uploads_dir );
}
else
{
	$descriptionhtml = "<textarea style=\"width: 100%\" name=\"descriptionhtml\" id=\"descriptionhtml\" cols=\"20\" rows=\"15\">" . $descriptionhtml . "</textarea>";
}
$xtpl->assign( 'DESCRIPTIONHTML', $descriptionhtml );

foreach( $array_cat_list as $rows_i )
{
	$sl = ( $rows_i[0] == $row['parentid'] ) ? ' selected="selected"' : '';
	$xtpl->assign( 'pid', $rows_i[0] );
	$xtpl->assign( 'ptitle', $rows_i[1] );
	$xtpl->assign( 'pselect', $sl );
	$xtpl->parse( 'main.parent_loop' );
}

if( empty( $row['id'] ) )
{
	$xtpl->parse( 'main.auto_get_alias' );
}

if( ! empty( $error ) )
{
	$xtpl->assign( 'ERROR', implode( '<br />', $error ) );
	$xtpl->parse( 'main.error' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

$page_title = $lang_module['cat'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';