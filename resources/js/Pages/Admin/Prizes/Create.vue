<template>
    <div>
        <app-layout>
            <template #header>
                <h2 class="font-light text-2xl text-gray-800 leading-tight">
                    Add Prize
                </h2>

            </template>

            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <jet-form-section @submitted="addPrize">
                        <template #title>
                            Prize Details
                        </template>
                        <template #description>
                            <p>Input necessary details for the Prize</p>
                        </template>

                        <template #form>
                            <!-- Name -->
                            <div class="col-span-6 sm:col-span-4">
                                <jet-label for="title" value="Title"/>
                                <jet-input id="title" type="text" class="mt-1 block w-full" v-model="form.title"
                                           autocomplete="name"/>
                                <jet-input-error :message="form.errors.title" class="mt-2"/>
                            </div>
                        </template>

                        <template #actions>
                            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                                Saved.
                            </jet-action-message>

                            <inertia-link
                                :disabled="form.processing"
                                :class="{ 'opacity-25 cursor-not-allowed': form.processing }"
                                class="inline-flex items-center px-4 py-2 mr-2 bg-white border border-gray-800 rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest"
                                :href="route('admin.prizes.index')">
                                Cancel
                            </inertia-link>

                            <jet-button :class="{ 'opacity-25 cursor-not-allowed': form.processing }"
                                        :disabled="form.processing">
                                <svg v-if="form.processing" class="animate-spin inline-block mr-3 h-4 w-4 text-white"
                                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Create
                            </jet-button>
                        </template>
                    </jet-form-section>
                </div>
                <notifications position="top right"/>
            </div>
        </app-layout>
    </div>

</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import JetButton from '@/Jetstream/Button'
import JetActionMessage from '@/Jetstream/ActionMessage'
import JetFormSection from '@/Jetstream/FormSection'
import JetSecondaryButton from "@/Jetstream/SecondaryButton";
import JetValidationErrors from '@/Jetstream/ValidationErrors';
import JetLabel from '@/Jetstream/Label'
import JetInput from '@/Jetstream/Input'
import JetInputError from '@/Jetstream/InputError'
import CustomSelect from "@/CustomComponents/CustomSelect";

export default {
    name: "Create",
    components: {
        CustomSelect,
        AppLayout,
        JetActionMessage,
        JetButton,
        JetFormSection,
        JetSecondaryButton,
        JetValidationErrors,
        JetInput,
        JetInputError,
        JetLabel,
    },
    data() {
        return {
            form: this.$inertia.form({
                title: '',
            }),
        }
    },
    methods: {
        addPrize() {
            this.form.post(this.route('admin.prizes.store'), {
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
