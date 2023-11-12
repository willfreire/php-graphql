<?php

namespace App\GraphQL\Types;

use App\GraphQL\AppType;
use App\GraphQL\Database\Post;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class UserType extends ObjectType
{
    public function __construct()
    {
        $config = $this->config();
        parent::__construct($config);
    }

    private function config()
    {
        return [
            'name' => 'User',
            'description' => 'Exibe um Usuário',
            'fields' => [
                'id' => [
                    'type' => Type::id()
                ],
                'name' => [
                    'description' => 'Nome do Autor',
                    'type' => Type::string()
                ],
                'email' => [
                    'type' => Type::string()
                ],
                'posts' => [
                    'description' => 'Artigos deste Autor:',
                    'type' => Type::listOf(AppType::post())
                ]
            ],
            'resolveField' => function($value, $args, $context, $info) {
                if ($info->fieldName == 'posts') {
                    return Post::byAuthor($value['id'], $info);
                }
                return $value[$info->fieldName];
            }
        ];
    }
}