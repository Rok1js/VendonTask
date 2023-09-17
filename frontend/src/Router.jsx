import { BrowserRouter as Router, Route, Switch, Link } from "react-router-dom";
import Home from "./Routes/Home";
import NotFound from "./Routes/NotFound";
import EndScreen from "./Routes/EndScreen";
import Questions from "./Routes/Questions";

function Routers() {
  return (
    <Switch>
      <Route path="/" render={ (props) => <Home {...props} /> } exact />
      <Route path="/questions" render={ (props) => <Questions {...props} /> }  />
      <Route path="/finished" render={ (props) => <EndScreen {...props} /> }  />
      <Route path="*" render={ (props) => <NotFound {...props} /> }  />
    </Switch>
  );
}

export default Routers;
