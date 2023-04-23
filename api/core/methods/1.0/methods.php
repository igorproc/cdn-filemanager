<?php
// single methods
$methods = [
  'utils.time' => [
    'in_active' => true,
    'beforeMethods' => [],
    'objects' => ['Utils'],
    'title' => 'Time()',
    'detail' => 'Get server time',
    'link' => '/time',
    'props' => [
      'now' => [
        'required' => false,
        'type' => 'Int',
        'default' => 0,
      ]
    ]
  ],
  'file.imageUpload' => [
    'in_active' => true,
    'beforeMethods' => [],
    'objects' => ['File'],
    'title' => 'imageUpload()',
    'detail' => 'Image upload',
    'link' => '/file.imageUpload',
    'props' => [
      'server-token' => [
        'required' => true,
        'type' => 'String',
      ]
    ]
  ],
];

return $methods;