<?php

namespace App\GraphQL\Database;

use App\GraphQL\Connection;

class User
{
    public static function first($id, $info)
    {
        $pdo = Connection::get();

        $allowed_fields = [
            'id',
            'name',
            'email'
        ];
        $fields = $info->getFieldSelection();
        $sql = createSelectSql('users', $fields, $allowed_fields, 'WHERE id=?');

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function insert(string $name = '', string $email = '', int $active = 1): array
    {
        if (empty($name) & empty($email)) {
            return [];
        }

        $pdo = Connection::get();

        $sql = <<<SQL
            INSERT INTO `users` (`name`, `email`, `active`) VALUES (?, ?, ?);
        SQL;

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $name,
            $email,
            $active
        ]);

        $lastId = $pdo->lastInsertId();

        return self::getUser($pdo, $lastId);
    }

    private static function getUser(\PDO $pdo, int $id = 0): array
    {
        if (!$id) {
            return [];
        }

        $sql = <<<SQL
            SELECT `id`, `name`, `email`, `active` FROM `users` WHERE `id` = ?;
        SQL;

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $id
        ]);

        return $stmt->fetch();
    }
}