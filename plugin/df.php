<?php

# Collectd Df plugin

require_once 'type/GenericStacked.class.php';
require_once 'modules/collectd.inc.php';

# LAYOUT
#
# df-X/df_complex-free.rrd
# df-X/df_complex-reserved.rrd
# df-X/df_complex-used.rrd

$obj = new Type_GenericStacked($CONFIG);
$obj->data_sources = array('value');
$obj->order = array('reserved', 'free', 'used');
$obj->ds_names = array(
	'reserved' => 'Reserved',
	'free' => 'Free',
	'used' => 'Used',
);
$obj->colors = array(
	'reserved' => 'aaaaaa',
	'free' => '00ff00',
	'used' => 'ff0000',
);

switch($obj->args['type']) {
	case 'df_complex':
		$obj->rrd_title = sprintf('Free space (%s)', $obj->args['pinstance']);
		$obj->rrd_vertical = 'Bytes';
		$obj->rrd_format = '%5.1lf%sB';
		$obj->base = $CONFIG['default_base'];
		break;
	case 'df_inodes':
		$obj->rrd_title = sprintf('Free inodes (%s)', $obj->args['pinstance']);
		$obj->rrd_vertical = 'Inodes';
		$obj->rrd_format = '%5.1lf%s';
		break;
}


# backwards compatibility
if ((preg_replace('/[^0-9\.]/','',$CONFIG['version']) < 5) &&
    (preg_replace('/[^a-zA-Z]/','',$CONFIG['version']) == 'Collectd')) {
	$obj->data_sources = array('free', 'used');
	$obj->rrd_title = sprintf('Free space (%s)', $obj->args['tinstance']);
} 

collectd_flush($obj->identifiers);
$obj->rrd_graph();
