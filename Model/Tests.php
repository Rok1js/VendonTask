<?php

namespace Vendon\Model;

use Vendon\core\DatabaseModel;

class Tests extends DatabaseModel
{
    /**
     * @var int
     */
    public int $id;

    /**
     * @var string
     */
    public string $title;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'tests';
    }

    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'title',
        ];
    }
}