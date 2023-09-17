<?php

namespace Vendon\Model;

use Vendon\core\DatabaseModel;

class TestResult extends DatabaseModel
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
    public int $correct_answers;

    /**
     * @var int
     */
    public int $total_questions;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'results';
    }

    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'user_id',
            'test_id',
            'correct_answers',
            'total_questions'
        ];
    }
}