<template>
    <div class="easy-content">
        <p>
            {{ text }}
        </p>
    </div>
</template>

<script>
    export default {
        props: ['esurl'],
        data: () => ({
            text: 'waiting'
        }),
        mounted: function() {
            this.$nextTick(function() {
                this.setupStream();
            });
        },
        methods: {
            setupStream() {
                let es = new EventSource(this.esurl);
        
                es.addEventListener('event_gitlab', event => {
                    let data = JSON.parse(event.data);
                    this.text = data.current;
                }, false);
                
                es.addEventListener('error', event => {
                    if (event.readyState == EventSource.CLOSED) {
                        console.log('Event was closed');
                        console.log(EventSource);
                    }
                }, false);
            }
        }
    }
</script>

<style>
    .easy-content {
        text-align: center;
        padding-top: 200px;
    }
    .easy-content p:first-child {
        color: #1abc9c;
        font-size: 25px;
    }
    .easy-content p:last-child {
        padding-top: 20px;
        color: #bbb;
    }
</style>