<template>
    <div class="widget-number">
        <h1 class="title">{{ title }}</h1>
        
        <h2 class="value">{{ event.current }}</h2>
        
        <p class="more-info">{{ moreInfo }}</p>
        
        <p class="updated-at">{{ new Date(event.updatedAt*1000).toLocaleTimeString() }}</p>
    </div>
</template>

<script>
    export default {
        props: ['event', 'title', 'moreInfo'],
    }
</script>

<style scoped lang="scss">
    $background-color:  #47bbb3;
    $value-color:       #fff;
    
    $title-color:       rgba(255, 255, 255, 0.7);
    $moreinfo-color:    rgba(255, 255, 255, 0.7);
    
    // ----------------------------------------------------------------------------
    // Widget-number styles
    // ----------------------------------------------------------------------------
       .widget-number {
            height: 330px;
           background-color: $background-color;
    
    .title {
        color: $title-color;
    }
    
    .value {
        color: $value-color;
    }
    
    .change-rate {
        font-weight: 500;
        font-size: 30px;
        color: $value-color;
    }
    
    .more-info {
        color: $moreinfo-color;
    }
    
    .updated-at {
        color: rgba(0, 0, 0, 0.3);
    }
    
    }
</style>