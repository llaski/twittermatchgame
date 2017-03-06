'use strict';

export default {
    shuffle(array) {
        array = array.slice(0);
        for (let i = array.length; i; i--) {
            let j = Math.floor(Math.random() * i);
            [array[i - 1], array[j]] = [array[j], array[i - 1]];
        }

        return array;
    }
};