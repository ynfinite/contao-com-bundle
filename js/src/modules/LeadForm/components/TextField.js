import React, { Component } from 'react';

import _ from "lodash";

class TextField extends Component {
	constructor(props) {
		super(props);
	}

	handleChangeText(event) {
		this.props.changeFieldData(this.props.name, event.currentTarget.value);
	}

	render() {
		let widgetClasses = 'widget text col-xs-12';
		let fieldMarkup = "";
		let errorMarkup = "";
		let labelMarkup = "";
		let isRequired = "";
		let finalOutput = "";
		
		if(this.props.error) {
			widgetClasses += " hasError";
		}
		
		if(_.get(this.props.grid, "w") == 1) {
			widgetClasses += " col-sm-6";
		}
		
		let title = this.props.title;
		if(this.props.required) {
			isRequired = <span className="isRequired">*</span>;
		}

		if(this.props.hidden !== true) {
			labelMarkup = <label htmlFor={this.props.name}>{title}{isRequired}</label>	
		}			

		if(this.props.hidden) {
			fieldMarkup = <input type="hidden" name={this.props.name} value={this.props.value} onChange={this.handleChangeText.bind(this)}/>
		}
		else {
			if(this.props.multiline) {
				fieldMarkup = <textarea name={this.props.name} onChange={this.handleChangeText.bind(this)}>{this.props.value}</textarea>
			}
			else {
				fieldMarkup = <input type="text" name={this.props.name} value={this.props.value} onChange={this.handleChangeText.bind(this)}/>
			}			
		}

		if(this.props.error) {
			errorMarkup = <div className="error">{this.props.error}</div>
		}

		if(this.props.hidden !== true) {
			finalOutput = (
				<div className={widgetClasses}>
					{ labelMarkup }
					{ fieldMarkup }
					{ errorMarkup }
				</div>
			)
		}
		else {
			finalOutput = <div>{fieldMarkup}</div>
		}

		return (
			finalOutput
		)
	}
}

TextField.defaultProps = {
  mandatory: false
};

export default TextField;

