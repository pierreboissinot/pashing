<template>
    <div>
        <ul id="projects">
            <project v-for="(project, index) in projects"
                     :key="index"
                    v-bind:project-id="project"
                    v-bind:event-source="es"></project>
        </ul>
    </div>
</template>

<script>
    let testLayout = [
    ];
    
    import project from '../components/project'
    import axios from 'axios';
    
    export default {
        name: 'projets',
        components: {
            'project': project,
        },
        data: () => ({
            layout: testLayout,
            projects: [],
            es: null,
        }),
        mounted: function() {
            this.$nextTick(function() {
                this.setupStream();
            });
        },
        methods: {
            setupStream() {
                axios.get('/projets/list')
                    .then((response) => {
                        let ids = response.data;
                        this.projects = ids;
                        this.es = new EventSource(`/projets/events`);
                    })
                    .catch((error) => {
                        console.log(error);
                    })
                    .then(() => {
                        console.log('GET /projets/list finished');
                    });
            },
        }
    }
</script>

<style scoped lang="scss">
    #projects {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
    }
</style>