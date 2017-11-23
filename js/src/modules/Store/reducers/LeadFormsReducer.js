import immutable from 'immutable';

const init = {
	data: {},
	fields: {},
	errors: {},
	leadType: "",
	target: "",
	send: false,
	sendError: ""
}

const LeadFormsReducer = (state = immutable.Map({}), action) => {
	switch (action.type) {
		case "INIT_STORE":
			var newstate = state.updateIn([action.payload.appId], (data) => {
				let fieldData = [];
				for (var property in action.payload.fields) {
				    if (action.payload.fields.hasOwnProperty(property)) {
				 		fieldData.push(action.payload.fields[property]);
				    }
				}
				let initialData = {
					...init, 
					REQUEST_TOKEN: action.payload.token, 
					target: action.payload.target, 
					fields: fieldData,
					leadType: action.payload.leadType
				}
				return immutable.fromJS(initialData);
			})
			return newstate;
			break;
		case "CHANGE_DATA":
			var newState = state.updateIn([action.payload.appId, "data", action.payload.fieldName], data => action.payload.value);
			return newState;
		break;
		case "CHANGE_DATA_CHECKBOXGROUP":
			var newState = state.updateIn([action.payload.appId, "data", action.payload.fieldName], (data) =>{
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
				return data;
			})
			return newState;
			break;
		case "SET_ERROR":
			var newState = state.updateIn([action.payload.appId, "errors"], errors => {
				return {...action.payload.errors};
			})
			return 	newState;
		case "SEND_FORM_FULFILLED":
			var payloadData = action.payload;
			console.log("Returned Payload: ", payloadData.status, " and ", payloadData.data.status);
			if(payloadData.status == 200 && payloadData.data.status == 201) {
				var newState = state.updateIn([payloadData.data.appId, "send"], send => true);
				return newState;	
			}
			else {
				var newState = state.updateIn([payloadData.data.appId, "sendError"], sendError => payloadData.error);
				return newState;
			}
		default: 

			return state;
	}
}

export default LeadFormsReducer;