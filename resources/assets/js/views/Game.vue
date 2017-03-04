<template>
    <div class="container">
        <div class="row">
            <div class="col">
                <h1 class="text-center" style="margin-top: 20px;">Twitter Match Game</h1>
                <p class="text-right">{{ timeRemaining | secsToMins }} Remaining</p>
            </div>
        </div>

        <div class="text-center" v-if="loading">
            <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
            <span class="sr-only">Loading...</span>
        </div>

        <template v-if="!loading" v-for="(tweet, handle) in game.tweets">
            <div class="row">
                <div class="col drag-item handle-item">
                    <p>{{ handle }}</p>
                </div>
                <div class="col drag-item tweet-item">
                    <p>{{ tweet }}</p>
                </div>
            </div>
        </template>

        <div class="row" v-if="true || (!loading && gameComplete)">
            <div class="col-xs-12 col-md-8 offset-md-2" style="margin-top: 20px;">
                <form @submit.prevent="onSubmit" @keydown="form.errors.clear($event.target.name)">
                    <p>Submit your results to see where you stack up on the leaderboard!</p>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="name" class="form-control" id="name" placeholder="Name..." v-model="form.name">
                        <div class="alert alert-danger" role="alert" v-if="form.errors.has('name')" v-text="form.errors.get('name')" style="margin-top: 10px;"></div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" class="form-control" id="email" placeholder="Email..." v-model="form.email">
                        <div class="alert alert-danger" role="alert" v-if="form.errors.has('email')" v-text="form.errors.get('email')" style="margin-top: 10px;"></div>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn" value="Submit Results">
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import API from '../API';
    import Form from '../lib/Form';

    export default {
        data() {
            return {
                loading: true,
                timeRemaining: 5 * 60, //5 minutes
                game: {},
                form: new Form({
                    name: '',
                    email: '',
                    results: []
                })
            };
        },

        computed: {
            gameComplete() {
                return this.timeRemaining <= 0;
            }
        },

        mounted() {
            API.startGame().then(data => {
                this.loading = false;
                this.game = data;
                this.runTimer();
            }).catch(error => {
                console.error(error);
            });
        },

        methods: {
            runTimer() {
                setTimeout(() => {
                    if (this.timeRemaining === 0) {
                        return;
                    }

                    this.timeRemaining--;
                    this.runTimer();
                }, 1000);
            },

            onSubmit() {
                this.form.results = this.game.tweets; //fix this

                this.form.submit(`/api/games/${this.game.id}`, 'PUT')
                    .then(responseData => {
                        console.log(responseData);
                        this.$router.push('leaderboard');
                    }).catch(error => {
                        console.log(error);
                    })
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