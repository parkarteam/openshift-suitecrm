<?php
/***CONFIGURATOR***/
$sugar_config['disable_persistent_connections'] = false;
$sugar_config['http_referer']['list'][] = $sugar_config['host_name'];
$sugar_config['http_referer']['actions'] =array( 'index', 'ListView', 'DetailView', 'EditView', 'oauth', 'authorize', 'Authenticate', 'Login', 'SupportPortal', 'ajaxui' );