<template>
    <button @click="getPINs" class="btn btn-primary">Get PINs</button>
</template>

<script>
export default {
    name: "RequestPINsButton",
    props: {
        amount: {
            type: Number
        }
    },
    methods: {
        getPINs() {
            axios.get(`/PINs?count=${this.amount}`)
                .then(({data}) => {
                    this.$emit('returned', data)
                })
        }
    }
}
</script>
