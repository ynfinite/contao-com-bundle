import React, {Component} from 'react';

import {connect} from 'react-redux';

import _ from 'lodash';

import LeadForm from './LeadForm';

import TextField from "./components/TextField";
import SelectField from "./components/SelectField";
import CheckboxGroup from "./components/CheckboxGroup";
import CheckboxField from "./components/CheckboxField";
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

	
	buildOptions(data) {
		let options = [];
		_.forEach(data, (item, index) => {
			options.push({key: index, value: item.name, text: item.name})
		})
		return options;
	}

	sendData() {
		let {formData, fields, token, target, leadType, formId} = this.props;

		var errors = {}
		let hasErrors = false;
		
		let realFieldNames = {};
		_.forEach(fields, (field, index) => {
			var fieldName = field.config.field_name;
			if(field.config.field_name.indexOf("__parent__") == 0) {
				fieldName = fieldName.replace("__parent__", "");
			}
			realFieldNames[fieldName] = field.config.name;

			if(field.config.required && !formData[field.config.field_name]){
				errors[field.config.field_name] = "Bitte füllen Sie dieses Feld aus.";
				hasErrors = true;
			}
		})

		if(hasErrors) {
			this.props.dispatch(LeadFormActions.setError(errors, this.props.appId));
		}
		else {
			this.props.dispatch(LeadFormActions.sendData(target, leadType, formData, realFieldNames, this.props.appId, formId, token));
		}
	}

	render() {		
		let {formData, errorData, sendError, send, fields} = this.props;	
		let fieldMarkup = [];
		_.forEach(fields, (field, index) => {
			let config = field.config;
			let grid = field.grid;

			let field_name = _.get(config, "field_name");
			switch (field.type) {
				case "text":
					fieldMarkup.push(<TextField 
						key={_.get(config, "name")}
						grid={grid} 
						name={field_name}
						hidden={_.get(config, "hidden")}
						required={_.get(config, "required")}
						multiline={_.get(config, "multiline")}
						title={_.get(config, "name")} 
						error={_.get(errorData, field_name)} 
						value={_.get(formData, field_name)} 
						changeFieldData={this.changeFieldData} />
					);
				break;
				case "select":
					let options = this.buildOptions(_.get(config, 'items'));

					fieldMarkup.push(<SelectField 
						grid={grid} 
						selectType={_.get(config, "selectType")}
						key={_.get(config, "name")}
						name={field_name} 
						required={_.get(config, "required")}
						title={_.get(config, 'name')} 
						options={options} 
						error={_.get(errorData, field_name)} 
						value={_.get(formData, field_name)} 
						changeFieldData={this.changeSelectData}
						changeSelectedCheckboxGroup={this.changeSelectedCheckboxGroup} />);
				break;
				case "checkbox":
					fieldMarkup.push(<CheckboxField 
						key={_.get(config, "name")}
						grid={grid}
						name={field.name}
						required={_.get(config, "required")}
						title={_.get(config, "name")}
						error={_.get(errorData, field_name)} 
						value={_.get(formData, field_name)} 
						changeSelectedCheckboxGroup={this.changeSelectedCheckboxGroup}
					/>);
				break;
			}
		})

		let description = (
			<div className="col-xs-12 widget explanation"></div>
		)

		let resultText = (
			<div className="resultTextContainer" dangerouslySetInnerHTML={{__html: this.props.message}}>
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
						<div className="col-xs-12"><p className="sendError">{sendError}</p></div>
					:
					""
				}
			</LeadForm>
		)
	}
}

const mapStateToProps = function (store, props) {
    return {
        token: _.get(store, ["leadForms", props.appId, "REQUEST_TOKEN"]),
		target: _.get(store, ["leadForms", props.appId, "target"]),
		leadType: _.get(store, ["leadForms", props.appId, "leadType"]),
		formId: _.get(store, ["leadForms", props.appId, "formId"]),
        formData: _.get(store, ["leadForms", props.appId, "data"]),
        fields: _.get(store, ["leadForms", props.appId, "fields"]),
        errorData: _.get(store, ["leadForms", props.appId, "errors"]),
		sendError: _.get(store, ["leadForms", props.appId, "sendError"]),
		send: _.get(store, ["leadForms", props.appId, "send"]),
		message: _.get(store, ["leadForms", props.appId, "message"])
    }
}

export default connect(mapStateToProps)(LeadFormWrapper);