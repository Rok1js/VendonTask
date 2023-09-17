<?php

namespace Vendon\Model;

use Vendon\core\DatabaseModel;

class Users extends DatabaseModel
{
    /**
     * @var int
     */
    public int $id;

    /**
     * @var string
     */
    public string $name;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'users';
    }

    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'name',
        ];
    }
}