<template>
    <div>
        <project :event="eventProjetA"></project>
        <project :event="eventProjetB"></project>
        <project :event="eventProjetC"></project>
        <project :event="eventProjetD"></project>
        <project :event="eventProjetE"></project>
        <project :event="eventProjetF"></project>
        <project :event="eventProjetG"></project>
        <project :event="eventProjetH"></project>
        <project :event="eventProjetI"></project>
        <project :event="eventProjetJ"></project>
    </div>
</template>

<script>
    let testLayout = [
    ];
    
    import project from '../components/project'
    import { GridLayout, GridItem } from 'vue-grid-layout/dist/vue-grid-layout.min'
    
    export default {
        name: 'projets',
        components: {
            'project': project,
            GridLayout,
            GridItem
        },
        data: () => ({
            layout: testLayout,
            eventProjetA: {},
            eventProjetB: {},
            eventProjetC: {},
            eventProjetD: {},
            eventProjetE: {},
            eventProjetF: {},
            eventProjetG: {},
            eventProjetH: {},
            eventProjetI: {},
            eventProjetJ: {},
        }),
        mounted: function() {
            this.$nextTick(function() {
                this.setupStream();
            });
        },
        methods: {
            setupStream() {
                let es = new EventSource('/projets/events');
                es.addEventListener('event_projets_a', event => {
                    let data = JSON.parse(event.data);
                    this.eventProjetA = data;
                }, false);
                es.addEventListener('event_projets_b', event => {
                    let data = JSON.parse(event.data);
                    this.eventProjetB = data;
                }, false);
                es.addEventListener('event_projets_c', event => {
                    let data = JSON.parse(event.data);
                    this.eventProjetC = data;
                }, false);
                es.addEventListener('event_projets_d', event => {
                    let data = JSON.parse(event.data);
                    this.eventProjetD = data;
                }, false);
                es.addEventListener('event_projets_e', event => {
                    let data = JSON.parse(event.data);
                    this.eventProjetE = data;
                }, false);
                es.addEventListener('event_projets_f', event => {
                    let data = JSON.parse(event.data);
                    this.eventProjetF = data;
                }, false);
                es.addEventListener('event_projets_g', event => {
                    let data = JSON.parse(event.data);
                    this.eventProjetG = data;
                }, false);
                es.addEventListener('event_projets_h', event => {
                    let data = JSON.parse(event.data);
                    this.eventProjetH = data;
                }, false);
                es.addEventListener('event_projets_i', event => {
                    let data = JSON.parse(event.data);
                    this.eventProjetI = data;
                }, false);
                es.addEventListener('event_projets_j', event => {
                    let data = JSON.parse(event.data);
                    this.eventProjetJ = data;
                }, false);
            },
        }
    }
</script>

<style scoped lang="scss">
</style>