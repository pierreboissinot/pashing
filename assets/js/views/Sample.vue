<template>
    <div>
        <h2>Sample</h2>
        <number
                title="Opened issues"
                more-info="# of opened issues"
                v-bind:event="eventGitlabOpenedIssues"></number>
        <number
                title="Closed issues"
                more-info="# of closed issues during last 30 days"
                v-bind:event="eventGitlabClosedIssues"></number>
        <number
                title="Stale issues"
                more-info="# of stale issues"
                v-bind:event="eventGitlabStaleIssues"></number>
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
            eventGitlabOpenedIssues: {},
            eventGitlabClosedIssues: {},
            eventGitlabStaleIssues: {}
        }),
        mounted: function() {
            this.$nextTick(function() {
                this.setupStream();
            });
        },
        methods: {
            setupStream() {
                let es = new EventSource('/sample/events');
            
                es.addEventListener('event_gitlab_opened_issues', event => {
                    let data = JSON.parse(event.data);
                    this.eventGitlabOpenedIssues = data;
                }, false);
    
                es.addEventListener('event_gitlab_closed_issues', event => {
                    let data = JSON.parse(event.data);
                    this.eventGitlabClosedIssues = data;
                }, false);
    
                es.addEventListener('event_gitlab_stale_issues', event => {
                    let data = JSON.parse(event.data);
                    this.eventGitlabStaleIssues = data;
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