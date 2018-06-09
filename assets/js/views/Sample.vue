<template>
    <div>
        <h2>Sample</h2>
        <number
                title="Opened issues"
                more-info="# of opened issues"
                v-bind:event="eventGitlabOpenedIssues"></number>
    </div>
</template>

<script>
    import number from '../components/number'
    
    export default {
        name: 'sample',
        components: {
            'number': number
        },
        data: () => ({
            eventGitlabOpenedIssues: {}
        }),
        mounted: function() {
            this.$nextTick(function() {
                this.setupStream();
            });
        },
        methods: {
            setupStream() {
                let es = new EventSource('/sample/events');
            
                es.addEventListener('event_gitlab', event => {
                    let data = JSON.parse(event.data);
                    this.eventGitlabOpenedIssues = data;
                }, false);
            
                es.addEventListener('error', event => {
                    if (event.readyState == EventSource.CLOSED) {
                        console.log('Event was closed');
                        console.log(EventSource);
                    }
                }, false);
            },
        }
    }
</script>

<style scoped lang="scss">
</style>