<template>
    <transition
            name="fade"
            appear>
        <div class="widget widget-project" :id="projectId"
         v-bind:class="classStatus"
        v-if="eventData && eventData.budget">
        <h1 class="title">{{ title }}</h1>
        <div class="wrapper">
            <div class="column-one">
                <div class="main-infos">
                        <p id="label-reserve" v-bind:class="classRedBg">Budget restant</p>
                        <h2 class="reserve" v-bind:class="classRedBg">{{ reserveString }}</h2>
                        <p class="budget">Budget initial: {{ budgetString }}</p>
                </div>
                <div class="categories">
                    <!-- TODO: -->
                    <div v-if="pilotageHoursSold">
                        <knob-control class="dial"
                                  :value="pilotageHoursSpent"
                                  :max="pilotageHoursSold"
                                  :min="0"
                                  :size="70"
                                      :value-display-function="displayHours"
                                  text-color="#000"
                                  primary-color="#ffe66d"
                                  secondary-color="#4ecdc4"
                                  title="'Pilotage'"
                    ></knob-control>
                        <p class="text-center text-legend">Pilotage <br>{{ pilotageHoursSold }}h</p>
                    </div>
                    <div v-if="conceptionHoursSold">
                        <knob-control class="dial"
                                  :value="conceptionHoursSpent"
                                  :max="conceptionHoursSold"
                                  :min="0"
                                  :size="70"
                                      :value-display-function="displayHours"
                                  text-color="#000"
                                  primary-color="#ffe66d"
                                  secondary-color="#4ecdc4"
                                  title="Conception"
                    ></knob-control>
                        <p class="text-center text-legend">Conception <br>{{ conceptionHoursSold }}h</p>
                    </div>
                    <div v-if="realisationHoursSold">
                        <knob-control class="dial"
                                  :value="realisationHoursSpent"
                                  :max="realisationHoursSold"
                                  :min="0"
                                  :size="70"
                                      :value-display-function="displayHours"
                                  text-color="#000"
                                  primary-color="#ffe66d"
                                  secondary-color="#4ecdc4"
                                  title="Réalisation"
                    ></knob-control>
                        <p class="text-center text-legend">Réalisation<br>{{ realisationHoursSold }}h</p>
                    </div>
                </div>
                <div id="detailed-legend"><p>Temps passé / Temps vendu</p></div>
            </div>
            <div class="column-two">
                <div class="progress vertical-line" :id="reserveId"></div>
            </div>
        </div>
        <p class="updated-at">{{ new Date(updatedAt*1000).toLocaleTimeString() }}</p>
    </div>
    </transition>
</template>

