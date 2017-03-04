<template>
    <div class="container">
        <div class="jumbotron text-center">
            <h1>Twitter Match Game</h1>
            <p>The goal of the game is to match twitter handles to a tweet sent from the handle in the least # of tries. Think you have what it takes?</p>
            <br>
            <button class="btn btn-lg" @click="startGame">Get Started</button>
        </div>
    </div>
</template>

<script>
    export default {
        methods: {
            startGame() {
                this.$router.push('game');
            }
        }
    };
</script>