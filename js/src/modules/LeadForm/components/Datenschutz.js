import React, { Component } from 'react';

class Datenschutz extends Component {
	constructor(props) {
		super(props);

		this.handleChange = this.handleChange.bind(this);
	}

	handleChange(event) {
		this.props.changeFieldData('datenschutz', event.target.checked);
	}

	render() {
		let widgetClasses = 'widget checkboxContainer';
		if (this.props.error) {
			widgetClasses += ' hasError';
		}

		return (
			<div className={widgetClasses}>
				<div className="checkbox">
					{this.props.checked ? (
						<input
							type="checkbox"
							value="Datenschutz angenommen"
							name="datenschutz"
							onChange={this.handleChange}
							checked
						/>
					) : (
						<input
							type="checkbox"
							value="Datenschutz angenommen"
							name="datenschutz"
							onChange={this.handleChange}
						/>
					)}
					<label htmlFor="datenschutz">
						Hiermit bestätigen Sie die Übermittlung Ihrer Daten.
						{this.props.mandatory ? <span className="isMandatory">*</span> : ''}
					</label>
				</div>
				{this.props.error ? <div className="error">{this.props.error}</div> : ''}
			</div>
		);
	}
}

export default Datenschutz;
