# twittermatchgame

## Backend Features

- [x] Serve home page
- [x] Get list of matching twitter handles and tweets
- [x] Submit results with name and email

## Frontend Features

- [X] Click start btn
- [X] Request game data
- [X] Display game data (and mix it up)
- [X] Drag n Drop handles onto tweets
- [X] Display form once all items are matched or once timer runs out
- [X] View Leaderboard

## Cleanup/Add Ons

- [ ] Store rank in database and update on game submission instead of always needing to query for it
- [ ] Visual error message instead of console.error for api requests
- [ ] Instead of fetching twitter data on every game, create a cron job that gathers data for the top 100 accounts and stores it locally every hour to use for game data
- [ ] Add JS Tests
- [ ] Better Visual Design
- [ ] Navigation to always view leaderboard w/out needing to play a game
