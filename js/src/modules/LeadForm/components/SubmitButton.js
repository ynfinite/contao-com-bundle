import React, { Component } from 'react';

class SubmitButton extends Component {
	constructor(props) {
		super(props);
	}

	render() {
		return (
			<div className="widget submit">
				<label htmlFor={this.props.name}>{this.props.title}</label>
				<input type="submit" onClick={this.props.sendData} value="Absenden" />
			</div>
		)
	}
}

export default SubmitButton;