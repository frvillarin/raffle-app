<template>
    <div class="mt-3 -mb-1 flex justify-end">
        <div v-for="(link, key) in links" class="flex align-middle">
            <div v-if="link.url === null"
                 class="mr-1 mb-1 px-3 py-2 text-sm border rounded text-gray-400 bg-white"
                 :class="{ 'ml-auto': link.label === 'Next' }" v-html="link.label"></div>
            <inertia-link v-else
                          class="mr-1 mb-1 px-3 py-2 text-sm border rounded hover:bg-blue-400 hover:text-white focus:border-blue-500 focus:text-white"
                          :class="{ 'bg-blue-500 text-white': link.active, 'ml-auto': link.label === 'Next' }"
                          :href="link.url" v-html="link.label"></inertia-link>
        </div>
    </div>
</template>

<script>
export default {
    name: "Pagination",
    props: {
        links: []
    }
}
</script>

<style scoped>

</style>
