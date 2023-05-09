<?php

declare(strict_types=1);

namespace App\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;

use ApiPlatform\OpenApi\Model;
use ApiPlatform\OpenApi\OpenApi;

final class RefreshDecorator implements OpenApiFactoryInterface
{
    public function __construct(
        private OpenApiFactoryInterface $decorated
    ) {}

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);
        $schemas = $openApi->getComponents()->getSchemas();

        $schemas['Token'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'refreshToken' => [
                    'type' => 'string',
                    'readOnly' => true
                ]
            ],
        ]);
        $schemas['Credentials'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'refreshToken' => [
                    'type' => 'string',
                    'example'=> '7315c3f66c6ba7c4bc32297a07c8cd14153f1e4693e24d4906096179afe0bbedd272e312e4b8b94ab346238063fcd90d6d178be3396503da7bd93b0f4574f180'
                ]
            ],
        ]);

        $schemas = $openApi->getComponents()->getSecuritySchemes() ?? [];
        $schemas['JWT'] = new \ArrayObject([
            'type' => 'http',
            'scheme' => 'bearer',
            'bearerFormat' => 'JWT',
        ]);

        $pathItem = new Model\PathItem(
            ref: 'JWT Token',
            post: new Model\Operation(
                operationId: 'postCredentialsItem',
                tags: ['Token'],
                responses: [
                    '200' => [
                        'description' => 'Refresh JWT Token',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Token',
                                ],
                            ],
                        ],
                    ],
                ],
                summary: 'Refresh Jwt token to login.',
                requestBody: new Model\RequestBody(
                    description: 'Generate new JWT Token from Refresh token',
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/Credentials',
                            ],
                        ],
                    ]),
                ),
                security: [],
            ),
        );
        $openApi->getPaths()->addPath('/api/token/refresh', $pathItem);

        return $openApi;
    }
}