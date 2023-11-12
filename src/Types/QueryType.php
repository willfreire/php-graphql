<?php

namespace App\GraphQL\Types;

use App\GraphQL\AppType;
use App\GraphQL\Database\User;
use App\GraphQL\Database\Post;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class QueryType extends ObjectType
{
    public function __construct()
    {
        $config = $this->config();
        parent::__construct($config);
    }

    private function config()
    {
        return [
            'name' => 'Query',
            'description' => 'Raiz da API',
            'fields' => [
                'user' => [
                    'name' => 'User',
                    'type' => AppType::user(),
                    'description' => 'Exibe usuário por ID',
                    'args' => [
                        'id' => Type::nonNull(Type::id())
                    ]
                ],
                'posts' => [
                    'name' => 'Posts',
                    'type' => Type::listOf(AppType::post()),
                    'description' => 'Exibe a lista de Artigos',
                    'args' => [
                        'page' => Type::int(),
                        'limit' => Type::int()
                    ]
                ]
            ],
            'resolveField' => function($val, $args, $context, $info) {
                $method = strtolower($info->fieldName);
                return $this->{$method}($val, $args, $context, $info);
            }
        ];
    }

    public function user($val, $args, $context, $info)
    {
        return User::first($args['id'], $info);
    }

    public function posts($val, $args, $context, $info)
    {
        if (!isset($args['page'])) {
            return Post::all($info);
        }
        $page  = $args['page'] ?? 1;
        $limit = $args['limit'] ?? 10;
        return Post::paginate($page, $limit, $info);
    }
}