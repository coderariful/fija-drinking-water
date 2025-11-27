<script>
    /*===================================================================================================
 - DESCRIPTION : MODERN BOOTSTRAP 4 ADMIN TEMPLATE - FULLY RESPONSIVE
 - AUTHOR : olfytech (http://www.olfytech.com/)
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
            var line_chart_data =  {
                labels: @json($salesGraph->pluck('date')),
                datasets: [
                    {
                        label: 'Sale',
                        backgroundColor: "rgba(239,43,65,0.7)",
                        borderColor: "#ef2b41",
                        borderWidth: 1,
                        data: @json($salesGraph->pluck('sale'))
                    },
                    {
                        label: 'Payment',
                        backgroundColor: "rgba(241,61,81,0.7)",
                        borderColor: "#ef475a",
                        borderWidth: 1,
                        data: @json($salesGraph->pluck('paid'))
                    },
                    {
                        label: 'Due',
                        backgroundColor: "rgba(189,22,41,0.7)",
                        borderColor: "#ae1324",
                        borderWidth: 1,
                        data: @json($salesGraph->pluck('due'))
                    }
                ]
            };

            var line_chart_config = {
                type: 'line',
                data: line_chart_data,
                options: {
                    responsive: true,
                    legend: {
                        position: 'top'
                    },
                    title: {
                        display: true,
                        text: 'Daily Sales Overview'
                    }
                }
            };

            var line_chart_ctx = document.getElementById("chart-line-activity").getContext("2d");

            new Chart(line_chart_ctx, line_chart_config);
            //---------------------------------------------------------------------------------------------
            // -CALENDAR ------------------------------------------------------------------------------
            //---------------------------------------------------------------------------------------------

            var today = new Date(),
                month = today.getMonth() + 1,
                year = today.getFullYear(),
                notes =  [
                    { "date": year + "-" + month + "-12", "time" : "15:45 AM", "content": "New Year" },
                    { "date": year + "-" + month + "-25", "time" : "10:30 AM" , "content": "Christmas" }
                ];

            $("#calendar_dark").prototipoCalendar({notes: notes,theme: "light",backgroundColor : "#444"});

            //---------------------------------------------------------------------------------------------
            // - NOTIFICATION -----------------------------------------------------------------------------
            //---------------------------------------------------------------------------------------------

            {{--$.notify("Hi {{Auth::user()->name}} !!  ", {align:"center", verticalAlign:"top",color: "#FFFFFF", background: "#4b5066"});--}}
        });
    })(jQuery);


</script>
