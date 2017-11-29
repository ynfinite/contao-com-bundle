import React, { Component } from 'react';

import immutable from 'immutable';

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
						<div className="row">
							{this.props.description}
							{this.props.children}
							<SubmitButton sendData={this.props.sendData} />
						</div>
					</div>
					<div className={resultClasses}>
						{this.props.resultText}
					</div>
				</div>
			</div>
		)
	}
}

export default LeadForm;