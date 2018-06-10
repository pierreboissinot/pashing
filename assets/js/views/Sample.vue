<template>
    <div>
        <h2>Sample</h2>
        <grid-layout
                :layout="layout"
                :col-num="6"
                :row-height="350"
                :is-draggable="true"
                :is-resizable="true"
                :is-mirrored="false"
                :margin="[10, 10]"
                :use-css-transforms="true"
        >
            <grid-item
                       :x="0"
                       :y="0"
                       :w="1"
                       :h="1"
                       :i="1">
                <number
                        title="Time spent"
                        more-info="wrike timelog for last 30 days"
                        v-bind:event="eventWrikeTimelog"></number>
            </grid-item>
            <grid-item
                    :x="1"
                    :y="0"
                    :w="1"
                    :h="1"
                    :i="2">
                <number
                        title="Opened issues"
                        more-info="# of opened issues"
                        v-bind:event="eventGitlabOpenedIssues"></number>
            </grid-item>
            <grid-item
                    :x="2"
                    :y="0"
                    :w="1"
                    :h="1"
                    :i="3">
                <number
                        title="Closed issues"
                        more-info="# of closed issues during last 30 days"
                        v-bind:event="eventGitlabClosedIssues"></number>
            </grid-item>
            <grid-item
                    :x="3"
                    :y="0"
                    :w="1"
                    :h="1"
                    :i="4">
                <number
                        title="Stale issues"
                        more-info="# of stale issues"
                        v-bind:event="eventGitlabStaleIssues"></number>
            </grid-item>
            <grid-item
                    :x="4"
                    :y="0"
                    :w="1"
                    :h="1"
                    :i="5">
                <number
                        title="All issues"
                        more-info="# of issues during last 30 days"
                        v-bind:event="eventGitlabStaleIssues"></number>
            </grid-item>
            <grid-item
                    :x="5"
                    :y="0"
                    :w="1"
                    :h="1"
                    :i="6">
                <number
                        title="New issues"
                        more-info="# of created issues during last 30 days"
                        v-bind:event="eventGitlabNewIssues"></number>
            </grid-item>
        </grid-layout>
    </div>
</template>

<script>
    let testLayout = [
    ];
    
    import number from '../components/number'
    import { GridLayout, GridItem } from 'vue-grid-layout/dist/vue-grid-layout.min'
    
    export default {
        name: 'sample',
        components: {
            'number': number,
            GridLayout,
            GridItem
        },
        data: () => ({
            layout: testLayout,
            eventGitlabOpenedIssues: {},
            eventGitlabClosedIssues: {},
            eventGitlabStaleIssues: {},
            eventGitlabAllIssues: {},
            eventGitlabNewIssues: {},
            eventWrikeTimelog: {}
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
    
                es.addEventListener('event_gitlab_all_issues', event => {
                    let data = JSON.parse(event.data);
                    this.eventGitlabAllIssues = data;
                }, false);
    
                es.addEventListener('event_gitlab_new_issues', event => {
                    let data = JSON.parse(event.data);
                    this.eventGitlabNewIssues = data;
                }, false);
    
                es.addEventListener('event_wrike_timelog', event => {
                    let data = JSON.parse(event.data);
                    this.eventWrikeTimelog = data;
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