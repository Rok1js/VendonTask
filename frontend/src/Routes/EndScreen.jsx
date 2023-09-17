import React, { PureComponent } from "react";
import axiosClient from "../Util/axios-client";
import { withRouter } from "react-router-dom";
import "../styles/EndScreen.scss";

class EndScreen extends PureComponent {
  state = {
    resultData: {},
    user: {},
  };

  componentDidMount() {
    const {
      location: {
        state: {
          testId,
          user,
          user: { id: userID },
        },
      },
    } = this.props;

    this.setState({ user: user });

    const payload = {
      test_id: testId,
      user_id: userID,
    };

    axiosClient
      .post("/save-final-result", payload)
      .then(({ data }) => {
        this.setState({ resultData: data });
      })
      .catch((error) => {
        console.log(error);
      });
  }

  render() {
    const {
      user: { name },
      resultData:{
        correct_answers,
        total_questions
      }
    } = this.state;
    return (
      <div className="EndScreen">
        <h1 className="EndScreen-Title Title">Paldies, {name}!</h1>
        <h3>Tu atbildēji pareizi uz {correct_answers} no {total_questions} jautājumiem.</h3>
      </div>
    );
  }
}

export default withRouter(EndScreen);
