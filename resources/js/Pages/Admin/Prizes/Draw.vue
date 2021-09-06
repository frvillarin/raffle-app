<template>
    <div>
        <app-layout>
            <template #header>
                <h2 class="font-light text-5xl text-gray-800 leading-tight">
                    Prize: <span class="font-bold">{{ prize.title }}</span>
                </h2>
            </template>

            <div class="flex h-screen">
                <div class="mx-auto my-48 text-center">
                    <div v-if="prize.winner">
                        <p class="text-6xl">
                            Raffle Draw Winner
                        </p>
                        <p class="font-bold text-7xl uppercase my-4">
                            {{ prize.winner.participant.name }}
                        </p>
                        <inertia-link
                            class="inline-flex mt-5 text-3xl uppercase text-white items-center px-8 py-6 bg-blue-400 border border-blue-300 rounded-md font-bold tracking-widest shadow-sm hover:text-gray-100 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150"
                            :href="route('admin.prizes.index')">
                            <span>Go to Prizes</span>
                        </inertia-link>
                    </div>
                    <div v-else>
                        <ul class="text-6xl">
                            <li v-show="finalSelectedShift">{{ finalSelectedShift }}</li>
                            <li v-show="finalSelectedDepartment">{{ finalSelectedDepartment }}</li>
                            <!--                        <li v-show="finalSelectedParticipant">-->
                            <!--                            {{ finalSelectedParticipant ? finalSelectedParticipant.name : '' }}-->
                            <!--                        </li>-->
                        </ul>
                        <button
                            class="inline-flex text-3xl uppercase text-white items-center px-8 py-6 bg-blue-400 border border-blue-300 rounded-md font-bold tracking-widest shadow-sm hover:text-gray-100 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150"
                            @click.prevent="generateRandomShift" v-show="!isDrawingShift">Start
                        </button>
                        <h2 class="text-7xl font-bold"
                            v-show="isDrawingShift && !isDrawingDepartment && !isDrawingParticipant">
                            {{ selectedShift }}
                        </h2>
                        <h2 class="text-7xl font-bold" v-show="isDrawingShift && isDrawingDepartment">
                            {{ selectedDepartment }}
                        </h2>
                        <h2 class="text-8xl font-bold uppercase w-full" v-show="isDrawingParticipant">
                            {{ selectedParticipant ? selectedParticipant.name : '' }}
                        </h2>
                        <div class="mt-12">
                            <button
                                class="inline-flex text-3xl uppercase text-white items-center px-8 py-6 bg-purple-400 border border-purple-300 rounded-md font-bold tracking-widest shadow-sm hover:text-gray-100 focus:outline-none focus:border-purple-300 focus:shadow-outline-blue transition ease-in-out duration-150"
                                @click.prevent="storeWinner" v-show="finalSelectedParticipant">Save Winner
                            </button>
                            <button
                                class="inline-flex text-3xl uppercase text-white items-center px-8 py-6 bg-blue-400 border border-blue-300 rounded-md font-bold tracking-widest shadow-sm hover:text-gray-100 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150"
                                @click="restart" v-show="finalSelectedParticipant || noParticipants">Restart
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </app-layout>
    </div>
</template>

<script>

import AppLayout from "@/Layouts/AppLayout";

export default {
    name: "Play",
    props: ['participants', 'prize'],
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
            noParticipants: false,
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
                        this.randomDepartment(2, 100)
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
                        this.isDrawingDepartment = false
                        const FSD = this.finalSelectedDepartment
                        this.finalParticipants = _.filter(this.shifts[this.selectedShift], function (participant) {
                            return participant.department === FSD
                        })
                        if (this.finalParticipants.length) {
                            this.randomParticipant(3, 110)
                        } else { // If there are no participants
                            alert("There are no participants!")
                            this.noParticipants = true
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
                        this.selectedParticipant = this.finalParticipants[random]
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
            if (this.showerTime > 0) {
                setTimeout(() => {
                    this.showerTime -= 1
                    this.stopShower()
                }, 1000)
            } else {
                this.$confetti.stop()
            }
        },
        storeWinner() {
            axios.post(this.route('admin.winners.store'),
                {
                    prize_id: this.prize.id,
                    participant_id: this.finalSelectedParticipant.id,
                }).then(response => {
                if (response.data.success) {
                    this.restart()
                }
            })
        },
        restart() {
            location.reload()
        }
    }
}
</script>

<style scoped>

</style>
