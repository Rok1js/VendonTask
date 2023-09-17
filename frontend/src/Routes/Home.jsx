import React, { createRef, PureComponent } from "react";
import axiosClient from "../Util/axios-client";
import { withRouter } from "react-router-dom";
import { saveToken } from "../Util/Token";
import "../styles/Home.scss";

class Home extends PureComponent {
  constructor() {
    super();
    
    this.state = {
      selectedTest: null,
      name: null,
      tests: {},
      inputError: null,
      selectError: null,
    };

    this.nameRef = createRef();
  }

  componentDidMount() {
    axiosClient
      .get("/")
      .then(({ data }) => {
        this.setState({ tests: data });
      })
      .catch((error) => {
        console.log(error);
      });
  }

  changeSelectedTest = (ev) => {
    ev.preventDefault();
    this.setState({ selectedTest: parseInt(ev.target.value) });
  };

  onChange = (ev) => {
    ev.preventDefault();
    this.setState({ name: this.nameRef.current.value });
  };

  renderOptions() {
    const { tests } = this.state;

    if (!tests) {
      return null;
    }

    return Object.keys(tests).map((key) => (
      <option key={tests[key].id} value={tests[key].id}>
        {tests[key].title}
      </option>
    ));
  }

  renderInput() {
    const { inputError } = this.state;

    const isError = inputError ? "_isError" : "";

    return (
      <div className="Home-Input">
        <input
          ref={this.nameRef}
          type="text"
          placeholder="Ievadi savu vārdu"
          onChange={this.onChange}
          name="name"
          id="name"
        />
        <br></br>
        <label htmlFor="name" className={`Home-Label${isError}`}>
          You need to provide your name
        </label>
      </div>
    );
  }

  renderTestDropdown() {
    const { selectedTest, selectError } = this.state;

    const isError = selectError ? "_isError" : "";

    return (
      <>
        <select
          className="Home-Dropdown"
          name="dropdown"
          id="dropdown"
          onChange={this.changeSelectedTest}
          value={selectedTest}
        >
          <option disabled selected value>
            -- select an option --
          </option>
          {this.renderOptions()}
        </select>
        <br></br>
        <label htmlFor="dropdown" className={`Home-Label${isError}`}>
          You need to select test
        </label>
      </>
    );
  }

  onSubmit = (e) => {
    e.preventDefault();
    const { selectedTest } = this.state;
    const name = this.nameRef.current.value;

    const { history } = this.props;

    if (selectedTest && name) {
      const payload = {
        test_id: selectedTest,
        name: name,
      };

      axiosClient
        .post("/save-user-data", payload)
        .then(({ data }) => {
          const { id, token, name } = data;
          saveToken(token);
          history.push("/questions", {
            user: { id, name },
            testId: selectedTest,
          });
        })
        .catch((error) => {
          console.log(error);
        });
    }

    if (!selectedTest) {
      this.setState({ selectError: true });
    } else {
      this.setState({ selectError: false });
    }

    if (!name) {
      this.setState({ inputError: true });
    } else {
      this.setState({ inputError: false });
    }
  };

  render() {
    return (
      <div className="Home">
        <h1 className="Home-Title">Testa uzdevums</h1>
        {this.renderInput()}
        <div className="Home-Select">{this.renderTestDropdown()}</div>
        <div className="Home-Button">
          <button onClick={this.onSubmit}>Sākt</button>
        </div>
      </div>
    );
  }
}

export default withRouter(Home);
