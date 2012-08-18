<?php
ActiveRecord\Config::initialize(function($cfg) {
    $connections = array(
        'legacy' => 'mysql://gray8110:8aU_V{^{,RJC@localhost/gray8110_svblogs',
        'wp' => 'mysql://gray8110:8aU_V{^{,RJC@localhost/gray8110_siskiyouvelo'
    );

    $cfg->set_model_directory('../utils/models');
    $cfg->set_connections($connections);
});