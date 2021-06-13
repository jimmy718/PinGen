<template>
    <Layout>
        <div class="row">
            <div class="col-sm-12">
                <AmountSelector @selected="amount = $event" class="w-100"></AmountSelector>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-12">
                <RequestPINsButton @returned="PINs = $event" :amount="amount" class="w-100"></RequestPINsButton>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-12">
                <PINList :PINs="PINs" class="w-100"></PINList>
            </div>
        </div>
    </Layout>
</template>

<script>
import Layout from './../Layout'
import AmountSelector from '../AmountSelector'
import RequestPINsButton from '../RequestPINsButton'
import PINList from '../PINList'

export default {
    name: "PINGenerator",
    components: { Layout, AmountSelector, RequestPINsButton, PINList },
    data() {
        return {
            amount: 1,
            PINs: [],
        }
    }
}
</script>

<style scoped>

</style>
