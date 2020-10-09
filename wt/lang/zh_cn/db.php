<?php
defined('ShopWT') or exit('Access Denied By ShopWT');
$lang	= array(
/**
 * index
 */
'db_index_min_size'		=> '分卷大小至少为10K',
'db_index_name_exists'	=> '备份名已存在，请填写其他名称',
'db_index_choose'		=> '请选择要备份的数据库表',
'db_index_backup_to_wait'	=> '正在进行备份，请稍等',
'db_index_back_to_db'	=> '返回数据库备份',
'db_index_backup_succ'	=> '备份成功',
'db_index_backuping'		=> '正在备份',
'db_index_backup_succ1'	=> '分卷数据#',
'db_index_backup_succ2'	=> '正在备份中...',
'db_index_db'			=> '数据库',
'db_index_backup'		=> '备份',
'db_index_restore'		=> '恢复',
'db_index_backup_method'	=> '备份方式',
'db_index_all_data'		=> '备份全部数据',
'db_index_spec_table'	=> '备份选定的表',
'db_index_table'			=> '数据表',
'db_index_size'			=> '分卷大小',
'db_index_name'			=> '备份名',
'db_index_name_tip'		=> '备份名字由1到20位数字、字母或下划线组成',
'db_index_backup_tip'	=> '为保证数据完整性请确保您的站点处于关闭状态，您确定要马上执行当前操作吗',
'db_index_help1'			=> '数据备份功能根据你的选择备份全部数据或指定数据，导出的数据文件可用“数据恢复”功能或 phpMyAdmin 导入',
'db_index_help2'			=> '建议定期备份数据库',
/**
 * 恢复
 */
'db_restore_file_not_exists'		=> '删除的文件不存在',
'db_restore_del_succ'			=> '删除备份成功',
'db_restore_choose_file_to_del'	=> '请选择要删除的内容',
'db_restore_backup_time'			=> '备份时间',
'db_restore_backup_size'			=> '备份大小',
'db_restore_volumn'				=> '卷数',
'db_restore_import'				=> '导入',
/**
 * 导入
 */
'db_import_back_to_list'			=> '返回数据库备份',
'db_import_succ'					=> '导入成功',
'db_import_going'				=> '正在导入',
'db_import_succ2'				=> '成功导入...',
'db_import_fail'					=> '数据导入失败',
'db_import_file_not_exists'		=> '导入的文件不存在',
'db_import_help1'				=> '点击导入选项进行数据库恢复',
/**
 * 删除
 */
'db_del_succ'	=> '删除备份成功',
);