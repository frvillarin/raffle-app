<template>
    <div>
        <app-layout>
            <template #header>
                <h2 class="font-light text-2xl text-gray-800 leading-tight">
                    Prizes
                </h2>
            </template>

            <div class="sm:py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <inertia-link
                        class="inline-flex items-center mx-3 sm:mx-0 px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150"
                        :href="route('admin.prizes.create')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span>Add Prize</span>
                    </inertia-link>

                    <div class="w-full my-6 shadow">
                        <table class="rounded-t-lg m-5 w-full mx-auto bg-gray-200 text-gray-800">
                            <thead>
                            <tr class="text-left border-b-2 border-gray-100">
                                <th class="px-4 py-3">Title</th>
                                <th class="px-4 py-3">Winner</th>
                                <th class="px-4 py-3">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="prize in prizes.data" class="bg-white border-b border-gray-200">
                                <td class="px-4 py-2">{{ prize.title }}</td>
                                <td class="px-4 py-2">
                                    <div v-if="prize.winner">
                                        {{ prize.winner.participant.name }}
                                    </div>
                                    <div v-else class="inline-block">
                                        <inertia-link
                                            class="inline-flex items-center px-2 py-1 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition"
                                            :href="route('admin.prizes.draw', prize)">
                                            Draw Raffle
                                        </inertia-link>

                                    </div>
                                </td>
                                <td class="px-4 py-2">
                                    <div v-show="!prize.winner">
                                        <div class="inline-block">
                                            <inertia-link
                                                class="inline-flex items-center px-1.5 py-0.5 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:border-yellow-900 focus:ring focus:ring-yellow-300 disabled:opacity-25 transition"
                                                :href="route('admin.prizes.edit', prize)">
                                                Edit
                                            </inertia-link>

                                        </div>
                                        <div class="inline-block">
                                            <button
                                                class="inline-flex items-center px-1.5 py-0.5 bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring focus:ring-red-300 disabled:opacity-25 transition"
                                                @click="confirmPrizeDeletion(prize)">
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <jet-confirmation-modal :show="confirmingUserDeletion" class="align-middle">
                            <template #title>
                                Delete Prize
                            </template>

                            <template #content>
                                Are you sure you want to delete this prize?
                            </template>

                            <template #footer>
                                <jet-secondary-button
                                    :class="{ 'opacity-25 cursor-not-allowed': deletePrizeForm.processing }"
                                    @click.native="confirmingUserDeletion = false">
                                    Nevermind
                                </jet-secondary-button>

                                <jet-danger-button class="ml-2" @click.native="deletePrize"
                                                   :class="{ 'opacity-25 cursor-not-allowed': deletePrizeForm.processing }"
                                                   :disabled="deletePrizeForm.processing">
                                    <svg v-if="deletePrizeForm.processing"
                                         class="animate-spin inline-block mr-3 h-4 w-4 text-white"
                                         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Delete
                                </jet-danger-button>
                            </template>
                        </jet-confirmation-modal>

                    </div>
                    <pagination :links="prizes.links"/>
                </div>
            </div>
            <notifications position="top right"/>
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
import SmallButton from "@/CustomComponents/SmallButton";

export default {
    name: "List",
    props: ['prizes'],
    components: {
        AppLayout,
        JetButton,
        JetConfirmationModal,
        JetDangerButton,
        JetSecondaryButton,
        Pagination,
        SmallButton,
    },
    data() {
        return {
            confirmingUserDeletion: false,
            deleting: false,
            prizeToDelete: null,
            deletePrizeForm: this.$inertia.form({
                '_method': 'DELETE',
            }),
            form: this.$inertia.form({
                title: '',
            }, {
                resetOnSuccess: false,
            }),
        }
    },
    methods: {
        confirmPrizeDeletion(prize) {
            this.confirmingUserDeletion = true
            this.prizeToDelete = prize
        },
        onChange(event) {
            this.form.file = event.target.files[0]
        },
        deletePrize() {
            this.deletePrizeForm.delete(this.route('admin.prizes.destroy', this.prizeToDelete), {
                onSuccess: success => {
                    this.confirmingUserDeletion = false;
                    this.$notify({
                        title: "Successful",
                        text: this.$page.props.flash.message,
                    });
                },
                onError: errors => {
                    this.confirmingUserDeletion = false;
                }
            })
        },
    }
}
</script>

<style scoped>

</style>
