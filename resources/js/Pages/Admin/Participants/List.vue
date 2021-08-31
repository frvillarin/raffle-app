<template>
    <div>
        <app-layout>
            <template #header>
                <h2 class="font-light text-2xl text-gray-800 leading-tight">
                    Participants
                </h2>
            </template>


            <div class="sm:py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="flex justify-end">
                        <!--                        <inertia-link v-if="$page.props.user.is_super_admin"-->
                        <!--                                      class="inline-flex items-center mx-3 sm:mx-0 px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150"-->
                        <!--                                      :href="route('admin.sites.create')">-->
                        <!--                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">-->
                        <!--                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>-->
                        <!--                            </svg>-->
                        <!--                            <span>Create Site</span>-->
                        <!--                        </inertia-link>-->
                    </div>

                    <div class="w-full my-6 shadow">
                        <table class="rounded-t-lg m-5 w-full mx-auto bg-gray-200 text-gray-800">
                            <thead>
                                <tr class="text-left border-b-2 border-gray-300">
                                    <th class="px-4 py-3">Name</th>
                                    <th class="px-4 py-3">Department</th>
                                    <th class="px-4 py-3">Shift</th>
                                    <th class="px-4 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-gray-100 border-b border-gray-200">
                                    <td class="px-4 py-2">Jill</td>
                                    <td class="px-4 py-2">Smith</td>
                                    <td class="px-4 py-2">50</td>
                                    <td class="px-4 py-2">Male</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!--                    <pagination :links="sites.links"/>-->
                </div>

                <!--                <jet-confirmation-modal :show="confirmingUserDeletion" class="align-middle"-->
                <!--                                        v-if="$page.props.user.is_super_admin">-->
                <!--                    <template #title>-->
                <!--                        Delete Site-->
                <!--                    </template>-->

                <!--                    <template #content>-->
                <!--                        Are you sure you want to delete this site?-->
                <!--                    </template>-->

                <!--                    <template #footer>-->
                <!--                        <jet-secondary-button-->
                <!--                            :class="{ 'opacity-25 cursor-not-allowed': deleteSiteForm.processing }"-->
                <!--                            @click.native="confirmingUserDeletion = false">-->
                <!--                            Nevermind-->
                <!--                        </jet-secondary-button>-->

                <!--                        <jet-danger-button class="ml-2" @click.native="deleteSite"-->
                <!--                                           :class="{ 'opacity-25 cursor-not-allowed': deleteSiteForm.processing }"-->
                <!--                                           :disabled="deleteSiteForm.processing">-->
                <!--                            <svg v-if="deleteSiteForm.processing"-->
                <!--                                 class="animate-spin inline-block mr-3 h-4 w-4 text-white"-->
                <!--                                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">-->
                <!--                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"-->
                <!--                                        stroke-width="4"></circle>-->
                <!--                                <path class="opacity-75" fill="currentColor"-->
                <!--                                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>-->
                <!--                            </svg>-->
                <!--                            Delete-->
                <!--                        </jet-danger-button>-->
                <!--                    </template>-->
                <!--                </jet-confirmation-modal>-->
            </div>

        </app-layout>
    </div>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import JetButton from '@/Jetstream/Button'
import JetConfirmationModal from '@/Jetstream/ConfirmationModal'
import JetDangerButton from "@/Jetstream/DangerButton";
import JetSecondaryButton from "@/Jetstream/SecondaryButton";
import Pagination from "@/CustomComponents/Pagination";

export default {
    name: "List",
    components: {
        AppLayout,
        JetButton,
        JetConfirmationModal,
        JetDangerButton,
        JetSecondaryButton,
        Pagination,
    }
}
</script>

<style scoped>

</style>
