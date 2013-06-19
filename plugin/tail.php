<?php

# Collectd tail plugin

require_once 'type/Default.class.php';
require_once 'modules/collectd.inc.php';


$obj = new Type_Default($CONFIG);
$obj->width = $width;
$obj->heigth = $heigth;

$obj->rrd_title = sprintf('tail: %s (%s)', $obj->args['pinstance'], $obj->args['type']);
$obj->rrd_format = '%5.1lf%s';

collectd_flush($obj->identifiers);
$obj->rrd_graph();
