<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (hongoctrien@2mit.org)
 * @Copyright (C) 2015 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 21 Jun 2015 00:23:50 GMT
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

// Categories
$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE status=1 ORDER BY sort ASC';
$global_array_event_cat = nv_db_cache( $sql, 'id', $module_name );