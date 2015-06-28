<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2015 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 28 Jun 2015 10:33:09 GMT
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$sql_drop_module = array();
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_cat";

$sql_create_module = $sql_drop_module;
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "(
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  title varchar(255) NOT NULL,
  alias varchar(255) NOT NULL,
  catid smallint(4) unsigned NOT NULL,
  adduser int(11) unsigned NOT NULL DEFAULT '0',
  address varchar(255) NOT NULL COMMENT 'Địa chỉ diễn ra sự kiện',
  quantity int(11) unsigned NOT NULL COMMENT 'Giới hạn sSố lượng tham gia',
  homeimgfile varchar(255) NOT NULL COMMENT 'Hình ảnh sự kiện',
  homeimgalt varchar(255) NOT NULL,
  homeimgthumb tinyint(4) unsigned NOT NULL,
  hometext text NOT NULL COMMENT 'Giới thiệu ngắn gọn',
  bodytext text NOT NULL COMMENT 'Chi tiết sự kiện',
  keywords text NOT NULL,
  groups_view varchar(255) NOT NULL,
  start_time int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian bắt đầu',
  end_time int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian kết thúc',
  add_time int(11) unsigned NOT NULL DEFAULT '0',
  edit_time int(11) unsigned NOT NULL DEFAULT '0',
  status tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_cat(
  id smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  parentid smallint(4) unsigned NOT NULL DEFAULT '0',
  title varchar(255) NOT NULL COMMENT 'Tên gọi chủ đề',
  alias varchar(255) NOT NULL,
  keywords text NOT NULL,
  description text NOT NULL COMMENT 'Mô tả',
  descriptionhtml text NOT NULL COMMENT 'Mô tả chi tiết',
  groups_view varchar(255) NOT NULL,
  lev smallint(4) unsigned NOT NULL DEFAULT '0',
  numsub smallint(4) unsigned NOT NULL DEFAULT '0',
  subid varchar(255) NOT NULL,
  sort smallint(4) unsigned NOT NULL DEFAULT '0',
  weight smallint(4) unsigned NOT NULL DEFAULT '0',
  status tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Trạng thái',
  PRIMARY KEY (id)
) ENGINE=MyISAM";