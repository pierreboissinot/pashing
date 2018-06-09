<template>
    <div>
        <h2>Sample</h2>
        <text-content
                v-bind:event="eventGitlabOpenedIssues"></text-content>
    </div>
</template>

<script>
    import text from '../components/text'
    
    export default {
        name: 'sample',
        components: {
            'text-content': text
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