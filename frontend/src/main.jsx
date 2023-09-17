import React from "react";
import ReactDOM from "react-dom/client";
import "./styles/main.scss";
import { BrowserRouter as Router } from "react-router-dom";
import Routers from "./Router.jsx";

ReactDOM.createRoot(document.getElementById("root")).render(
  <Router>
    <Routers />
  </Router>
);
