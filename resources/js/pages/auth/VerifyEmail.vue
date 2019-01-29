<template>
    <div>
        <div
            class="mt-2 alert alert-warning"
            role="alert"
        >
            Email verification is taking place.
        </div>
    </div>
</template>

<script>
    export default {
        async mounted() {

            const { queryURL } = this.$route.query;
            console.log('post this url with your token', queryURL, "Sample = Authorization : Bearer + your token" );
            await axios.post(queryURL, {
                'Access-Control-Allow-Origin':'*',
                'Content-Type':'X-CSRF-TOKEN',
            }).then(x => {
                console.log(x);
            }).catch(x => {
                console.log(x);
            });
        }
    }
</script>
