/*globals Chart:false, feather:false */ 

(function () {
    'use strict';

$.get('../inc/users.inc.php?key', function (users) {
        users.forEach(user => {
            $('#users').append(render(user));
        })
    })

function render(user) {
    let name = user.usersFirstname+' '+user.usersSecondname;
return `
       <tr>
         <td>${user.idusers}</td>
         <td>${user.uidusers}</td>
         <td>${name}</td>
         <td>${null}</td>
         <td>${user.date_joined}</td> 
       </tr>
    `;
    }

function chart(){
    console.log('hello') 
    // Graphs
var ctx = document.getElementById('myChart')
    // eslint-disable-next-line no-unused-vars

    let myChart = new Chart(ctx, { 
        type: 'line',
        data: {
            labels: [
        'Sunday',
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday'
      ],
            datasets: [{
                data: [
          153,
          213,
          18,
          24,
          23,
          24,
          12
        ],
                lineTension: 0,
                backgroundColor: 'transparent',
                borderColor: 'rgba(67, 22, 228, 0.844)',
                borderWidth: 4,
                pointBackgroundColor: 'rgb(214, 211, 211)'
      }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: false
                    }
        }]
            },
            legend: {
                display: false
            }
        }
    })
 }    
$('#w').click(chart); 
chart();
}())

