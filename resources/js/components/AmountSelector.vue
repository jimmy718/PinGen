<template>
    <select v-model="amount" @change="$emit('selected', amount)" class="form-control">
        <option v-for="n in 10" :value="n" :key="n">{{n}}</option>
    </select>
</template>

<script>
export default {
    name: "AmountSelector",
    data() {
        return {
            amount: 1
        }
    }
}
</script>

<style scoped>

</style>
