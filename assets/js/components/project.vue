<template>
    <div class="widget widget-project">
        <h1 class="title">{{ event.title }}</h1>
    
        <div class="wrapper">
            <div class="column-one">
                <h2 class="reserve">{{ event.reserve }} € HT</h2>
                <p>réserve</p>
                
                <p class="budget">budget: {{ event.budget }} € HT</p>
            </div>
            <div class="column-two">
                <canvas data-type="linear-gauge"
                        v-bind:data-value="event.reserve"
                        data-min-value="0"
                        v-bind:data-max-value="event.budget"
                        data-units="€"
                        data-tick-side="right"
                        data-borders="false"
                        data-bar-begin-circle="false"
                        data-stroke-ticks="true"
                        data-width="250"
                        data-height="400"
                        data-color-plate="#009bbb"
                        data-color-numbers="#fff"
                        data-color-units="#fff"
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