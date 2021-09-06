<template>
    <div>
        <app-layout>
            <template #header>
                <h2 class="font-light text-2xl text-gray-800 leading-tight">
                    Raffle Play
                </h2>
            </template>

            <div class="flex h-screen">
                <ul>
                    <li v-show="finalSelectedShift">Shift: {{ finalSelectedShift }}</li>
                    <li v-show="finalSelectedDepartment">Department: {{ finalSelectedDepartment }}</li>
                    <li v-show="finalSelectedParticipant">Participant: {{ finalSelectedParticipant }}</li>
                </ul>
                <div class="m-auto">
                    <button
                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150"
                        @click.prevent="generateRandomShift" v-show="!isDrawingShift">Start
                    </button>
                    <h2 class="text-2xl font-bold" v-show="isDrawingShift && !isDrawingDepartment && !isDrawingParticipant">{{
                            selectedShift
                        }}</h2>
                    <h2 class="text-2xl font-bold" v-show="isDrawingDepartment && isDrawingDepartment">{{ selectedDepartment }}</h2>
                    <h2 class="text-2xl font-bold" v-show="isDrawingParticipant">{{ selectedParticipant }}</h2>
                </div>
            </div>

        </app-layout>
    </div>
</template>

<script>

import AppLayout from "@/Layouts/AppLayout";

export default {
    name: "Play",
    props: ['participants'],
    components: {
        AppLayout,
    },
    data() {
        return {
            shifts: _.groupBy(this.participants, 'shift'),
            departments: _.groupBy(this.participants, 'department'),
            isDrawingShift: false,
            isDrawingDepartment: false,
            isDrawingParticipant: false,

            selectedShift: null,
            finalSelectedShift: null,

            selectedDepartment: null,
            finalSelectedDepartment: null,

            finalParticipants: null,
            selectedParticipant: null,
            finalSelectedParticipant: null,

            showerTime: 5,
        }
    },
    mounted() {

    },
    methods: {
        generateRandomShift() {
            this.randomShift(2, 100)
        },
        randomShift(factor, times) {
            this.isDrawingShift = true
            const internalCallback = ((t, counter) => {
                return () => {
                    if (--t > 0) {
                        setTimeout(internalCallback, ++counter * factor)
                        const shiftKeys = Object.keys(this.shifts)
                        const random = Math.floor(Math.random() * shiftKeys.length)
                        this.selectedShift = shiftKeys[random]
                    } else {
                        this.finalSelectedShift = this.selectedShift
                        setTimeout(() => {
                            this.randomDepartment(2, 100)
                        }, 1000)
                    }
                };
            })(times, 0);
            setTimeout(internalCallback, factor);
        },
        randomDepartment(factor, times) {
            this.isDrawingDepartment = true
            const internalCallback = ((t, counter) => {
                return () => {
                    if (--t > 0) {
                        setTimeout(internalCallback, ++counter * factor)
                        const departmentKeys = Object.keys(this.departments)
                        const random = Math.floor(Math.random() * departmentKeys.length)
                        this.selectedDepartment = departmentKeys[random]
                    } else {
                        this.finalSelectedDepartment = this.selectedDepartment
                        const FSD = this.finalSelectedDepartment
                        this.finalParticipants = _.filter(this.shifts[this.selectedShift], function (participant) {
                            return participant.department === FSD
                        })
                        if (this.finalParticipants.length){
                            setTimeout(() => {
                                this.randomParticipant(3, 110)
                            }, 1000)
                            this.isDrawingDepartment = false
                        }else{ // If there are no participants
                            console.log("There are no participants!")
                            alert("There are no participants!")
                        }
                    }
                };
            })(times, 0);
            setTimeout(internalCallback, factor);
        },
        randomParticipant(factor, times) {
            this.isDrawingParticipant = true
            const internalCallback = ((t, counter) => {
                return () => {
                    if (--t > 0) {
                        setTimeout(internalCallback, ++counter * factor)
                        const random = Math.floor(Math.random() * this.finalParticipants.length)
                        this.selectedParticipant = this.finalParticipants[random].name
                    } else {
                        this.finalSelectedParticipant = this.selectedParticipant
                        this.$confetti.start({
                            particles: [
                                {
                                    type: 'rect',
                                },
                            ],
                            defaultDropRate: 15
                        });
                        this.stopShower()
                    }
                };
            })(times, 0);
            setTimeout(internalCallback, factor);
        },
        stopShower() {
            if(this.showerTime > 0) {
                setTimeout(() => {
                    this.showerTime -= 1
                    this.stopShower()
                }, 1000)
            } else {
                this.$confetti.stop()
            }
        }
    }
}
</script>

<style scoped>

</style>
