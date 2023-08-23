import axios from 'axios'

export const isOnline = () => {
    return window.navigator.onLine;
}


export const getUrlLogin = () => {
    let domain = window.location.hostname != 'localhost' ? window.location.hostname : '';
    document.cookie = 'message=Vui lòng đăng nhập; expires=' + new Date(Date.now() + 60000) + '; path=/; domain='+ domain;
    let href = location.href;
    window.location.href = '/admin/login' + '?redirect=' + btoa(href);
}
export const error403 = (error) => {
    let domain = window.location.hostname != 'localhost' ? window.location.hostname : '';
    document.cookie = 'message=' + error.response.data.message +'; expires=' + new Date(Date.now() + 60000) + '; path=/; domain='+ domain;
    window.location.href = '/admin/login';

}

export const apiGet = async (url, data={}) => {
    if(!isOnline()){
        throw {
            data:{
                message: 'Vui lòng kiểm tra đường truyền của bạn'
            }
        };
    }
    if(typeof(data) !== 'object'){
        throw {
            data:{
                message: 'Tham số thứ 2 chỉ nhận 1 object'
            }
        };
    }
    return axios.get(url, {
        params:data
    }).then(response=>{
        return response.data;
    }).catch(error=>{
        if(!error.response){
            console.warning('Hệ thống đang bảo trì, Vui lòng thử lại sau')
            getUrlLogin();
        }else if(error.response.status === 401){
            getUrlLogin();
        }else if(error.response.status === 500){
            console.error('Lỗi hệ thống, Vui lòng thử lại sau');
        }else if(error.response.status === 403){
            error403(error)
        }else{
            throw error.response;
        }
    })
}

export const apiPost = async (url, data, params = {}) => {
    if(!isOnline()){
        throw {
            data:{
                message: 'Vui lòng kiểm tra đường truyền của bạn'
            }
        };
    }
    if(typeof(params) !== 'object'){
        throw {
            data:{
                message: 'Tham số thứ 3 chỉ nhận 1 object'
            }
        };
    }
    return axios.post(url, data, {
        params: params
    }).then(res => {
        return res.data;
    }).catch(error => {
        if(!error.response){
            console.warning('Hệ thống đang bảo trì, Vui lòng thử lại sau')
            getUrlLogin();
        }else if(error.response.status === 401){
            getUrlLogin();
        }else if(error.response.status === 500){
            console.error('Lỗi hệ thống, Vui lòng thử lại sau');
            throw error.response;
        }else if(error.response.status === 403){
            error403(error)
        }else{
            throw error.response;
        }
    });
}

export const apiPut = async (url, data, params = {}) => {
    if(!isOnline()){
        throw {
            data:{
                message: 'Vui lòng kiểm tra đường truyền của bạn'
            }
        };
    }
    if(typeof(params) !== 'object'){
        throw {
            data:{
                message: 'Tham số thứ 3 chỉ nhận 1 object'
            }
        };
    }
    return axios.put(url, data, {
        params: params
    }).then(res => {
        return res.data;
    }).catch(error => {
        if(!error.response){
            console.warning('Hệ thống đang bảo trì, Vui lòng thử lại sau')
            getUrlLogin();
        }else if(error.response.status === 401){
            getUrlLogin();
        }else if(error.response.status === 500){
            console.error('Lỗi hệ thống, Vui lòng thử lại sau');
        }else if(error.response.status === 403){
            error403(error)
        }else{
            throw error.response;
        }
    });
}

export const apiPatch = async (url, data, params = {}) => {
    if(!isOnline()){
        throw {
            data:{
                message: 'Vui lòng kiểm tra đường truyền của bạn'
            }
        };
    }
    if(typeof(params) !== 'object'){
        throw {
            data:{
                message: 'Tham số thứ 3 chỉ nhận 1 object'
            }
        };
    }
    return axios.patch(url, data, {
        params: params
    }).then(res => {
        return res.data;
    }).catch(error => {
        if(!error.response){
            console.warning('Hệ thống đang bảo trì, Vui lòng thử lại sau')
            getUrlLogin();
        }else if(error.response.status === 401){
            getUrlLogin();
        }else if(error.response.status === 500){
            console.error('Lỗi hệ thống, Vui lòng thử lại sau');
        }else if(error.response.status === 403){
            error403(error)
        }else{
            throw error.response;
        }
    });
}

export const apiDelete = async (url, data = {}) => {
    if(!isOnline()){
        throw {
            data:{
                message: 'Vui lòng kiểm tra đường truyền của bạn'
            }
        };
    }
    if(typeof(data) !== 'object'){
        throw {
            data:{
                message: 'Tham số thứ 2 chỉ nhận 1 object'
            }
        };
    }
    return axios.delete(url, data).then(res => {
        return res;
    }).catch(error => {
        if(!error.response){
            console.warning('Hệ thống đang bảo trì, Vui lòng thử lại sau')
            getUrlLogin();
        }else if(error.response.status === 401){
            getUrlLogin();
        }else if(error.response.status === 500){
            console.error('Lỗi hệ thống, Vui lòng thử lại sau');
        }else if(error.response.status === 403){
            error403(error)
        }else{
            throw error.response;
        }
    });
}
