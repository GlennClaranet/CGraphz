<?php

# Collectd NUT plugin


require_once 'type/Default.class.php';
require_once 'modules/collectd.inc.php';

## LAYOUT
# nut-XXXX/
# nut-XXXX/percent-XXXX.rrd
# nut-XXXX/temerature-XXXX.rrd
# nut-XXXX/voltage-XXXX.rrd
# nut-XXXX/timeleft-XXXX.rrd

$obj = new Type_Default($CONFIG);
switch($obj->args['type']) {
	case 'frequency':
		if (preg_replace('/[^0-9\.]/','',$CONFIG['version']) < 5) {
			$obj->data_sources = array('frequency');
		} else {
			$obj->data_sources = array('value');
		}
		$obj->ds_names = array('output' => 'Output');
		$obj->rrd_title = sprintf('Frequency (%s)', $obj->args['pinstance']);
		$obj->rrd_vertical = 'Hz';
		$obj->rrd_format = '%5.1lf%s';
	break;
	case 'percent':
		if (preg_replace('/[^0-9\.]/','',$CONFIG['version']) < 5) {
			$obj->data_sources = array('percent');
		} else {
			$obj->data_sources = array('value');
		}
		$obj->ds_names = array('charge' => 'Charge',
					'load' => 'Load');
		$obj->rrd_title = sprintf('Charge & load (%s)', $obj->args['pinstance']);
		$obj->rrd_vertical = '%';
		$obj->rrd_format = '%5.1lf';
	break;
	case 'temperature':
		$obj->data_sources = array('value');
		$obj->ds_names = array('battery' => 'Battery');
		$obj->rrd_title = sprintf('Temperature (%s)', $obj->args['pinstance']);
		$obj->rrd_vertical = '°C';
		$obj->rrd_format = '%5.1lf%s';
	break;
	case 'timeleft':
		if (preg_replace('/[^0-9\.]/','',$CONFIG['version']) < 5) {
			$obj->data_sources = array('timeleft');
		} else {
			$obj->data_sources = array('value');
		}
		$obj->ds_names = array('timeleft' => 'Timeleft');
		$obj->rrd_title = sprintf('Timeleft (%s)', $obj->args['pinstance']);
		$obj->rrd_vertical = 'Seconds';
		$obj->rrd_format = '%5.1lf';
	break;
	case 'voltage':
		$obj->data_sources = array('value');
		$obj->ds_names = array('battery' => 'Battery',
					'input' => 'Input',
					'output' => 'Output');
		$obj->rrd_title = sprintf('Voltage (%s)', $obj->args['pinstance']);
		$obj->rrd_vertical = 'Volts';
		$obj->rrd_format = '%5.1lf';
	break;
	default:
		error_image('Unknown graph type :'.PHP_EOL.str_replace('&',PHP_EOL,$_SERVER['QUERY_STRING']));
	break;
}

collectd_flush($obj->identifiers);
$obj->rrd_graph();
