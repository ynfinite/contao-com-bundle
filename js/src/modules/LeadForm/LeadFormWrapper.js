import React, {Component} from 'react';

import {connect} from 'react-redux';

import LeadForm from './LeadForm';

import TextField from "./components/TextField";
import SelectField from "./components/SelectField";
import CheckboxGroup from "./components/CheckboxGroup";
import Datenschutz from "./components/Datenschutz";
import Newsletter from "./components/Newsletter";

// Actions
import * as LeadFormActions from '../store/actions/LeadFormActions';

class LeadFormWrapper extends Component {

	constructor(props) {
		super(props);

		this.sendData = this.sendData.bind(this);
		this.changeSelectData = this.changeSelectData.bind(this);
		this.changeFieldData = this.changeFieldData.bind(this);
		this.changeSelectedCheckboxGroup = this.changeSelectedCheckboxGroup.bind(this);
	}

	changeSelectedCheckboxGroup(fieldName, value) {
		this.props.dispatch(LeadFormActions.changeCheckboxGroupData(fieldName, value, this.props.appId))
	}

	changeSelectData(fieldName, value) {
		this.props.dispatch(LeadFormActions.changeData(fieldName, value, this.props.appId));
	}

	changeFieldData(fieldName, value) {
		this.props.dispatch(LeadFormActions.changeData(fieldName, value, this.props.appId));
	}

	sendData() {
		var formData = this.props.leadForms.getIn([this.props.appId, "data"]);

		if(formData && formData.size > 0) {
			formData = formData.toJS();
		}
		else {
			formData = {}
		}

		var errors = {}
		
		if(errors.size > 0) {
			this.props.dispatch(LeadFormActions.setError(errors, this.props.appId));
		}
		else {
			var token = this.props.leadForms.getIn([this.props.appId, "REQUEST_TOKEN"]);
			var target = this.props.leadForms.getIn([this.props.appId, "target"]);
			var leadType = this.props.leadForms.getIn([this.props.appId, "leadType"]);
			console.log("Sending data to ", target, " with ", token, " and ", leadType, " the data is ", this.props.leadForms.getIn([this.props.appId, "data"]));
			this.props.dispatch(LeadFormActions.sendData(target, leadType, this.props.leadForms.getIn([this.props.appId, "data"]), this.props.appId, token));
		}
	}

	render() {
		let errorData = this.props.leadForms.getIn([this.props.appId, "errors"]) || {};
		let formData = this.props.leadForms.getIn([this.props.appId, "data"]) || {};
		let sendError = this.props.leadForms.getIn([this.props.appId, "sendError"]) || "";
		let send = this.props.leadForms.getIn([this.props.appId, "send"]) || false;

		let fields = this.props.leadForms.getIn([this.props.appId, "fields"]) || [];

		let fieldMarkup = [];
		fields.map((field, index) => {
			let config = field.get('config');
			switch (field.get('type')) {
				case "text":
					fieldMarkup.push(<TextField key={index} name={config.get('field_name')} title={config.get('name')} error={errorData[config.get('field_name')]} value={formData[config.get('field_name')]} changeFieldData={this.changeFieldData} />);
				break;
				case "checkbox_soon":
					fieldMarkup.push(<CheckboxGroup key={index} name="city" title="Ich interessiere mich für folgende Orange Card" options={cityOptions} mandatory={true} error={errorData.city} value={formData.city} changeFieldData={this.changeSelectedCheckboxGroup} />);
				break;
				case "select_soonwe":
					fieldMarkup.push(<SelectField key={index} name="amount" title="Bitte senden Sie mir" mandatory={true} options={amountOptions} error={errorData.amount} value={formData.amount} changeFieldData={this.changeSelectData} />);
				break;
			}
		})

		let description = (
			<div className="widget explanation"></div>
		)

		let resultText = (
			<div className="resultTextContainer">
				<p className="looksLikeH1">
					Vielen Dank!
				</p>
				<p className="looksLikeH2">
					Wir haben Ihre Anfrage erhalten die {formData.amount} Orange Card/s sind bald auf dem Weg zu Ihnen!
				</p>
			</div>
		)



		return (
			<LeadForm 
				appId={this.props.appId}
				description={description}
				resultText={resultText}
				sendData={this.sendData}
				send={send}
			>
				{fieldMarkup.map((field) => {
					return field;
				})}

				{ 
					sendError ?
						<div className="sendError"><p>{sendError}</p></div>
					:
					""
				}
			</LeadForm>
		)
	}
}

const mapStateToProps = function (store) {
    return {
        leadForms: store.get('leadForms'),
    }
}

export default connect(mapStateToProps)(LeadFormWrapper);