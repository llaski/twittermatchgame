'use strict';

export default {

    startGame() {
        return fetch('/api/games', {
            method: 'post',
            credentials: 'include',
            headers: new Headers({
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }),
            body: JSON.stringify({})
        }).then(response => {
            return response.json().catch(error => {
                //Error parsing json
                return new Promise((resolve, reject) => {
                    reject('Sorry about that, were not sure what happened! Please try again later.');
                })
            });
        });
    },

    getLeaderboard() {
        return fetch('/api/leaderboard', {
            method: 'get',
            credentials: 'include',
            headers: new Headers({
                'Content-Type': 'application/json'
            }),
        }).then(response => {
            return response.json().catch(error => {
                //Error parsing json
                return new Promise((resolve, reject) => {
                    reject('Sorry about that, were not sure what happened! Please try again later.');
                })
            });
        });
    }
}