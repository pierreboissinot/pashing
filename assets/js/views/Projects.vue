<template>
    <div>
        <h2>Projets metrics</h2>
        <project
                v-bind:event="eventProjetA"></project>
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
            },
        }
    }
</script>

<style scoped lang="scss">
</style>