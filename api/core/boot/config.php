<?php

// Host setings
$config['hostnameApi'] = 'cdn';
$config['docsHostname'] = 'docs.cdn';
$config['protocol'] = 'http://';

// App base link
$config['mainAppUrl'] = $config['protocol'] . $config['hostnameApi'];

// Docs base link
$config['docs'] = $config['protocol'] . $config['docsHostname'] . '/api/docs';

// Responce body function in handlers/Responce.php
$config['responce'] = 'ResponceWithJSON';
