<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light" data-footer="dark">

<head>

    <meta charset="utf-8">
    <title>Gestion des Affectations</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="" name="description">
    <meta content="Lelouch" name="author">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('../assets/images/favicon.ico') }}">

    <link rel="stylesheet" href="{{ asset('../assets/libs/swiper/swiper-bundle.min.css') }}">

    <!-- Layout config Js -->
    <script src="{{ asset('../assets/js/layout.js') }}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ asset('../assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <!-- Icons Css -->
    <link href="{{ asset('../assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">
    <!-- App Css-->
    <link href="{{ asset('../assets/css/app.min.css') }}" rel="stylesheet" type="text/css">
    <!-- custom Css-->
    <link href="{{ asset('../assets/css/custom.min.css') }}" rel="stylesheet" type="text/css">

    @vite(['resources/js/app.js'])

</head>

<body>

    <!--start back-to-top-->
    <button onclick="topFunction()" class="btn btn-info btn-icon" style="bottom: 50px;" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>
    <!--end back-to-top-->

    <!-- L'élément HTML d'identifiant "app" -->
    <div id="app"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JAVASCRIPT -->
    {{-- <script src="{{ asset('../assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script> --}}
    <script src="{{ asset('../assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('../assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('../assets/js/plugins.js') }}"></script>

    <!-- Swiper Js-->
    <script src="{{ asset('../assets/libs/swiper/swiper-bundle.min.js') }}"></script>

    <!-- Trend Fashion init js-->
    {{-- <script src="{{ asset('../assets/js/frontend/trend-fashion.init.js') }}"></script> --}}

    <!-- menu init js -->
    <script src="{{ asset('../assets/js/frontend/menu.init.js') }}"></script>
    <!-- form wizard init -->
    <script src="{{ asset('../assets/js/pages/form-wizard.init.js') }}"></script>
    <!-- Dashboard init -->
    <script src="{{ asset('../assets/js/pages/dashboard-ecommerce.init.js') }}"></script>
    <!-- App js -->
    <script src="{{ asset('../assets/js/app.js') }}"></script>
    <!-- ckeditor -->
    {{-- <script src="{{ asset('../assets/libs/%40ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script> --}}
    <!-- dropzone js -->
    {{-- <script src="{{ asset('../assets/libs/dropzone/dropzone-min.js') }}"></script> --}}
    <!-- App js -->
    <script src="{{ asset('../assets/js/app.js') }}"></script>

    <script>
        //  Line chart datalabel
        var linechartDatalabelColors = getChartColorsArray("line_chart_datalabel");
        if (linechartDatalabelColors) {
            var options = {
                chart: {
                    height: 405,
                    zoom: {
                        enabled: true
                    },
                    toolbar: {
                        show: false
                    }
                },
                colors: linechartDatalabelColors,
                markers: {
                    size: 0,
                    colors: "#ffffff",
                    strokeColors: linechartDatalabelColors,
                    strokeWidth: 1,
                    strokeOpacity: 0.9,
                    fillOpacity: 1,
                },
                dataLabels: {
                    enabled: false,
                },
                stroke: {
                    width: [2, 2, 2],
                    curve: 'smooth'
                },
                series: [{
                    name: "Orders",
                    type: 'line',
                    data: [180, 274, 346, 290, 370, 420, 490, 542, 510, 580, 636, 745]
                },
                {
                    name: "Refunds",
                    type: 'area',
                    data: [100, 154, 302, 411, 300, 284, 273, 232, 187, 174, 152, 122]
                },
                {
                    name: "Earnings",
                    type: 'line',
                    data: [260, 360, 320, 345, 436, 527, 641, 715, 832, 794, 865, 933]
                }
                ],
                fill: {
                    type: ['solid', 'gradient', 'solid'],
                    gradient: {
                        shadeIntensity: 1,
                        type: "vertical",
                        inverseColors: false,
                        opacityFrom: 0.3,
                        opacityTo: 0.0,
                        stops: [20, 80, 100, 100]
                    },
                },
                grid: {
                    row: {
                        colors: ['transparent', 'transparent'], // takes an array which will be repeated on columns
                        opacity: 0.2
                    },
                    borderColor: '#f1f1f1'
                },
                xaxis: {
                    categories: [
                        "Jan",
                        "Feb",
                        "Mar",
                        "Apr",
                        "May",
                        "Jun",
                        "Jul",
                        "Aug",
                        "Sep",
                        "Oct",
                        "Nov",
                        "Dec",
                    ],
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'right',
                    floating: true,
                    offsetY: -25,
                    offsetX: -5
                },
                responsive: [{
                    breakpoint: 600,
                    options: {
                        chart: {
                            toolbar: {
                                show: false
                            }
                        },
                        legend: {
                            show: false
                        },
                    }
                }]
            }

            var chart = new ApexCharts(
                document.querySelector("#line_chart_datalabel"),
                options
            );
            chart.render();
        }
        
    </script>

</body>


</html>
