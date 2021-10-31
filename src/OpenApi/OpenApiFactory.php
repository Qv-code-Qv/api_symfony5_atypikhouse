<?php

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\Model\Operation;
use ApiPlatform\Core\OpenApi\Model\PathItem;
use ApiPlatform\Core\OpenApi\Model\RequestBody;
use ApiPlatform\Core\OpenApi\OpenApi;
use PhpParser\Node\Expr\Array_;

class OpenApiFactory implements OpenApiFactoryInterface
{
    public function __construct(private OpenApiFactoryInterface $decorated)
    {
    }
    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);
        /** @var PathItem $path */
        foreach ($openApi->getPaths()->getPaths() as $key => $path) {
            if ($path->getGet() && $path->getSummary() == 'hidden') {
                $openApi->getPaths()->addPath($key, $path->withGet(null));
            }
        }
        $schemas = $openApi->getComponents()->getSecuritySchemes();
        $schemas['bearerAuth'] = new \ArrayObject([
            'type' => 'http',
            'scheme' => 'bearer',
            'bearerFormat' => 'JWT'
        ]);
        //$openApi = $openApi->withSecurity(['cookieAuth' => []]);

        $schemas = $openApi->getComponents()->getSchemas();
        $schemas['Credentials'] = new  \ArrayObject([
          'type' => 'object',
          'properties' => [
              'email' => [
                  'type' => 'string',
              ],
              'password' => [
                  'type' => 'string',
              ]
          ]
        ]);
        $pathItem = new PathItem(
            post: new Operation(
                operationId: 'postApiLogin',
                tags: ['Auth'],
                responses: [
                    '200' => [
                        'description' => 'Utilisateur connecté',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/User-read.User'
                                ]
                            ]
                        ]
                    ]
                ],
                requestBody: new RequestBody(
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                '$ref' =>  '#/components/schemas/Credentials'
                            ]
                        ]
                    ])
                )
            )
        );
        $profilePath = new PathItem(
            get: new Operation(
                operationId: 'getUserProfile',
                responses: [
                '200' => [
                    'description' => 'Profile utilisateur',
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/User-read.User'
                            ]
                        ]
                    ]
                ]
            ],
                requestBody: new RequestBody(
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                '$ref' =>  '#/components/schemas/Credentials'
                            ]
                        ]
                    ])
                )
            )
        );
        $openApi->getPaths()->addPath('/api/login', $pathItem);
        $openApi->getPaths()->addPath('/api/logged/profile', $profilePath );
        return $openApi;
    }
}
