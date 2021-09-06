<template>
        <select class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm rounded" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)"  >
            <option v-for="(option, name) in options" :value="option">{{ name }}</option>
        </select>
</template>

<script>
export default {
    name: "CustomSelect",
    props: ['options','modelValue'],
    emits: ['update:modelValue'],
    methods: {
        focus() {
            this.$refs.input.focus()
        }
    }
}
</script>

<style scoped>

</style>
