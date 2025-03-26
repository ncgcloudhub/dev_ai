/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: Chartjs init js
*/


// get colors array from the string
function getChartColorsArray(chartId) {
    if (document.getElementById(chartId) !== null) {
        var colors = document.getElementById(chartId).getAttribute("data-colors");
        colors = JSON.parse(colors);
        return colors.map(function (value) {
            var newValue = value.replace(" ", "");
            if (newValue.indexOf(",") === -1) {
                var color = getComputedStyle(document.documentElement).getPropertyValue(newValue);
                if (color) return color; else return newValue;;
            } else {
                var val = value.split(',');
                if(val.length == 2){
                    var rgbaColor = getComputedStyle(document.documentElement).getPropertyValue(val[0]);
                    rgbaColor = "rgba("+rgbaColor+","+val[1]+")";
                    return rgbaColor;
                } else {
                    return newValue;
                }
            }
        });
    }
}

Chart.defaults.borderColor= "rgba(133, 141, 152, 0.1)";
Chart.defaults.color= "#858d98";

// line chart
var islinechart = document.getElementById('lineChart');
lineChartColor =  getChartColorsArray('lineChart');
if(lineChartColor){
islinechart.setAttribute("width", islinechart.parentElement.offsetWidth);

var lineChart = new Chart(islinechart, {
    type: 'line',
    data: {
        labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October"],
        datasets: [
            {
                label: "Sales Analytics",
                fill: true,
                lineTension: 0.5,
                backgroundColor: lineChartColor[0],
                borderColor: lineChartColor[1],
                borderCapStyle: 'butt',
                borderDash: [],
                borderDashOffset: 0.0,
                borderJoinStyle: 'miter',
                pointBorderColor: lineChartColor[1],
                pointBackgroundColor: "#fff",
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: lineChartColor[1],
                pointHoverBorderColor: "#fff",
                pointHoverBorderWidth: 2,
                pointRadius: 1,
                pointHitRadius: 10,
                data: [65, 59, 80, 81, 56, 55, 40, 55, 30, 80]
            },
            {
                label: "Monthly Earnings",
                fill: true,
                lineTension: 0.5,
                backgroundColor: lineChartColor[2],
                borderColor: lineChartColor[3],
                borderCapStyle: 'butt',
                borderDash: [],
                borderDashOffset: 0.0,
                borderJoinStyle: 'miter',
                pointBorderColor: lineChartColor[3],
                pointBackgroundColor: "#fff",
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: lineChartColor[3],
                pointHoverBorderColor: "#eef0f2",
                pointHoverBorderWidth: 2,
                pointRadius: 1,
                pointHitRadius: 10,
                data: [80, 23, 56, 65, 23, 35, 85, 25, 92, 36]
            }
        ]
    },
    options: {
        x: {
            ticks: {
                font: {
                    family: 'Poppins',
                },
            },
        },
        y: {
            ticks: {
                font: {
                    family: 'Poppins',
                },
            },
        },
        plugins: {
            legend: {
                labels: {
                    // This more specific font property overrides the global property
                    font: {
                        family: 'Poppins',
                    }
                }
            },
        },
    },
    
});
}

// bar chart
document.addEventListener("DOMContentLoaded", function () {
    fetch('/user/monthly-usage') // Ensure the route exists in Laravel
        .then(response => response.json())
        .then(data => {
            var isbarchart = document.getElementById('bar');
            var barChartColor = getChartColorsArray('bar');

            if (isbarchart && barChartColor) {
                isbarchart.setAttribute("width", isbarchart.parentElement.offsetWidth);

                var barChart = new Chart(isbarchart, {
                    type: 'bar',
                    data: {
                        labels: data.labels, // Month names from backend
                        datasets: [
                            {
                                label: "Tokens Used",
                                backgroundColor: barChartColor[0],
                                borderColor: barChartColor[0],
                                borderWidth: 1,
                                hoverBackgroundColor: barChartColor[1],
                                hoverBorderColor: barChartColor[1],
                                data: data.tokens // Tokens used dynamically
                            },
                            {
                                label: "Credits Used",
                                backgroundColor: barChartColor[2],
                                borderColor: barChartColor[2],
                                borderWidth: 1,
                                hoverBackgroundColor: barChartColor[3],
                                hoverBorderColor: barChartColor[3],
                                data: data.credits // Credits used dynamically
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                ticks: {
                                    font: {
                                        family: 'Poppins',
                                    },
                                },
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    font: {
                                        family: 'Poppins',
                                    },
                                },
                            },
                        },
                        plugins: {
                            legend: {
                                labels: {
                                    font: {
                                        family: 'Poppins',
                                    }
                                }
                            },
                        }
                    }
                });
            }
        })
        .catch(error => console.error('Error loading chart data:', error));
});


