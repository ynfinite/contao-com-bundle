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
		
		if(this.props.error) {
			widgetClasses += " hasError";
		}
		
		if(_.get(this.props.grid, "w") == 1) {
			widgetClasses += " col-sm-6";
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
				{this.props.multiline ?
					<textarea name={this.props.name} onChange={this.handleChangeText.bind(this)}>
						{this.props.value}
					</textarea>
				:
					<input type="text" name={this.props.name} value={this.props.value} onChange={this.handleChangeText.bind(this)}/>
				}

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

