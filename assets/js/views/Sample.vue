<template>
    <div>
        <h2>Sample</h2>
        <text-content esurl="/sample/events"></text-content>
    </div>
</template>

<script>
    import text from '../components/text'
    
    export default {
        name: 'sample',
        components: {
            'text-content': text
        }
    }
</script>

<style scoped lang="scss">
</style>