# twittermatchgame

http://twittergame.larrylaski.com

This game is designed to allow users to match twitter handles and tweets and see how long it takes them to get all 10 right. They can also view a leaderboard to see where they stand. This project uses Laravel, MySQL, the Twitter API, web scraping to get the most followed twitter accounts, Vue JS, jQuery UI, and Laravel Mix.

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

- [X] Store rank in database and update on game submission instead of always needing to query for it
- [X] Visual error message instead of console.error for api requests
- [X] Setup live site

-----

- [ ] Instead of fetching twitter data on every game, create a cron job that gathers data for the top 100 accounts and stores it locally every hour to use for game data
- [ ] Add JS Tests
- [ ] Better Visual Design
- [ ] Navigation to always view leaderboard w/out needing to play a game
- [ ] Move ranking logic into separate job or its own mysql stored procedure. Basically better option than running individual queries for all games.
