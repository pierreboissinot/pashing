<template>
    <div class="widget widget-project">
        <h1 class="title">{{ event.title }}</h1>
        <div class="wrapper">
            <div class="column-one">
                <div class="main-infos">
                    <h2 class="reserve">{{ reserve }}</h2>
                    <p>réserve</p>
                    
                    <p class="budget">budget: {{ budget }}</p>
                </div>
                <div class="categories">
                    <knob-control class="dial"
                                  :value="event.pilotage"
                                  :max="event.budgetPilotage"
                                  :min="0"
                                  :size="100"
                                  text-color="#fff"
                                  primary-color="#FFFC19"
                                  secondary-color="#fff"
                                  title="'Pilotage'"
                    ></knob-control>
                    <knob-control class="dial"
                                  :value="event.conception"
                                  :max="event.budgetConception"
                                  :min="0"
                                  :size="100"
                                  text-color="#fff"
                                  primary-color="#FF0000"
                                  secondary-color="#fff"
                                  title="Conception"
                    ></knob-control>
                    <knob-control class="dial"
                                  :value="event.realisation"
                                  :max="event.budgetRealisation"
                                  :min="0"
                                  :size="100"
                                  text-color="#fff"
                                  primary-color="#1485CC"
                                  secondary-color="#fff"
                                  title="Réalisation"
                    ></knob-control>
                </div>
            </div>
            <div class="column-two">
                <canvas data-type="linear-gauge"
                        v-bind:data-value="event.reserve"
                        data-min-value="0"
                        v-bind:data-max-value="event.budget"
                        data-units="€"
                        data-tick-side="right"
                        data-number-side="right"
                        data-needle-side="right"
                        data-borders="false"
                        data-bar-begin-circle="false"
                        data-stroke-ticks="false"
                        data-width="150"
                        data-height="300"
                        data-color-plate="#009bbb"
                        data-color-numbers="#fff"
                        data-color-units="#fff"
                        data-value-box="false"
                        v-bind:data-major-ticks="'0, '+ event.budget/4 + ',' + event.budget/4*2 + ',' + event.budget/4*3 + ',' + event.budget"
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
            }
        }
    }
</script>

<style scoped lang="scss">
    $background-color: #009bbb;
    $value-color:       #fff;
    
    $title-color:       rgba(255, 255, 255, 0.7);
    $moreinfo-color:    rgba(255, 255, 255, 0.7);
    
    // ----------------------------------------------------------------------------
    // Widget-number styles
    // ----------------------------------------------------------------------------
    .widget-project {
        
        height: 350px;
        background-color: $background-color;
        
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
        
        .categories {
            margin: 14px;
        }
        
        .dial {
            display: inline;
        }
        
        .title {
            color: $title-color;
        }
        
        .reserve {
            color: $value-color;
        }
        
        .budget {
            color: $moreinfo-color;
        }
        
        .updated-at {
            color: rgba(0, 0, 0, 0.3);
        }
        
    }
</style>