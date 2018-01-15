import _ from 'lodash';

const init = {
	data: {},
	fields: {},
	errors: {},
	message: "",
	data: {},
	leadType: "",
	target: "",
	send: false,
	sendError: ""
}

const LeadFormsReducer = (state = {}, action) => {
	switch (action.type) {
		case "INIT_STORE":
			var newState = _.cloneDeep(state);
			
			let fieldData = [];
			let initData = {};
			for (var property in action.payload.fields) {
			    
			    var field_name = action.payload.fields[property].config.field_name;

			    if(window.sessionStorage[field_name]) {
			    	initData[field_name] = window.sessionStorage[field_name];
			    }

			    if (action.payload.fields.hasOwnProperty(property)) {
			 		fieldData.push(action.payload.fields[property]);
			    }
			}

			let initialData = {
				...init, 
				REQUEST_TOKEN: action.payload.token, 
				formId: action.payload.formId,
				data: initData,
				target: action.payload.target, 
				fields: fieldData,
				leadType: action.payload.leadType
			}
		
			var newstate = _.set(newState, action.payload.appId, initialData);

			return newstate;
			break;
		case "CHANGE_DATA":
			var newState = _.cloneDeep(state);
			
			newState = _.set(newState, [action.payload.appId, "data", action.payload.fieldName], action.payload.value);
			return newState;
			
			break;
		break;
		case "CHANGE_DATA_CHECKBOXGROUP":
			var newState = _.cloneDeep(state);

			var data = _.get(state, [action.payload.appId, "data", action.payload.fieldName]);
			
			if(!data) {
				data = [action.payload.value]
			}
			else {
				let target = data.indexOf(action.payload.value);
				if(target > -1) {
					data.splice(target, 1);
				}
				else {
					data.push(action.payload.value);
				}
			}
			
			newState = _.set(newState, [action.payload.appId, "data", action.payload.fieldName], data)
			return newState;

			break;
		case "SET_ERROR":
			var newState = _.cloneDeep(state);
			
			newState = _.set(newState, [action.payload.appId, "errors"], action.payload.errors)
			return newState;
			
			break;
		case "SEND_FORM_FULFILLED":
			var newState = _.cloneDeep(state);

			console.log("RETURNING PAYLOAD", action.payload);
			var payloadData = action.payload;
			if(payloadData.data && payloadData.data.success) {
				if(payloadData.data.status == 201 || payloadData.data.status == 200 || payloadData.data.mailSuccess == true) {
					newState = _.set(newState, [payloadData.data.appId, "send"], true);
					newState = _.set(newState, [payloadData.data.appId, "message"], payloadData.data.message)
					return newState;	
				}
				newState = _.set(newState, [payloadData.appId, "sendError"], payloadData.data.error);
				return newState;
			}
			else {
				newState = _.set(newState, [payloadData.appId, "sendError"], payloadData.error);
				return newState;
			}
			break;
		default: 

			return state;
	}
}

export default LeadFormsReducer;