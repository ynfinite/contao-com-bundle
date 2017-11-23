import React, { Component } from 'react';

import { connect } from 'react-redux';
import immutable from 'immutable';
import axios from "axios";

import SubmitButton from "./components/SubmitButton";

class LeadForm extends Component {
	constructor(props) {
		super(props)
	}

	render() {
		
		var formClasses = "form";
		if(this.props.send == true) {
			formClasses += " hidden";
		}

		var resultClasses = "result";
		if(this.props.send == false) {
			resultClasses += " hidden";
		}

		return (
			<div className="lead-form-container">
				<div className="lead-form-container-inner">
					<div className={formClasses}>
						{this.props.description}
						{this.props.children}
						<SubmitButton sendData={this.props.sendData} />
					</div>
					<div className={resultClasses}>
						{this.props.resultText}
					</div>
				</div>
			</div>
		)
	}
}

const mapStateToProps = function(store) {
	return {
		leadForms: store.get("leadForms")
	}
}

export default connect(mapStateToProps)(LeadForm);