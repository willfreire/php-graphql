<?php

namespace App\GraphQL\Database;

use App\GraphQL\Connection;

class Post
{
    public static function byAuthor(int $author_id = 0, $info)
    {
        $pdo = Connection::get();

        $allowed_fields = [
            'title',
            'body'
        ];
        $fields = $info->getFieldSelection();
        $sql = createSelectSql('posts', $fields, $allowed_fields, "WHERE author_id = ?");

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$author_id]);
        return $stmt->fetchAll();
    }
    
    public static function all($info)
    {
        $pdo = Connection::get();

        $allowed_fields = [
            'title',
            'body'
        ];
        $fields = $info->getFieldSelection();
        $sql = createSelectSql('posts', $fields, $allowed_fields);

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function paginate($page, $limit, $info)
    {
        $pdo = Connection::get();

        $allowed_fields = [
            'title',
            'body'
        ];

        $offset = $limit * ($page - 1);

        $fields = $info->getFieldSelection();

        $sql = createSelectSql('posts', $fields, $allowed_fields, "LIMIT ?,?");
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(1, $offset, \PDO::PARAM_INT);
        $stmt->bindParam(2, $limit, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}