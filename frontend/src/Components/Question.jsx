import React, { PureComponent } from "react";
import { withRouter } from "react-router-dom";
import axiosClient from "../Util/axios-client";
import "../styles/Question.scss";

class Question extends PureComponent {
  state = {
    answers: null,
    selectedAnswer: null,
  };

  componentDidMount() {
    const {
      questionData: { id },
    } = this.props;

    const url = `/answer-data/${id}`;

    axiosClient
      .get(url)
      .then(({ data }) => {
        this.setState({ answers: data, selectedAnswer: null });
      })
      .catch((error) => {
        const { response } = error;
        console.log(response);
      });
  }

  componentDidUpdate(prevProps) {
    if (this.props.currentQuestion !== prevProps.currentQuestion) {
      this.setState({ selectedAnswer: null });

      const {
        questionData: { id },
      } = this.props;

      const url = `/answer-data/${id}`;

      axiosClient
        .get(url)
        .then(({ data }) => {
          this.setState({ answers: data });
        })
        .catch((error) => {
          console.log(error);
        });
    }
  }

  onclick(value) {
    this.setState({ selectedAnswer: value });
  }

  renderAnswers() {
    const { answers, selectedAnswer } = this.state;

    if (!answers) {
      return null;
    }

    return answers.map((item) => {
      const isSelected = selectedAnswer === item.id ? "_isSelected" : "";
      return (
        <button
          className={`Button Button${isSelected}`}
          onClick={() => this.onclick(item.id)}
          key={item.id}
        >
          {item.answer}
        </button>
      );
    });
  }

  renderNext() {
    const { selectedAnswer } = this.state;

    const { onNextQuestion } = this.props;

    if (!selectedAnswer) {
      return null;
    }

    return (
      <>
        <button onClick={() => onNextQuestion(selectedAnswer)}>Nākamais</button>
      </>
    );
  }


  render() {
    const {
      questionData: { question },
      renderProgressBar,
    } = this.props;

    return (
      <div className="Question">
        <h1 className="Question-Title Title">{question}</h1>
        <div className="Question-ButtonWrapper">{this.renderAnswers()}</div>
        {renderProgressBar}
        <div className="Question-NextButtonWrapper">{this.renderNext()}</div>
      </div>
    );
  }
}

export default withRouter(Question);
