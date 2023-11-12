<?php

use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use App\GraphQL\AppType;
use App\GraphQL\Database\User;
use App\GraphQL\Types\QueryType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

# CORS <--

try {
    
    $input     = file_get_contents('php://input');
    $input     = json_decode($input, true);
    $query     = $input['query'] ?? "";
    $variables = $input['variables'] ?? null;

    $schema = new Schema([
        'query' => new QueryType,
        'mutation' => new ObjectType([
            'name' => 'Mutations',
            'fields' => [
                'createUser' => [
                    'type' => AppType::user(),
                    'args' => [
                        'name' => Type::nonNull(Type::string()),
                        'email' => Type::nonNull(Type::string())
                    ],
                    'resolve' => function($value, $args) {
                        $name  = (string) $args['name'];
                        $email = (string) $args['email'];
                        return User::insert($name, $email, 1);
                    }
                ]
            ]
        ])
    ]);

    $result = GraphQL::executeQuery($schema, $query, null, null);
    $output = $result->toArray();
} catch (\Exception $e) {
    $output = [
        'erros' => [
            [
                'message' => $e->getMessage()
            ]
        ]
    ];
}

header('Content-Type: application/json');
echo json_encode($output);