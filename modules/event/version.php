<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (hongoctrien@2mit.org)
 * @Copyright (C) 2015 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 21 Jun 2015 00:23:50 GMT
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$module_version = array(
	'name' => 'Event',
	'modfuncs' => 'main,detail,viewcat',
	'change_alias' => 'main,detail,viewcat',
	'submenu' => 'main,detail,viewcat',
	'is_sysmod' => 0,
	'virtual' => 1,
	'version' => '4.0.00',
	'date' => 'Sun, 21 Jun 2015 00:23:50 GMT',
	'author' => 'VINADES.,JSC (hongoctrien@2mit.org)',
	'uploads_dir' => array($module_name),
	'note' => 'Module event (Sự kiện) dùng cho NukeViet 4'
);