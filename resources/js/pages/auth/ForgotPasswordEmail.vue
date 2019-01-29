<template>
</template>

<script>
    import router from 'vue-router';
    export default {
        name: "ForgotPasswordEmail",
        data() {
            return {
                token:'',
                email:'',
                password:'testpasswords', //Input incoming data (model="password")
            }
        },
        async created() {
            this.token = this.$route.params.token;
            this.email = this.$route.params.email;
            await axios.post('http://127.0.0.1:8000/api/auth/forgot-password-reset/',
                {
                    token: this.token,
                    email: this.email,
                    password: this.password,
                },
                {
                'Access-Control-Allow-Origin':'*',
                'Content-Type':'multipart/form-data',
                    'Accept':'application/json',
            }).then(response => {
                console.log(response.data.message)
            })
                .catch(error => {
                    console.error(error.response.data.message)
                });
        }
    }
</script>

<style scoped>

</style>
