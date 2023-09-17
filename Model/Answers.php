<?php

namespace Vendon\Model;

use Vendon\core\DatabaseModel;

class Answers extends DatabaseModel
{
    /**
     * @var int
     */
    public int $id;

    /**
     * @var string
     */
    public string $answer;

    /**
     * @var bool
     */
    public bool $is_correct;

    /**
     * @var int
     */
    public int $question_id;


    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'answers';
    }

    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'answer',
            'is_correct',
            'question_id'
        ];
    }

}