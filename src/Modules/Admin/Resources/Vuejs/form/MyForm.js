import Errors from './Errors';

export class MyForm {
    /**
     * Create a new Form instance.
     *
     * @param {object} data
     */
    constructor(data) {
        this.originalData = data;
        for (let field in data) {
            this[field] = data[field];
        }
        this.myerrors = new Errors();
        this.errors = {};
        this.message = null;
        this.type = null;
    }


    /**
     * Fetch all relevant data for the form.
     */
    data() {
        let data = {};

        for (let property in this.originalData) {
            data[property] = this[property];
        }

        return data;
    }


    /**
     * Reset the form fields.
     */
    reset() {
        for (let field in this.originalData) {
            this[field] = '';
        }
        this.myerrors.clear();
    }


    /**
     * Send a POST request to the given URL.
     * .
     * @param {string} url
     */
    post(url) {
        return this.submit('post', url);
    }


    /**
     * Send a PUT request to the given URL.
     * .
     * @param {string} url
     */
    put(url) {
        return this.submit('put', url);
    }


    /**
     * Send a PATCH request to the given URL.
     * .
     * @param {string} url
     */
    patch(url) {
        return this.submit('patch', url);
    }

    /**
     * Send a GET request to the given URL.
     * .
     * @param {string} url
     */
    get(url) {
        return apiGet(url,this.data());
        //return this.submit('get', url);
    }


    /**
     * Send a DELETE request to the given URL.
     * .
     * @param {string} url
     */
    delete(url) {
        return this.submit('delete', url);
    }


    /**
     * Submit the form.
     *
     * @param {string} requestType
     * @param {string} url
     */
    submit(requestType, url) {
        return new Promise((resolve, reject) => {
            axios[requestType](url, this.data())
                .then(response => {
                    this.onSuccess(response.data);
                    resolve(response.data);
                })
                .catch(error => {
                    this.onFail(error);
                    reject(error);
                });
        });
    }


    /**
     * Handle a successful form submission.
     *
     * @param {object} data
     */
    onSuccess(data) {
        if(data.message){
            this.message = data.message;
            this.type = 'success';
        }
        this.reset();
    }


    /**
     * Handle a failed form submission.
     *
     * @param {object} errors
     */
    onFail(errors) {
        if(errors.response.data.message){
            this.message = errors.response.data.message;
            this.type = 'error';
        }
        this.myerrors.record(errors);
        for (let field in errors.response.data.errors) {
            if(this.myerrors.has(field)){
                this.errors[field] = this.myerrors.get(field);
            }
        }
    }
}
// HOW TO USE IN VUE COMPONENT:
// data : {
// 	form : new MyForm({
// 		name        : '',
// 		description : '',
// 	}),
// },

// methods : {
// 	onSubmit() {
// 		this.form.post('/projects')
// 			.then(response => alert('Wahoo!'));
// 	}
// }
