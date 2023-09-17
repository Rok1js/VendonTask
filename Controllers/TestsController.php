<?php

namespace Vendon\Controllers;

use Vendon\core\Authorization;
use Vendon\core\Request;
use Vendon\core\Validate;
use Vendon\Exceptions\ForbiddenException;
use Vendon\Exceptions\UnprocessableException;
use Vendon\Model\Answers;
use Vendon\Model\Questions;
use Vendon\Model\TestResult;
use Vendon\Model\Tests;
use Vendon\Model\UserAnswers;
use Vendon\Model\Users;

class TestsController
{
    /**
     * @var Tests
     */
    public Tests $tests;

    /**
     * @var Users
     */
    public Users $user;

    /**
     * @var Questions
     */
    public Questions $questions;

    /**
     * @var Answers
     */
    public Answers $answers;

    /**
     * @var UserAnswers
     */
    public UserAnswers $userAnswers;

    /**
     * @var Validate
     */
    public Validate $validate;

    /**
     * @return bool|string
     */
    //vvv Retrieving all tests in database
    public function retrieveData(): bool|string
    {
        $this->tests = new Tests();
        return $this->tests->all();
    }

    /**
     * @param Request $request
     * @param Authorization $authorization
     * @return array|null
     * @throws UnprocessableException
     */
    //vvv Saving user data
    public function saveUserData(Request $request, Authorization $authorization): ?array
    {
        $this->user = new Users();
        $this->validate = new Validate();
        $requestData = $request->getBody();

        //vvv Defining rules for each attribute and the rule for it
        $rules = [
            'name' => [$this->validate::RULE_REQUIRED],
        ];

        //vvv Validating the input so it is not empty
        if (!$this->validate->validate($requestData, $rules)) {
            throw new UnprocessableException($this->validate->getErrors());
        }

        $this->user->loadData($requestData);
        $savedData = $this->user->save();
        $savedData['token'] = $authorization->generateToken($savedData['id']);

        return $savedData;
    }

    /**
     * @param Request $request
     * @return array|bool
     */
    //vvv Retrieving all the questions to the requested test
    public function retrieveQuestions(Request $request): bool|array
    {
        $this->questions = new Questions();
        $params = $request->getRouteParams();
        $testId = $params['id'];

        return $this->questions->select()->where('test_id', '=', $testId)->getQuery();
    }

    /**
     * @param Request $request
     * @return array|bool
     */
    //vvv Retrieving all the answers to the requested question
    public function retrieveAnswers(Request $request): bool|array
    {
        $this->answers = new Answers();
        $params = $request->getRouteParams();
        $questionId = $params['id'];

        return $this->answers->select(['id', 'answer', 'question_id'])
            ->where('question_id', '=', $questionId)
            ->getQuery();
    }

    /**
     * @param Request $request
     * @param Authorization $authorization
     * @return array|null
     * @throws UnprocessableException
     */
    //vvv Saving user answer data
    public function saveUserAnswer(Request $request, Authorization $authorization): ?array
    {
        $this->userAnswers = new UserAnswers();
        $this->validate = new Validate();
        $requestData = $request->getBody();

        //vvv Defining rules for each attribute and the rule for it
        $rules = [
            'test_id' => [$this->validate::RULE_REQUIRED],
            'user_id' => [$this->validate::RULE_REQUIRED],
            'question_id' => [$this->validate::RULE_REQUIRED],
            'answer_id' => [$this->validate::RULE_REQUIRED],
        ];

        //vvv Validating the input so it is not empty
        if (!$this->validate->validate($requestData, $rules)) {
            throw new UnprocessableException($this->validate->getErrors());
        }

        $token = $request->getToken();
        $result = $authorization->validateToken($token);

        //vvv Validating if the user is authorized to make the request
        if (!$result || $authorization->getPayload($token)['user_id'] !== $requestData['user_id']) {
            throw new ForbiddenException();
        }

        $this->userAnswers->loadData($requestData);

        return $this->userAnswers->save();
    }

    /**
     * @param Request $request
     * @param Authorization $authorization
     * @return array|null
     * @throws UnprocessableException
     */
    //vvv Saving final result for users test score
    public function saveFinalResult(Request $request, Authorization $authorization): ?array
    {
        $this->validate = new Validate();
        $data = $request->getBody();

        //vvv Defining rules for each attribute and the rule for it
        $rules = [
            'test_id' => [$this->validate::RULE_REQUIRED],
            'user_id' => [$this->validate::RULE_REQUIRED]
        ];

        //vvv Validating the input so it is not empty
        if (!$this->validate->validate($data, $rules)) {
            throw new UnprocessableException($this->validate->getErrors());
        }

        $token = $request->getToken();
        $result = $authorization->validateToken($token);

        //vvv Validating if the user is authorized to make the request
        if (!$result || $authorization->getPayload($token)['user_id'] !== $data['user_id']) {
            throw new ForbiddenException();
        }

        $this->userAnswers = new UserAnswers();
        $this->questions = new Questions();

        //vvv Creating join query to retrieve how many answers user has answered correctly
        $correctCount = $this->userAnswers->select(['count(*)'])->
        join('', 'answers', 'user_answers', 'id', 'answer_id')
            ->where('test_id', '=', $data['test_id'])
            ->where('is_correct', '=', 1)
            ->where('user_id', '=', $data['user_id'])
            ->getQuery();

        //vvv Creating query to retrieve how many questions has in the given test
        $totalQuestions = $this->questions->select(['count(*)'])
            ->where('test_id', '=', $data['test_id'])->getQuery();

        $requestBody = [
            'user_id' => $data['user_id'],
            'test_id' => $data['test_id'],
            'correct_answers' => $correctCount[0]['count(*)'],
            'total_questions' => $totalQuestions[0]['count(*)'],
        ];

        $this->testResult = new TestResult();

        $this->testResult->loadData($requestBody);

        return $this->testResult->save();
    }
}