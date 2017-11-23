import React, { Component } from 'react';

class TextField extends Component {
	constructor(props) {
		super(props);
	}

	handleChangeText(event) {
		this.props.changeFieldData(this.props.name, event.currentTarget.value);
	}

	render() {
		let widgetClasses = 'widget text';
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
				<input type="text" name={this.props.name} value={this.props.value} onChange={this.handleChangeText.bind(this)}/>
				{ this.props.error ?
					<div className="error">{this.props.error}</div>
				:
					""
				}
				
			</div>
		)
	}
}

TextField.defaultProps = {
  mandatory: false
};

export default TextField;

