<template>
    <div class="widget widget-project" v-bind:class="classStatus">
        <h1 class="title">{{ title }}</h1>
        <div class="wrapper">
            <div class="column-one">
                <div class="main-infos">
                    <p>Budget restant</p>
                    <h2 class="reserve">{{ reserve }}</h2>
                    
                    <p class="budget">Budget initial: {{ budget }}</p>
                </div>
                <div class="categories">
                    <div>
                    <knob-control class="dial"
                                  :value="event.pilotage"
                                  :max="event.budgetPilotage"
                                  :min="0"
                                  :size="50"
                                  text-color="#000"
                                  primary-color="#ffe66d"
                                  secondary-color="#fff"
                                  title="'Pilotage'"
                    ></knob-control>
                        <p class="text-center text-legend">Conception</p>
                    </div>
                    <div>
                    <knob-control class="dial"
                                  :value="event.conception"
                                  :max="event.budgetConception"
                                  :min="0"
                                  :size="50"
                                  text-color="#000"
                                  primary-color="#ffe66d"
                                  secondary-color="#fff"
                                  title="Conception"
                    ></knob-control>
                        <p class="text-center text-legend">Réalisation</p>
                    </div>
                    <div>
                    <knob-control class="dial"
                                  :value="event.realisation"
                                  :max="event.budgetRealisation"
                                  :min="0"
                                  :size="50"
                                  text-color="#000"
                                  primary-color="#ffe66d"
                                  secondary-color="#fff"
                                  title="Réalisation"
                    ></knob-control>
                        <p class="text-center text-legend">Pilotage</p>
                    </div>
                </div>
                <div id="detailed-legend"><p>Temps passé / Temps vendu</p></div>
            </div>
            <div class="column-two">
                <canvas data-type="linear-gauge"
                        v-bind:data-value="event.reserve"
                        data-min-value="0"
                        v-bind:data-max-value="event.budget"
                        data-units=""
                        data-tick-side="right"
                        data-ticks-width="0"
                        data-number-side="right"
                        data-borders="false"
                        data-bar-begin-circle="false"
                        data-stroke-ticks="false"
                        data-width="150"
                        data-height="300"
                        data-color-units="#fff"
                        data-value-box="false"
                        data-bar-stroke-width="0"
                        data-bar-stroke-progress="false"
                        data-major-ticks=""
                        data-minor-ticks=""
                        data-needle="false"
                        data-needle-width="0"
                        data-color-plate="rgba(255, 255, 255, 0)"
                        data-color-numbers="rgba(255, 255, 255, 0)"
                        data-color-bar-stroke="rgba(255, 255, 255, 0)"
                        data-color-stroke-ticks="rgba(255, 255, 255, 0)"
                        data-color-bar="#4ecdc4"
                        data-color-bar-progress="#1a535c"
                ></canvas>
            </div>
        </div>
        <p class="updated-at">{{ new Date(event.updatedAt*1000).toLocaleTimeString() }}</p>
    </div>
</template>

<script>
    import 'canvas-gauges/gauge.min';
    import VueKnobControl from 'vue-knob-control';
    import wNumb from 'wnumb';
    export default {
        props: ['event'],
        components: {
            'knob-control': VueKnobControl,
        },
        data: {
            event: {
                pilotage: 0
            }
        },
        computed: {
            title: function () {
                return undefined !== this.event.title ? this.event.title.substring(0, 29) + '.' : 'title';
            },
            reserve: function () {
                let moneyFormat = wNumb({
                    mark: '.',
                    thousand: ' ',
                    suffix: ' € HT'
                });
                return moneyFormat.to(this.event.reserve);
            },
            budget: function () {
                let moneyFormat = wNumb({
                    mark: '.',
                    thousand: ' ',
                    suffix: ' € HT'
                });
                return moneyFormat.to(this.event.budget);
            },
            classStatus: function () {
                return {
                    'danger': this.event.reserve < 0,
                    'warning': 0 === this.event.reserve,
                    'success': this.event.reserve > 0
                }
            }
        }
    }
</script>

<style scoped lang="scss">
    $value-color: #000;
    
    $title-color: rgba(75, 75, 75, 0.7);
    $moreinfo-color: rgba(75, 75, 75, 0.7);
    
    // ----------------------------------------------------------------------------
    // Widget-number styles
    // ----------------------------------------------------------------------------
    .widget-project {
        
        .text-center {
            text-align: center;
        }
        
        .text-legend {
            font-size: 8px;
        }
        
        height: 350px;
        
        .wrapper {
            display: grid;
        }
        
        .column-one {
            grid-column: 1;
            grid-row: 1;
            padding-top: 45px;
        }
        
        .column-two {
            grid-column: 2;
            grid-row: 1;
        }
        
        .categories {
            margin: 14px;
            display: inline-flex;
        }
        
        .dial {
            display: inline;
        }
        
        .title {
            color: $title-color;
        }
        
        .reserve {
            color: $value-color;
            font-size: 50px;
        }
        
        .budget {
            color: $moreinfo-color;
        }
        
        .main-infos {
            color: $value-color;
        }
        
        .updated-at {
            color: rgba(0, 0, 0, 0.3);
        }
        
        #detailed-legend {
            color: #000;
            text-align: center;
            font-weight: bold;
            font-size: 14px;
        }
        
    }
    .danger {
        background-color: #ff6b6b;
    }

    .warning {
        background-color: orange;
    }

    .success {
        background-color: #f7fff7;
    }
</style>