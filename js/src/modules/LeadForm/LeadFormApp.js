import React, {Component} from 'react';
import ReactDOM from 'react-dom';

import store from "../Store/Store";

import { Provider } from 'react-redux';

import LeadFormWrapper from './LeadFormWrapper';
import { initStore } from '../store/actions/LeadFormActions';


export default class LeadFormApp extends Component {

	constructor(props) {
		super(props);
	}

	componentWillMount() {
		store.dispatch(initStore(this.props.appId, this.props.token, this.props.target, this.props.fields, this.props.leadType))
	}

	render() {
		return (
			<Provider store={store}>
				<LeadFormWrapper appId={this.props.appId} />
			</Provider>
		)
	}
}

