<template>
    <div class="widget widget-project">
        <h1 class="title">{{ event.title }}</h1>
        <div class="wrapper">
            <div class="column-one">
                <div class="main-infos">
                    <h2 class="reserve">{{ event.reserve }} € HT</h2>
                    <p>réserve</p>
                    
                    <p class="budget">budget: {{ event.budget }} € HT</p>
                </div>
                <div class="categories">
                    <canvas data-type="radial-gauge"
                            data-title="Pilotage"
                            data-width="150"
                            data-height="150"
                            data-units="€"
                            data-min-value="0"
                            data-start-angle="90"
                            data-ticks-angle="180"
                            data-value-box="true"
                            v-bind:data-max-value="event.budgetPilotage"
                            v-bind:data-value="event.pilotage"
                            v-bind:data-major-ticks="'0,' + event.budgetPilotage/4 + ',' + event.budgetPilotage/2 + ',' + event.budgetPilotage/4*3 + ',' + event.budgetPilotage"
                            data-stroke-ticks="true"
                            data-color-plate="#fff"
                            data-border-shadow-width="0"
                            data-borders="false"
                            data-needle-type="arrow"
                            data-needle-circle-size="7"
                            data-needle-circle-outer="true"
                            data-needle-circle-inner="false"
                            data-animation-duration="1500"
                            data-animation-rule="linear"
                    ></canvas>
                    <canvas data-type="radial-gauge"
                            data-title="Conception"
                            data-width="150"
                            data-height="150"
                            data-units="€"
                            data-min-value="0"
                            data-start-angle="90"
                            data-ticks-angle="180"
                            data-value-box="true"
                            v-bind:data-max-value="event.budgetConception"
                            v-bind:data-value="event.conception"
                            v-bind:data-major-ticks="'0,' + event.budgetConception/4 + ',' + event.budgetConception/2 + ',' + event.budgetConception/4*3 + ',' + event.budgetConception"
                            data-stroke-ticks="true"
                            data-color-plate="#fff"
                            data-border-shadow-width="0"
                            data-borders="false"
                            data-needle-type="arrow"
                            data-needle-circle-size="7"
                            data-needle-circle-outer="true"
                            data-needle-circle-inner="false"
                            data-animation-duration="1500"
                            data-animation-rule="linear"
                    ></canvas>
                    <canvas data-type="radial-gauge"
                            data-title="Réalisation"
                        data-width="150"
                        data-height="150"
                        data-units="€"
                        data-min-value="0"
                        data-start-angle="90"
                        data-ticks-angle="180"
                        data-value-box="true"
                        v-bind:data-max-value="event.budgetRealisation"
                        v-bind:data-value="event.realisation"
                        v-bind:data-major-ticks="'0,' + event.budgetRealisation/4 + ',' + event.budgetRealisation/2 + ',' + event.budgetRealisation/4*3 + ',' + event.budgetRealisation"
                        data-stroke-ticks="true"
                        data-color-plate="#fff"
                        data-border-shadow-width="0"
                        data-borders="false"
                        data-needle-type="arrow"
                        data-needle-circle-size="7"
                        data-needle-circle-outer="true"
                        data-needle-circle-inner="false"
                        data-animation-duration="1500"
                        data-animation-rule="linear"
                ></canvas>
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
                        data-stroke-ticks="true"
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
    export default {
        props: ['event'],
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