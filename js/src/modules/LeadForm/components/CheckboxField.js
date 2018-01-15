import React, { Component } from 'react';

class CheckboxField extends Component {
	constructor(props) {
		super(props);

		this.handleChangeCheckbox = this.handleChangeCheckbox.bind(this);
	}

	handleChangeCheckbox(event, value) {
		this.props.changeSelectedCheckboxGroup(this.props.name, value);
	}

	render() {
		let widgetClasses = 'widget checkbox col-xs-12';
		let isRequired = "";
		
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

		return (
			<div className={widgetClasses}>
				<div className="widget-inner-container">
					<input type="checkbox" name={this.props.name} value={this.props.value} onChange={(event) => this.handleChangeCheckbox(event, this.props.value)} checked={this.props.isChecked} />
					<label htmlFor={this.props.name}>{title}{isRequired}</label>
				</div>
				{this.props.error ?
					<div className="error">{this.props.error}</div>
				:
					""
				}
			</div>
		)
	}
}

export default CheckboxField;