/*===================================================================================================

 - PROJECT NAME : BDC-TIKIT
 - DESCRIPTION : MODERN BOOTSTRAP 4 ADMIN TEMPLATE - FULLY RESPONSIVE
 - AUTHOR : bdCoder
 - VERSION : 1.0
 - FILE : DASHBAORD JS

 ===================================================================================================*/
(function($)
{
    "use strict";
    $(document).ready(function () {


        //---------------------------------------------------------------------------------------------
        // - CHART ACTIVITY ---------------------------------------------------------------------------
        //---------------------------------------------------------------------------------------------

        var activity_chart_data = [
            {
                label: 'Secret File ',
                borderColor: "#4a4643",
                pointBackgroundColor: '#fff',
                pointBorderColor: '#4a4643',
                fill: false,
                data: [50, 30, 60, 55, 70, 90, 85, 55, 55, 20, 40]
            },
            {
                label: 'Secret Text ',
                borderColor: "#ef2b41",
                pointBackgroundColor: '#fff',
                fill: false,
                data: [10, 5, 30, 30, 45, 40, 30, 20, 20, 5, 0]
            }
        ];

        var activity_chart_data_dark = [
            {
                label: 'Secret File ',
                borderColor: "#cabfb7",
                pointBackgroundColor: '#444',
                pointBorderColor: '#cabfb7',
                fill: false,
                data: [50, 30, 60, 55, 70, 90, 85, 55, 55, 20, 40]
            },
            {
                label: 'Secret Text ',
                borderColor: "#ef2b41",
                pointBackgroundColor: '#444',
                fill: false,
                data: [10, 5, 30, 30, 45, 40, 30, 20, 20, 5, 0]
            }
        ];

        var activity_chart_config = {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: activity_chart_data
            },
            options: {
                elements: {
                    line: {
                        tension: 0
                    },
                    point: {
                        borderWidth: 3
                    }
                },
                scales: {
                    xAxes: [{
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            fontColor: "black"
                        }
                    }],
                    yAxes: [{
                        gridLines: {
                            display: false,
                            color: 'rgba(0,0,0,0.125)',
                            lineWidth: 4
                        },
                        ticks: {
                            fontColor: 'black',
                            min: 0,
                            max: 100,
                            stepSize: 25
                        }
                    }]
                },
                legend: {
                    display: false
                },
                tooltips: {
                    enabled: false,
                    mode: 'index',
                    position: 'nearest',
                    custom: function(tooltip) {

                        var tooltipEl = document.getElementById('activity-tooltip');

                        if (!tooltipEl) {
                            tooltipEl = document.createElement('div');
                            tooltipEl.id = 'activity-tooltip';
                            tooltipEl.classList.add('chartjs-tooltip');
                            tooltipEl.innerHTML = "<table></table>";
                            this._chart.canvas.parentNode.appendChild(tooltipEl);
                        }

                        if (tooltip.opacity === 0) {
                            tooltipEl.style.opacity = 0;
                            return;
                        }

                        tooltipEl.classList.remove('above', 'below', 'no-transform');
                        if (tooltip.yAlign) {
                            tooltipEl.classList.add(tooltip.yAlign);
                        } else {
                            tooltipEl.classList.add('no-transform');
                        }

                        function getBody(bodyItem) {
                            return bodyItem.lines;
                        }

                        if (tooltip.body) {
                            var titleLines = tooltip.title || [];
                            var bodyLines = tooltip.body.map(getBody);

                            var innerHtml = '<thead>';

                            titleLines.forEach(function(title) {
                                innerHtml += '<tr><th>' + title + '</th></tr>';
                            });
                            innerHtml += '</thead><tbody>';

                            bodyLines.forEach(function(body, i) {
                                var colors = tooltip.labelColors[i];
                                var style = 'background:' + colors.borderColor;
                                var span = '<span class="chartjs-tooltip-key" style="' + style + '"></span>';
                                innerHtml += '<tr><td>' + span + body + '</td></tr>';
                            });
                            innerHtml += '</tbody>';

                            var tableRoot = tooltipEl.querySelector('table');
                            tableRoot.innerHTML = innerHtml;
                        }

                        var positionY = this._chart.canvas.offsetTop;
                        var positionX = this._chart.canvas.offsetLeft;
                        tooltipEl.style.opacity = 1;
                        tooltipEl.style.left = positionX + tooltip.caretX + 'px';
                        tooltipEl.style.top = positionY + tooltip.caretY + 'px';
                    }
                },
                responsive: true,
                maintainAspectRatio: false
            }

        };

        var activity_chart_config_dark = {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: activity_chart_data_dark
            },
            options: {
                elements: {
                    line: {
                        tension: 0
                    },
                    point: {
                        borderWidth: 3
                    }
                },
                scales: {
                    xAxes: [{
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            fontColor: "white"
                        }
                    }],
                    yAxes: [{
                        gridLines: {
                            display: false,
                            color: 'rgba(255,255,255,0.125)',
                            lineWidth: 4
                        },
                        ticks: {
                            fontColor: 'white',
                            min: 0,
                            max: 100,
                            stepSize: 25
                        }
                    }]
                },
                legend: {
                    display: false
                },
                tooltips: {
                    enabled: false,
                    mode: 'index',
                    position: 'nearest',
                    custom: function(tooltip) {

                        var tooltipEl = document.getElementById('activity-tooltip');

                        if (!tooltipEl) {
                            tooltipEl = document.createElement('div');
                            tooltipEl.id = 'activity-tooltip';
                            tooltipEl.classList.add('chartjs-tooltip');
                            tooltipEl.innerHTML = "<table></table>";
                            this._chart.canvas.parentNode.appendChild(tooltipEl);
                        }

                        if (tooltip.opacity === 0) {
                            tooltipEl.style.opacity = 0;
                            return;
                        }

                        tooltipEl.classList.remove('above', 'below', 'no-transform');
                        if (tooltip.yAlign) {
                            tooltipEl.classList.add(tooltip.yAlign);
                        } else {
                            tooltipEl.classList.add('no-transform');
                        }

                        function getBody(bodyItem) {
                            return bodyItem.lines;
                        }

                        if (tooltip.body) {
                            var titleLines = tooltip.title || [];
                            var bodyLines = tooltip.body.map(getBody);

                            var innerHtml = '<thead>';

                            titleLines.forEach(function(title) {
                                innerHtml += '<tr><th>' + title + '</th></tr>';
                            });
                            innerHtml += '</thead><tbody>';

                            bodyLines.forEach(function(body, i) {
                                var colors = tooltip.labelColors[i];
                                var style = 'background:' + colors.borderColor;
                                var span = '<span class="chartjs-tooltip-key" style="' + style + '"></span>';
                                innerHtml += '<tr><td>' + span + body + '</td></tr>';
                            });
                            innerHtml += '</tbody>';

                            var tableRoot = tooltipEl.querySelector('table');
                            tableRoot.innerHTML = innerHtml;
                        }

                        var positionY = this._chart.canvas.offsetTop;
                        var positionX = this._chart.canvas.offsetLeft;
                        tooltipEl.style.opacity = 1;
                        tooltipEl.style.left = positionX + tooltip.caretX + 'px';
                        tooltipEl.style.top = positionY + tooltip.caretY + 'px';
                    }
                },
                responsive: true,
                maintainAspectRatio: false
            }

        };

        var activity_chart_ctx = document.getElementById("chart-line-activity").getContext("2d");

        if( $("#chart-line-activity").attr("data-style") !== "dark")
            new Chart(activity_chart_ctx, activity_chart_config);
        else
            new Chart(activity_chart_ctx, activity_chart_config_dark);

        //---------------------------------------------------------------------------------------------
        // - prototipo CALENDAR ------------------------------------------------------------------------------
        //---------------------------------------------------------------------------------------------

        var today = new Date(),
            month = today.getMonth() + 1,
            year = today.getFullYear(),
            notes =  [
                { "date": year + "-" + month + "-12", "time" : "15:45 AM", "content": "New Year" },
                { "date": year + "-" + month + "-25", "time" : "10:30 AM" , "content": "Christmas" }
            ];

        $("#calendar").prototipoCalendar({notes: notes});

        $("#calendar_dark").prototipoCalendar({notes: notes,theme: "light",backgroundColor : "#444"});

        //---------------------------------------------------------------------------------------------
        // - NOTIFICATION -----------------------------------------------------------------------------
        //---------------------------------------------------------------------------------------------

        $.notify("Hi John Doe !!  ", {align:"center", verticalAlign:"top",color: "#FFFFFF", background: "#4b5066"});


    });
})(jQuery);
