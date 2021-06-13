<template>
    <ul class="list-group">
        <li v-for="PIN in PINs" class="list-group-item">{{PIN.value}}</li>
    </ul>
</template>

<script>
export default {
    name: "PINList",
    props: {
        PINs: {
            type: Array,
        }
    }
}
</script>
