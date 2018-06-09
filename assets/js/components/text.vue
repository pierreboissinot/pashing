<template>
    <div class="easy-content">
        <p>
            {{ event.current }}
        </p>
    </div>
</template>

<script>
    export default {
        props: ['event'],
    }
</script>

<style>
    .easy-content {
        text-align: center;
        padding-top: 200px;
    }
    .easy-content p:first-child {
        color: #1abc9c;
        font-size: 25px;
    }
    .easy-content p:last-child {
        padding-top: 20px;
        color: #bbb;
    }
</style>