// pie chart
var ispiechart = document.getElementById('pieChart');
pieChartColors =  getChartColorsArray('pieChart');
if(pieChartColors){

var pieChart = new Chart(ispiechart, {
    type: 'pie',
    data: {
        labels: [
            "Desktops",
            "Tablets"
        ],
        datasets: [
            {
                data: [300, 180],
                backgroundColor: pieChartColors,
                hoverBackgroundColor: pieChartColors,
                hoverBorderColor: "#fff"
            }]
    },
    options: {
        plugins: {
            legend: {
                labels: {
                    font: {
                        family: 'Poppins',
                    }
                }
            },
        }
    }
});
}

var isdoughnutchart = document.getElementById('doughnut');
doughnutChartColors =  getChartColorsArray('doughnut');
if(doughnutChartColors){
var lineChart = new Chart(isdoughnutchart, {
    type: 'doughnut',
    data: {
        labels: [
            "Desktops",
            "Tablets"
        ],
        datasets: [
            {
                data: [300, 210],
                backgroundColor: doughnutChartColors,
                hoverBackgroundColor: doughnutChartColors,
                hoverBorderColor: "#fff"
            }]
    },
    options: {
        plugins: {
            legend: {
                labels: {
                    font: {
                        family: 'Poppins',
                    }
                }
            },
        }
    }
});
}

// polarArea chart
var ispolarAreachart = document.getElementById('polarArea');
polarAreaChartColors =  getChartColorsArray('polarArea');
if(polarAreaChartColors){
var lineChart = new Chart(ispolarAreachart, {
    type: 'polarArea',
    data: {
        labels: [
            "Series 1",
            "Series 2",
            "Series 3",
            "Series 4"
        ],
        datasets: [{
            data: [
                11,
                16,
                7,
                18
            ],
            backgroundColor: polarAreaChartColors,
            label: 'My dataset', // for legend
            hoverBorderColor: "#fff"
        }]
    },
    options: {
        plugins: {
            legend: {
                labels: {
                    font: {
                        family: 'Poppins',
                    }
                }
            },
        }
    }
});
}

// radar chart
var isradarchart = document.getElementById('radar');
radarChartColors =  getChartColorsArray('radar');
if(radarChartColors){
var lineChart = new Chart(isradarchart, {
    type: 'radar',
    data: {
        labels: ["Eating", "Drinking", "Sleeping", "Designing", "Coding", "Cycling", "Running"],
        datasets: [
            {
                label: "Desktops",
                backgroundColor: radarChartColors[0], //"rgba(42, 181, 125, 0.2)",
                borderColor: radarChartColors[1], //"#2ab57d",
                pointBackgroundColor: radarChartColors[1], //"#2ab57d",
                pointBorderColor: "#fff",
                pointHoverBackgroundColor: "#fff",
                pointHoverBorderColor: radarChartColors[1], //"#2ab57d",
                data: [65, 59, 90, 81, 56, 55, 40]
            },
            {
                label: "Tablets",
                backgroundColor: radarChartColors[2], //"rgba(81, 86, 190, 0.2)",
                borderColor: radarChartColors[3], //"#5156be",
                pointBackgroundColor: radarChartColors[3], //"#5156be",
                pointBorderColor: "#fff",
                pointHoverBackgroundColor: "#fff",
                pointHoverBorderColor: radarChartColors[3], //"#5156be",
                data: [28, 48, 40, 19, 96, 27, 100]
            }
        ]
    },
    options: {
        plugins: {
            legend: {
                labels: {
                    font: {
                        family: 'Poppins',
                    }
                }
            },
        }
    }
});
}