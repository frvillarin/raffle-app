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
                    <div class="flex w-full bg-white items-center px-6 py-4">
                        <div>
                            <inertia-link
                                class="inline-flex items-center mx-3 sm:mx-0 px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150"
                                :href="route('admin.participants.create')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                <span>Add Participant</span>
                            </inertia-link>
                        </div>
                        <span class="mx-5">OR</span>
                        <div>
                            <form @submit.prevent="importData" class=" rounded-lg"
                                  enctype="multipart/form-data">
                                <input type="file" class="border border-2 px-2 py-1" @change="onChange"
                                       :readonly="form.processing">
                                <jet-button :class="{ 'opacity-25 cursor-not-allowed': form.processing || !form.file}"
                                            :disabled="form.processing || !form.file">
                                    <svg v-if="form.processing"
                                         class="animate-spin inline-block mr-3 h-4 w-4 text-white"
                                         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Import
                                </jet-button>
                            </form>
                        </div>
                    </div>

                    <div class="w-full my-6 shadow">
                        <table class="rounded-t-lg m-5 w-full mx-auto bg-gray-200 text-gray-800">
                            <thead>
                            <tr class="text-left border-b-2 border-gray-100">
                                <th class="px-4 py-3">Name</th>
                                <th class="px-4 py-3">Department</th>
                                <th class="px-4 py-3">Shift</th>
                                <th class="px-4 py-3">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="participant in participants.data" class="bg-white border-b border-gray-200">
                                <td class="px-4 py-2">{{ participant.name }}</td>
                                <td class="px-4 py-2">{{ participant.department }}</td>
                                <td class="px-4 py-2">{{ participant.shift }}</td>
                                <td class="px-4 py-2">
                                    <div class="inline-block">
                                        <inertia-link class="inline-flex items-center px-1.5 py-0.5 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:border-yellow-900 focus:ring focus:ring-yellow-300 disabled:opacity-25 transition" :href="route('admin.participants.edit', participant)">
                                           Edit
                                        </inertia-link>

                                    </div>
                                    <div class="inline-block">
                                        <button class="inline-flex items-center px-1.5 py-0.5 bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring focus:ring-red-300 disabled:opacity-25 transition" @click="confirmParticipantDeletion(participant)">
                                           Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <jet-confirmation-modal :show="confirmingUserDeletion" class="align-middle">
                            <template #title>
                                Delete Participant
                            </template>

                            <template #content>
                                Are you sure you want to delete this participant?
                            </template>

                            <template #footer>
                                <jet-secondary-button
                                    :class="{ 'opacity-25 cursor-not-allowed': deleteParticipantForm.processing }"
                                    @click.native="confirmingUserDeletion = false">
                                    Nevermind
                                </jet-secondary-button>

                                <jet-danger-button class="ml-2" @click.native="deleteParticipant"
                                                   :class="{ 'opacity-25 cursor-not-allowed': deleteParticipantForm.processing }"
                                                   :disabled="deleteParticipantForm.processing">
                                    <svg v-if="deleteParticipantForm.processing"
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
                    <pagination :links="participants.links"/>
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
    props: ['participants'],
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
            participantToDelete: null,
            deleteParticipantForm: this.$inertia.form({
                '_method': 'DELETE',
            }),
            form: this.$inertia.form({
                file: '',
            }, {
                resetOnSuccess: false,
            }),
        }
    },
    methods: {
        confirmParticipantDeletion(participant) {
            this.confirmingUserDeletion = true
            this.participantToDelete = participant
        },
        onChange(event) {
            this.form.file = event.target.files[0]
        },
        deleteParticipant() {
            this.deleteParticipantForm.delete(this.route('admin.participants.destroy', this.participantToDelete), {
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
        importData() {
            this.form.post(this.route('admin.participants.importData'), {
                onSuccess: success => {
                    this.$notify({
                        title: "Successful",
                        text: this.$page.props.flash.message,
                    });
                },
                onError: errors => {
                    this.$notify({
                        title: "Error",
                        type: 'error',
                        text: Object.values(errors).join('<br/>'),
                    });
                }
            })
        }
    }
}
</script>

<style scoped>

</style>
