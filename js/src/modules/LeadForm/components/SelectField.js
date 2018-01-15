import React, { Component } from 'react';

class SelectField extends Component {
	constructor(props) {
		super(props);

		this.handleChangeCheckbox = this.handleChangeCheckbox.bind(this);
	}

	handleChangeSelect(event) {
		this.props.changeFieldData(this.props.name, event.currentTarget.value);
	}

	handleChangeCheckbox(event, value) {
		this.props.changeSelectedCheckboxGroup(this.props.name, value);
	}

	render() {
		let optionsMarkup = []

		let widgetClasses = 'widget select col-xs-12';
		
		if(this.props.error) {
			widgetClasses += " hasError";
		}
		
		if(_.get(this.props.grid, "w") == 1) {
			widgetClasses += " col-sm-6";
		}

		let fieldMarkup;

		switch(this.props.selectType) {
			case "select":
				widgetClasses += " select";
				this.props.options.map((option, index) => {
					optionsMarkup.push(<option key={index} value={option.value}>{option.text}</option>);
				});
				fieldMarkup = (
					<div className="widget-inner-container">
						<select name={this.props.name} value={this.props.value} onChange={this.handleChangeSelect.bind(this)}>
							{optionsMarkup}
						</select>
					</div>
				)
			break;
			case "checkbox":
				widgetClasses += " checkbox";
				this.props.options.map((option, index) => {
					let isChecked = false;
					if(this.props.value && this.props.value.indexOf(option.value) > -1) {
						isChecked = true;
					}
					optionsMarkup.push(
						<div className="widget-option-container" key={index}>
							<input type="checkbox" name={this.props.name} value={option.value} onChange={(event) => this.handleChangeCheckbox(event, option.value)} checked={isChecked} /><label>{option.text}</label>
						</div>
					);
				});
				fieldMarkup = (
					<div className="widget-inner-container">
						{optionsMarkup}
					</div>
				)
			break;
			case "radio":
				widgetClasses += " radio";
				this.props.options.map((option, index) => {
					optionsMarkup.push(
						<div className="widget-option-container" key={index}>
							<input type="radio" name={this.props.name} value={option.value} onChange={this.handleChangeSelect.bind(this)} /><label>{option.text}</label>
						</div>
					);
				});
				fieldMarkup = (
					<div className="widget-inner-container">
						{optionsMarkup}
					</div>
				)
			break;
		}

		return (
			<div className={widgetClasses}>
				<label htmlFor={this.props.name}>
					{this.props.title}
					{
						this.props.required ?
							<span className="isRequired">*</span>
						:
						""
					}
				</label>
				{fieldMarkup}
				{ this.props.error ?
					<div className="error">{this.props.error}</div>
				:
					""
				}
				
			</div>
		)
	}
}

SelectField.defaultProps = {
  mandatory: false
};

export default SelectField;

