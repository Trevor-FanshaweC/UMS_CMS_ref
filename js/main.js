import Vue from 'https://cdn.jsdelivr.net/npm/vue@2.6.11/dist/vue.esm.browser.js';

(() => {
    const myVM = new Vue({
        data: {
            users: []
        },

        created: function() {
            // manageUsers takes 3 arguments - a method, a parameters object and an optional callback
            // the callback in this case is just an anonymous function that pushes the database
            // query result into the users array
            this.manageUsers("getAll", {}, (data) => {
                // push user into the VM users array
                myVM.users = data;
            });
        },

        methods: {
            manageUsers(op, params, cb) {
                // ops is short for "operations" and correspond to SQL verbs -> get, delete, post, etc
                // these are passed in via function call (the first argument) on a click or created event
                // and then match them up / mine them to build out the URL and fetch call

                let ops = {
                    "getAll": { path: `get_users=true`, options: null },

                    "deleteOne": { 
                        path: `delete_user=true&&user_id=${params.id ? params.id : null}`,
                        options: { method: 'DELETE' }
                    },

                    "addOne": { 
                        path: `add_user=true`, 
                        options: {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json, text/plain, */*',
                                'Content-type': 'application/x-www-form-urlencoded'
                            },

                            body: params ? this.convertToQueryString(params) : null
                        }
                    }
                };

                let url = `./includes/UMS.php?${ops[op].path}`;

                fetch(url, ops[op].options)
                .then(res => res.json())
                .then(data => { 
                    console.log(data);
                    // if there is a callback passed into the manageUsers method, 
                    // then invoke it here
                    if (cb) { cb(data) }
                 })
                .catch((err) => console.log(err))
            },

            convertToQueryString(obj) {
                return Object.keys(obj).map(key => key + '=' + obj[key]).join('&');
            },

            updateUserList(currentuser, method) {
                // filter the users array to remove the deleted user
                // really, at this point we should start thinking about using a state
                // manager, but this gets the job done
                if (method === "delete") {
                    this.users = this.users.filter(user => user.fname !== currentuser.fname);
                } else {
                    this.users.push(currentuser)
                }         
            },

            createUser() {
                let formData = new FormData(document.querySelector('form')),
                    userData = {};
    
                for (let [key, value] of formData.entries()) {
                    userData[key] = value;
                }
    
                this.manageUsers("addOne", userData, (data) => this.updateUserList(data, "add"));
            }
        },

    }).$mount("#app")
})();