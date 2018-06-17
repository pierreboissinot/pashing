<template>
    <div class="widget widget-project">
        <h1 class="title">{{ event.title }}</h1>
    
        <h2 class="reserve">{{ event.reserve }} € HT</h2>
        <p>réserve</p>
        
        <p class="budget">budget: {{ event.budget }} € HT</p>
        
        
        <p class="updated-at">{{ new Date(event.updatedAt*1000).toLocaleTimeString() }}</p>
    </div>
</template>

<script>
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