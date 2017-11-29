import React, {Component} from 'react';

import {connect} from 'react-redux';

import _ from 'lodash';

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
		
		let realFieldNames = {};
		_.forEach(fields, (field, index) => {
			var fieldName = field.config.field_name;
			if(field.config.field_name.indexOf("__parent__") == 0) {
				fieldName = fieldName.replace("__parent__", "");
			}
			realFieldNames[fieldName] = field.config.name;
		})

		if(errors.size > 0) {
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
						key={index} 
						grid={grid} 
						name={field_name} 
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
						key={index} 
						name={field_name} 
						title={_.get(config, 'name')} 
						options={options} 
						error={_.get(errorData, field_name)} 
						value={_.get(formData, field_name)} 
						changeFieldData={this.changeSelectData}
						changeSelectedCheckboxGroup={this.changeSelectedCheckboxGroup} />);
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