<template>
    <div>
        <!--
        <h1>Dashboard</h1>
        <p>
            <router-link to="/cest-pas-sorcier">C'est pas sorcier</router-link>
            <router-link to="/projets">Projets</router-link>
        </p>
        -->
        <!-- route outlet -->
        <!-- component matched by the route will render here -->
        <router-view></router-view>
    </div>
</template>

<script>

</script>

<style>
    #app {
        font-family: 'Avenir', Helvetica, Arial, sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-align: center;
        color: #2c3e50;
        margin-top: 60px;
    }
</style>