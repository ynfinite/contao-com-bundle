import axios from "axios";


export const initStore = (appId, token, target, fields, leadType) => {
    return {
        type: 'INIT_STORE',
        payload: {
            appId: appId,
            token: token,
            target: target,
            fields: fields,
            leadType: leadType
        }
    }
}

export const changeCheckboxGroupData = (fieldName, value, appId) => {
    return {
        type: 'CHANGE_DATA_CHECKBOXGROUP',
        payload: {
            appId: appId,
            fieldName: fieldName,
            value: value
        }
    }
}

export const changeData = (fieldName, value, appId) => {
    return {
        type: 'CHANGE_DATA',
        payload: {
            appId: appId,
            fieldName: fieldName,
            value: value
        }
    }
}

export const sendData = (target, leadType, data, appId, token) => {
     let payload = {
        data: data, 
        REQUEST_TOKEN: token, 
        appId: appId,
        leadType: leadType
    };

    return {
        type: 'SEND_FORM',
        payload: axios.post(target, payload).catch(error => {
            console.log(error);
        })
    }
}

export const setError = (errors, appId) => {
    return {
        type: "SET_ERROR",
        payload: {
            appId: appId,
            errors: errors
        }
    }
}