require("./scss/main.scss");

require('smoothscroll-polyfill').polyfill();

import 'babel-polyfill';
import 'nodelist-foreach-polyfill';

import React from 'react';
import ReactDOM from 'react-dom';

import LeadFormApp from './modules/LeadForm/LeadFormApp';

let ynfiniteContainer = document.querySelectorAll('[data-ynfinite-component]');

if(ynfiniteContainer) {
	ynfiniteContainer.forEach((container) => {
		
		var appId = container.dataset.ynfiniteComponent;
		var token = container.dataset.token;
		var target = container.dataset.target;
		var leadType = container.dataset.leadtype;
		var fields = JSON.parse(container.dataset.fields);
		var formId = container.dataset.formid;
		
		ReactDOM.render(
		<LeadFormApp appId={appId} formId={formId} token={token} leadType={leadType} target={target} fields={fields} />,
			container
		);
	})
  
}
