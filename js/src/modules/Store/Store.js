import { applyMiddleware, createStore, compose } from 'redux';
import { combineReducers } from 'redux';

// Middleware
import { createLogger } from 'redux-logger'
import thunk from "redux-thunk";
import promise from "redux-promise-middleware";

// HTTPRequest
import axios from "axios";	

import leadFormsReducer from './reducers/LeadFormsReducer';

const reducers = combineReducers({
	leadForms: leadFormsReducer,
})

//const middleware = applyMiddleware(promise(), thunk, createLogger());
const middleware = applyMiddleware(promise(), thunk);

const store = createStore(reducers, {}, middleware);
export default store;