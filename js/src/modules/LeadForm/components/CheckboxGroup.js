import React, { Component } from 'react';

class CheckboxGroup extends Component {
	constructor(props) {
		super(props);

		this.handleChange = this.handleChange.bind(this);
	}

	handleChange(event) {
		this.props.changeFieldData(this.props.name, event.currentTarget.value);
	}

	render() {
		let optionsMarkup = []
		this.props.options.map((option, index) => {
			optionsMarkup.push(
				<div key={index} className="checkbox">
					{this.props.values && this.props.values.indexOf(option.value) > -1 ?
						<input type="checkbox" value={option.value} name={this.props.name+"_"+option.name} onChange={this.handleChange} checked />
					:
						<input type="checkbox" value={option.value} name={this.props.name+"_"+option.name} onChange={this.handleChange} />
					}
					<label htmlFor="datenschutz">
						{option.value}
					</label>
				</div>
			);
		});

		let widgetClasses = 'widget checkbox-group-container';
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
				<div className="checkbox-group">
					{
						optionsMarkup.map((option) => {
							return option;
						})
					}
				</div>
				{ this.props.error ?
					<div className="error">{this.props.error}</div>
				:
					""
				}
				
			</div>
		)
	}
}

CheckboxGroup.defaultProps = {
  mandatory: false
};

export default CheckboxGroup;

