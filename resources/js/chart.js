var chart = document.getElementById('hits');
var ctx = chart.getContext('2d');
var hits = JSON.parse(chart.getAttribute('data-hits'));

new Chart(ctx, {
    type: 'line',
    data: {
        labels: Object.keys(hits),
        datasets: [
            {
                label: "Hits",
                data: Object.values(hits),
                fill: true,
                borderColor: 'rgb(75, 192, 192)',
                lineTension: 0.1
            }
        ]
    },
    options: {
        scales: {
            yAxes: [{
                stacked: true
            }]
        }
    }
});
