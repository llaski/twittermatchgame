<template>
    <div class="container">
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
                <tr v-for="rank in 20">
                    <th>{{ rank }}</th>
                    <td>Tony Stark</td>
                    <td>9/10</td>
                    <td>2:22</td>
                </tr>
                <tr class="table-active">
                    <th>105</th>
                    <td>Tony Stark</td>
                    <td>8/10</td>
                    <td>5:37</td>
                </tr>
            </tbody>
        </table>
        <button class="btn btn-lg">Play Again</button>
    </div>
</template>