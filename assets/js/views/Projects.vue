<template>
    <div>
        <grid-layout
                :layout="layout"
                :col-num="4"
                :row-height="350"
                :is-draggable="true"
                :is-resizable="true"
                :is-mirrored="false"
                :margin="[10, 10]"
                :use-css-transforms="true">
            <grid-item
                    :x="0"
                    :y="0"
                    :w="1"
                    :h="1"
                    :i="1">
                <project :event="eventProjetA"></project>
            </grid-item>
            <grid-item
                    :x="1"
                    :y="0"
                    :w="1"
                    :h="1"
                    :i="1">
                <project :event="eventProjetB"></project>
            </grid-item>
            <grid-item
                    :x="2"
                    :y="0"
                    :w="1"
                    :h="1"
                    :i="1">
                <project :event="eventProjetC"></project>
            </grid-item>
            <grid-item
                    :x="3"
                    :y="0"
                    :w="1"
                    :h="1"
                    :i="1">
                <project :event="eventProjetD"></project>
            </grid-item>
            <grid-item
                    :x="0"
                    :y="1"
                    :w="1"
                    :h="1"
                    :i="1">
                <project :event="eventProjetE"></project>
            </grid-item>
            <grid-item
                    :x="1"
                    :y="1"
                    :w="1"
                    :h="1"
                    :i="1">
                <project :event="eventProjetF"></project>
            </grid-item>
            <grid-item
                    :x="2"
                    :y="1"
                    :w="1"
                    :h="1"
                    :i="1">
                <project :event="eventProjetG"></project>
            </grid-item>
            <grid-item
                    :x="3"
                    :y="1"
                    :w="1"
                    :h="1"
                    :i="1">
                <project :event="eventProjetH"></project>
            </grid-item>
            <grid-item
                    :x="0"
                    :y="2"
                    :w="1"
                    :h="1"
                    :i="1">
                <project :event="eventProjetI"></project>
            </grid-item>
            <grid-item
                    :x="1"
                    :y="2"
                    :w="1"
                    :h="1"
                    :i="1">
                <project :event="eventProjetJ"></project>
            </grid-item>
            <grid-item
                    :x="2"
                    :y="2"
                    :w="1"
                    :h="1"
                    :i="1"></grid-item>
            <grid-item
                    :x="3"
                    :y="2"
                    :w="1"
                    :h="1"
                    :i="1"></grid-item>
        </grid-layout>
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