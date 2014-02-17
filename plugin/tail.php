<?php

# Collectd tail plugin

require_once 'type/Default.class.php';
require_once 'modules/collectd.inc.php';


$obj = new Type_Default($CONFIG);

$obj->rrd_title = sprintf('tail: %s (%s)', $obj->args['pinstance'], $obj->args['type']);
$obj->rrd_format = '%5.1lf%s';

# backwards compatibility
if ($CONFIG['version'] < 5) {
	if (strcmp($obj->args['type'], 'gauge') != 0) {
		$obj->data_sources = array('count');
		if (count($obj->ds_names) == 1) {
			$obj->ds_names['count'] = $obj->ds_names['value'];
			unset($obj->ds_names['value']);
		}
		if (count($obj->colors) == 1) {
			$obj->colors['count'] = $obj->colors['value'];
			unset($obj->colors['value']);
		}
	}
}

collectd_flush($obj->identifiers);
$obj->rrd_graph();


