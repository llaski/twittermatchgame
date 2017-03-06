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

        <div v-if="!loading" v-for="(item, index) in gameDataTweets" class="container game-container">
            <div class="row">
                <div v-if="!item.matched" class="col drag-item handle-item" :data-handle="item.randomHandle">
                    <p>{{ item.randomHandle }}</p>
                </div>
                <div class="col drag-item tweet-item" :class="{ matched: item.matched }" :data-tweet="item.tweet">
                    <p v-if="item.matched">{{ item.handle }}</p>
                    <p>{{ item.tweet }}</p>
                </div>
            </div>
        </div>

        <div class="row" v-if="!loading && gameComplete">
            <div class="col-xs-12 col-md-8 offset-md-2" style="margin-top: 20px;">
                <form @submit.prevent="onSubmit" @keydown="form.errors.clear($event.target.name)">
                    <p v-if="allItemsMatched">Congrats! You completed the game in {{ timeRemaining | secsToMins }}.</p>
                    <p v-if="!allItemsMatched">Sorry - You ran out of time! You completed {{ numItemsMatched }} of {{ gameDataTweets.length }}</p>
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
    import Arr from '../lib/Arr';
    import queryString from 'query-string';

    export default {
        data() {
            return {
                loading: true,
                timeRemaining: 5 * 60, //5 minutes
                currentTimer: null,
                gameDataId: null,
                gameDataTweets: [],
                form: new Form({
                    name: '',
                    email: '',
                    time: null,
                    results: []
                })
            };
        },

        computed: {
            numItemsMatched() {
                return this.gameDataTweets.filter(item => item.matched).length;
            },

            allItemsMatched() {
                return this.gameDataTweets.length === this.numItemsMatched;
            },

            gameComplete() {
                return this.timeRemaining <= 0 || this.allItemsMatched;
            }
        },

        mounted() {
            API.startGame().then(data => {
                this.loading = false;
                this.setInitialGameData(data);

                this.runTimer();

                Vue.nextTick(() => {
                    this.initDraggable();
                    this.initDroppable();
                });
            }).catch(error => {
                console.error(error);
            });
        },

        methods: {
            initDraggable() {
                window.$('.handle-item').draggable({
                    revert: true,
                    revertDuration: 0,
                    cursor: 'move',
                    zIndex: 100
                });
            },

            initDroppable() {
                window.$('.tweet-item').droppable({
                    accept: ".handle-item",
                    addClasses: false,
                    classes: {
                        'ui-droppable-hover': 'ui-state-highlight'
                    },
                    drop: (event, ui) => {
                        const handle = ui.draggable.get(0).dataset.handle;
                        const tweet = event.target.dataset.tweet;

                        //Try to find an item that matches the chosen handle and tweet
                        const matchedGameTweetItem = this.gameDataTweets.find(item => item.tweet === tweet && item.handle === handle);

                        //If it's a match, set matched to true
                        if (matchedGameTweetItem) {
                            matchedGameTweetItem.matched = true;

                            //If the item's random handle is not the same as the matched handle, need to swap the random handles so the unmatched handle still appears
                            if (matchedGameTweetItem.randomHandle !== handle) {
                                const swampGameDataTweetItem = this.gameDataTweets.find(item => item.randomHandle === handle && !item.matched);

                                swampGameDataTweetItem.randomHandle = matchedGameTweetItem.randomHandle;
                            }
                        }
                    }
                });
            },

            setInitialGameData(data) {
                this.gameDataId = data.id;

                const randomizedHandles = Arr.shuffle(data.tweets.map(item => item.handle));

                //Add a random handle per item so we can display a random handle and give the appearance of random items for the user to match
                this.gameDataTweets = data.tweets.map((item, index) => {
                    item.randomHandle = randomizedHandles[index];
                    item.matched = false;

                    return item;
                });
            },

            runTimer() {
                setTimeout(() => {
                    if (this.gameComplete) {
                        return;
                    }

                    this.timeRemaining--;
                    this.runTimer();
                }, 1000);
            },

            onSubmit() {
                this.form.results = this.gameDataTweets.reduce((carry, item) => {
                    if (item.matched) {
                        carry[item.handle] = item.tweet;
                    } else {
                        carry[item.randomHandle] = item.tweet;
                    }

                    return carry;
                }, {});

                this.form.time = this.timeRemaining;

                this.form.submit(`/api/games/${this.gameDataId}`, 'PUT')
                    .then(responseData => {
                        this.$router.push(`leaderboard?${queryString.stringify(responseData)}`);
                    }).catch(error => {
                        console.log(error);
                    });
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