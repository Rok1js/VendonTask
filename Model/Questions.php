<?php

namespace Vendon\Model;

use Vendon\core\DatabaseModel;

class Questions extends DatabaseModel
{
    /**
     * @var int
     */
    public int $id;

    /**
     * @var string
     */
    public string $question;

    /**
     * @var int
     */
    public int $test_id;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'questions';
    }

    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'question',
            'test_id'
        ];
    }
}