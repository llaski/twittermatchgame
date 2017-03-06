<template>
    <div class="container">

        <div class="row">
            <div class="col">
                <h1 class="text-center" style="margin-top: 20px;">Twitter Match Game</h1>
            </div>
        </div>

        <div class="text-center" v-if="loading">
            <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
            <span class="sr-only">Loading...</span>
        </div>

        <div class="alert alert-danger text-center" role="alert" v-if="globalError">
          {{ globalError }}
        </div>

        <table class="table table-striped">
            <thead class="thead-inverse">
                <tr>
                    <th>Rank</th>
                    <th>Name</th>
                    <th># of Correct Matches</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="game in games" :class="{ 'table-active': game.highlight }">
                    <th>{{ game.rank }}</th>
                    <td>{{ game.name }}</td>
                    <td>{{ game.num_correct_answers }} / {{ game.total_questions }}</td>
                    <td>{{ game.time | secsToMins }}</td>
                </tr>
            </tbody>
        </table>
        <button class="btn btn-lg" @click="onPlayAgain">Play Again</button>
    </div>
</template>

<script>
    import API from '../API';
    import queryString from 'query-string';

    export default {
        data() {
            return {
                loading: true,
                games: [],
                globalError: null
            };
        },

        mounted() {
            API.getLeaderboard().then(data => {
                this.loading = false;
                this.games = data.games;

                this.initHighlightedGame();
            }).catch(error => {
                this.loading = false;
                this.globalError = error;
            });
        },

        methods: {
            initHighlightedGame() {
                const query = queryString.parse(location.search);

                if (!query.id) {
                    return;
                }

                const highlightedGame = this.games.find(game => game.id === parseInt(query.id));

                if (highlightedGame) {
                    highlightedGame.highlight = true;
                    return;
                }

                this.games.push({
                    id: query.id,
                    name: query.name,
                    num_correct_answers: query.num_correct_answers,
                    total_questions: query.total_questions,
                    time: query.time,
                    rank: query.rank,
                    highlight: true
                })
            },

            onPlayAgain() {
                this.$router.push('game');
            }
        },

        filters: {
            secsToMins(seconds) {
                const mins = Math.floor(seconds / 60);
                let formattedSecs = (seconds % 60);

                if (formattedSecs <= 9) {
                    formattedSecs = '0' + formattedSecs;
                }

                return `${mins}:${formattedSecs}`;
            }
        }
    };
</script>