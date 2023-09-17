<?php

namespace Vendon\Model;

use Vendon\core\DatabaseModel;

class UserAnswers extends DatabaseModel
{
    /**
     * @var int
     */
    public int $id;

    /**
     * @var int
     */
    public int $user_id;

    /**
     * @var int
     */
    public int $test_id;

    /**
     * @var int
     */
    public int $question_id;

    /**
     * @var int
     */
    public int $answer_id;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'user_answers';
    }

    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'user_id',
            'test_id',
            'question_id',
            'answer_id'
        ];
    }
}