<?php

return [
    '__name' => 'api-product-saved',
    '__version' => '0.0.1',
    '__git' => 'git@github.com:getmim/api-product-saved.git',
    '__license' => 'MIT',
    '__author' => [
        'name' => 'Iqbal Fauzi',
        'email' => 'iqbalfawz@gmail.com',
        'website' => 'https://iqbalfn.com/'
    ],
    '__files' => [
        'modules/api-product-saved' => ['install','update','remove'],
        'app/api-product-saved' => ['install','update','remove']
    ],
    '__dependencies' => [
        'required' => [
            [
                'product-saved' => NULL
            ],
            [
                'api' => NULL
            ],
            [
                'lib-app' => NULL
            ]
        ],
        'optional' => []
    ],
    'autoload' => [
        'classes' => [
            'ApiProductSaved\\Controller' => [
                'type' => 'file',
                'base' => 'app/api-product-saved/controller'
            ]
        ],
        'files' => []
    ],
    'routes' => [
        'api' => [
            'apiProductSaved' => [
                'path' => [
                    'value' => '/product/saved'
                ],
                'handler' => 'ApiProductSaved\\Controller\\Saved::index',
                'method' => 'GET'
            ],
            'apiProductSavedCreate' => [
                'path' => [
                    'value' => '/product/saved'
                ],
                'handler' => 'ApiProductSaved\\Controller\\Saved::create',
                'method' => 'POST'
            ],
            'apiProductSavedTruncate' => [
                'path' => [
                    'value' => '/product/saved'
                ],
                'handler' => 'ApiProductSaved\\Controller\\Saved::truncate',
                'method' => 'DELETE'
            ],
            'apiProductSavedSingle' => [
                'path' => [
                    'value' => '/product/saved/(:identity)',
                    'params' => [
                        'identity' => 'any'
                    ]
                ],
                'handler' => 'ApiProductSaved\\Controller\\Saved::single',
                'method' => 'GET'
            ],
            'apiProductSavedRemove' => [
                'path' => [
                    'value' => '/product/saved/(:identity)',
                    'params' => [
                        'identity' => 'any'
                    ]
                ],
                'handler' => 'ApiProductSaved\\Controller\\Saved::remove',
                'method' => 'DELETE'
            ]
        ]
    ],
    'libForm' => [
        'forms' => [
            'api.product-saved.create' => [
                'product' => [
                    'label' => 'Product',
                    'rules' => [
                        'required' => true,
                        'exists' => [
                            'model' => 'Product\\Model\\Product',
                            'field' => 'id',
                            'where' => ['status'=>2]
                        ]
                    ]
                ]
            ]
        ]
    ]
];