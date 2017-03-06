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
        }).then(response => response.json());
    },

    getLeaderboard() {
        return fetch('/api/games', {
            method: 'get',
            credentials: 'include',
            headers: new Headers({
                'Content-Type': 'application/json',
                // 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }),
            // body: JSON.stringify({})
        }).then(response => response.json());
    }

}