<script>
    import ProgressBar from 'progressbar.js';
    import VueKnobControl from 'vue-knob-control';
    import wNumb from 'wnumb';
    import axios from 'axios';
    
    export default {
        props: [ 'projectId', 'eventSource'],
        components: {
            'knob-control': VueKnobControl,
        },
        propData:{
            eventSource: null
        },
        data: function () {
            return {
                eventData: null
            }
        },
        mounted: function() {
            this.$nextTick(function() {
                this.getMetrics();
            });
        },
        methods: {
            displayHours(v) {
                return `${v}h`;
            },
            getMetrics() {
                axios.get(`/projets/${this.projectId}/metrics`)
                    .then((response) => {
                        this.eventData = JSON.parse(response.data);
                    })
                    .catch((error) => {
                        console.log(error);
                    })
                    .then(() => {
                        console.log(`/projets/${this.projectId}/metrics finished`);
                        // Set up listener on event source
                        let eventType = `event_project_${this.projectId}`;
                        // if component is rendered (see v-if upper)
                        if (this.budget) {
                            var reserveLine = new ProgressBar.Line(`#${this.reserveId}`, {
                                color: '#1a535c',
                                duration: 3000,
                                easing: 'easeInOut',
                                trailColor: '#4ecdc4',
                                strokeWidth: 4,
                                trailWidth: 4,
                                svgStyle: {width: '100%', height: '100%'}
                            });
                            if (this.reserveValue > 0 && this.budgetValue > 0) {
                                let reservePercent = this.eventData.reserve * 100 / this.eventData.budget;
                                reserveLine.animate(reservePercent/100);
                            } else {
                                reserveLine.animate(0);
                            }
                        }
                        /*
                        console.log(`register es for ${eventType}}`);
                        this.eventSource.addEventListener(eventType, event => {
                            let projectWidget = document.querySelector(`#${this.projectId}`);
                            this.eventData = JSON.parse(event.data);
                            console.log(event.data);
        
                            if (this.reserveValue > 0 && this.budgetValue > 0) {
                                let reservePercent = this.eventData.reserve * 100 / this.eventData.budget;
                                reserveLine.animate(reservePercent/100);
                            } else {
                                reserveLine.animate(0);
                            }
                        }, false);
                        */
                    });
            },
        },
        computed: {
            reserveId: function () {
                return this.projectId.replace(/[0-9]/g, '').toLowerCase();
            },
            conceptionHoursSpent: function () {
                return null != this.eventData ? Math.round(this.eventData.conceptionHoursSpent) : 0;
            },
            conceptionHoursSold: function () {
                return null != this.eventData ? Math.round(this.eventData.conceptionHoursSold) : null;
            },
            pilotageHoursSpent: function () {
                return null != this.eventData ? Math.round(this.eventData.pilotageHoursSpent) : 0;
            },
            pilotageHoursSold: function () {
                return null != this.eventData ? Math.round(this.eventData.pilotageHoursSold) : null;
            },
            realisationHoursSpent: function () {
                return null != this.eventData ? Math.round(this.eventData.realisationHoursSpent) : 0;
            },
            realisationHoursSold: function () {
                return null != this.eventData ? Math.round(this.eventData.realisationHoursSold) : null;
            },
            conception: function () {
                return null != this.eventData ? this.eventData.conception : null;
            },
            budgetConception: function () {
                return null != this.eventData ? this.eventData.budgetConception : null;
            },
            pilotage: function () {
                return null != this.eventData ? this.eventData.pilotage : null;
            },
            budgetPilotage: function () {
                return null != this.eventData ? this.eventData.budgetPilotage : null;
            },
            realisation: function () {
                return null != this.eventData ? this.eventData.realisation : null;
            },
            budgetRealisation: function () {
                return null != this.eventData ? this.eventData.budgetRealisation : null;
            },
            updatedAt: function () {
                return null != this.eventData ? this.eventData.updatedAt : null;
            },
            title: function () {
                return null != this.eventData ? this.eventData.title.substring(0, 29) + '.' : 'title';
            },
            reserveString: function () {
                let moneyFormat = wNumb({
                    mark: '.',
                    thousand: ' ',
                    suffix: ' € HT',
                    decimals: 0,
                });
                return null != this.eventData ? moneyFormat.to(this.eventData.reserve) : '';
            },
            reserveValue: function () {
                return null != this.eventData ? this.eventData.reserve : 0;
            },
            budgetString: function () {
                let moneyFormat = wNumb({
                    mark: '.',
                    thousand: ' ',
                    suffix: ' € HT'
                });
                return null != this.eventData ? moneyFormat.to(this.eventData.budget) : 0;
            },
            budgetValue: function () {
                return null != this.eventData ? this.eventData.budget : 0;
            },
            classStatus: function () {
                return {
                    'success': true
                    /*
                    'danger': null != this.eventData ? this.eventData.reserve < 0 : false,
                    'warning': null != this.eventData? 0 === this.eventData.reserve : false,
                    'success': null != this.eventData ? this.eventData.reserve > 0 : false,
                    */
                }
            },
            classRedBg: function () {
                return {
                    'text-red': null != this.eventData ? this.eventData.reserve < 0 : false,
                    'text-black': null != this.eventData ? this.eventData.reserve > 0 : false,
                }
            }
        }
    }
</script>

<style scoped lang="scss">
    @import '~animate.css';
    $value-color: #000;
    
    $title-color: #222;
    $moreinfo-color: rgba(75, 75, 75, 0.7);
    
    // ----------------------------------------------------------------------------
    // Widget-number styles
    // ----------------------------------------------------------------------------
    .widget-project {
        //width: 300px;
        min-height: 300px;
        margin: 4px;
        color: black;
        padding: 8px;
        
        .vertical-line {
            transform: rotate(-90deg);
            margin-top: 150px;
            width: 200px;
            height: 16px;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-legend {
            color: #47bbb3;
            font-size: 12px;
        }
        
        .text-red {
            color: #ff6b6b;
        }
    
        .text-black {
            color: rgb(26, 83, 92);
        }
        
        .wrapper {
            display: grid;
        }
        
        .column-one {
            grid-column: 1;
            grid-row: 1;
        }
        
        .column-two {
            grid-column: 2;
            grid-row: 1;
        }
        
        .label-reserve {
            font-size: 10px;
            color: #1a535c;
        }
        
        .categories {
            margin-top: 14px;
            padding-bottom: 4px;
            display: inline-flex;
        }
        
        .dial {
            display: inline;
        }
        
        .title {
            font-size: 26px;
            color: $title-color;
            border-bottom: 1px solid #47bbb3;
            padding-bottom: 8px;
        }
        
        .reserve {
            //color: $value-color;
            font-weight: normal;
            font-size: 50px;
            padding: 8px;
        }
        
        .budget {
            font-size: 10px;
            font-weight: normal;
            color: rgb(78, 205, 196);
        }
        
        .main-infos {
            color: $value-color;
            margin-bottom: 8px;
        }
        
        .updated-at {
            color: rgba(0, 0, 0, 0.3);
            margin-top: 14px;
        }
        
        #detailed-legend {
            color: #2c3e50;
            text-align: center;
            font-weight: normal;
            font-size: 14px;
        }
        
    }
    .danger {
        background-color: #ff6b6b;
    }

    .warning {
        background-color: #f7fff7;
    }

    .success {
        background-color: #f7fff7;
    }
    .fade-enter-active, .fade-leave-active {
        transition: opacity .5s;
    }
    .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
        opacity: 0;
    }
</style>