import React, { Component } from 'react';

class SelectField extends Component {
	constructor(props) {
		super(props);
	}

	handleChangeSelect(event) {
		this.props.changeFieldData(this.props.name, event.currentTarget.value);
	}

	render() {
		let optionsMarkup = []
		this.props.options.map((option, index) => {
			optionsMarkup.push(<option key={index} value={option.value}>{option.name}</option>);
		});

		let widgetClasses = 'widget select';
		if(this.props.error) {
			widgetClasses += " hasError";
		}

		return (
			<div className={widgetClasses}>
				<label htmlFor={this.props.name}>
					{this.props.title}
					{
						this.props.mandatory ?
							<span className="isMandatory">*</span>
						:
						""
					}
				</label>
				<select name={this.props.name} value={this.props.value} onChange={this.handleChangeSelect.bind(this)}>
					{optionsMarkup.map((option) => {
						return option;
					})}
				</select>
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

