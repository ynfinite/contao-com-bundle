import React, { Component } from 'react';

class Newsletter extends Component {
	constructor(props) {
		super(props);

		this.handleChange = this.handleChange.bind(this);
	}

	handleChange(event) {
		this.props.changeFieldData("newsletter", event.target.checked);
	}

	render() {
		return (
			<div>
				<div className="widget checkbox">
					{this.props.checked ?
						<input type="checkbox" value="Newsletter anmelden" name="newsletter" onChange={this.handleChange} checked />
					:
						<input type="checkbox" value="Newsletter anmelden" name="newsletter" onChange={this.handleChange} />
					}
					<label htmlFor="newsletter">Ich möchte die aktuellen Bauvorhaben als einer der Ersten per Newsletter erhalten.</label>
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

export default Newsletter;