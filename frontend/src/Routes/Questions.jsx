import React, { PureComponent } from "react";
import { withRouter } from "react-router-dom";
import axiosClient from "../Util/axios-client";
import Question from "../Components/Question";
import "../styles/Questions.scss";

class Questions extends PureComponent {
  constructor() {
    super();

    this.state = {
      questions: null,
      questionCount: null,
      currentQuestion: 1,
    };

    this.onNextQuestion = this.onNextQuestion.bind(this);
  }

  componentDidMount() {
    const {
      location: {
        state: { testId, user },
      },
    } = this.props;

    this.setState({ testId: testId, user: user });

    const url = `/questions-data/${testId}`;

    axiosClient
      .get(url)
      .then(({ data }) => {
        this.setState({ questions: data, questionCount: data.length });
      })
      .catch((error) => {
        console.log(error);
      });
  }

  onNextQuestion(answerID) {
    const {
      testId,
      user: { id: userId },
      currentQuestion,
      questions,
    } = this.state;

    const currentQuestionID = questions[currentQuestion - 1].id;

    const payload = {
      test_id: testId,
      user_id: userId,
      question_id: currentQuestionID,
      answer_id: answerID,
    };

    axiosClient
      .post("/save-user-answer", payload)
      .then(({ data }) => {
        this.setState({ currentQuestion: currentQuestion + 1 });
      })
      .catch((error) => {
        console.log(error);
      });
  }

  renderQuestion() {
    const { questions, currentQuestion, questionCount, user, testId } = this.state;

    if (!questions) {
      return null;
    }

    if (currentQuestion === questionCount + 1) {
      const { history } = this.props;
      history.push("/finished", { user: user, testId: testId });
      return null;
    }

    const currentQuestionItem = questions[currentQuestion - 1];

    return (
      <Question
        currentQuestion={currentQuestion}
        questionData={currentQuestionItem}
        onNextQuestion={this.onNextQuestion}
        renderProgressBar={this.renderProgressBar()}
      />
    );
  }

  renderProgressBar() {
    const { currentQuestion, questionCount } = this.state;
    const progress = (currentQuestion / questionCount) * 100 + "%";

    return (
      <div className="Questions-ProgressBarWrapper">
        <div className="Questions-ProgressBar" style={{ width: progress }} />
      </div>
    );
  }

  render() {
    return <div className="Questions">{this.renderQuestion()}</div>;
  }
}

export default withRouter(Questions);
