<!DOCTYPE html>
<html lang="es">

<head>
    <title>Mi App Laravel</title>
    <meta charset="UTF-8">
    {{--anteriormente Laravel usaba esta sintaxix para cargar assets
        y el css debe estar en la carpeta public/css

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    --}}
    @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- Laravel moderno: usa Vite --}}

    <!-- CSS de DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">

</head>
<body>
    @include('layouts.menu')
