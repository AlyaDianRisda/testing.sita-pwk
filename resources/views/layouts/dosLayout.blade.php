<!DOCTYPE html>
<!--
* CoreUI - Free Bootstrap Admin Template
* @version v5.2.0
* @link https://coreui.io/product/free-bootstrap-admin-template/
* Copyright (c) 2025 creativeLabs Łukasz Holeczek
* Licensed under MIT (https://github.com/coreui/coreui-free-bootstrap-admin-template/blob/main/LICENSE)
-->

<!--
    This project was developed as an undergraduate thesis by:
        1. Rafiif Muhammad (IF20)
        2. Alya Dian Risda (IF20)

    Supervised by Ilham Firman Ashari S.Kom., M.Kom.

    Special thanks to all stakeholders from Perencanaan Wilayah dan Kota (PWK) ITERA
-->

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
    <meta name="description" content="Sistem Informasi Tugas Akhir PWK ITERA" />
    <meta name="author" content="Perencanaan Wilayah dan Kota ITERA" />
    <meta name="keyword"
        content="Manajemen TA PWK ITERA, Sistem Informasi TA PWK, Jadwal Sidang TA PWK ITERA, Dosen Pembimbing PWK ITERA, Koordinator TA PWK ITERA" />
    <meta name="theme-color" content="#ffffff" />
    <title>DOS PANEL</title>


    <!-- Vendors styles-->
    <link rel="stylesheet" href="{{ asset('coreui/vendors/simplebar/css/simplebar.css') }}" />
    <link rel="stylesheet" href="{{ asset('coreui/css/vendors/simplebar.css') }}" />

    <!-- Main styles for this application-->
    <link href="{{ asset('coreui/css/style.css') }}" rel="stylesheet" />

    <!-- jQuery -->
    <script src="{{ asset('vendor/jquery/jquery-3.3.1.min.js') }}"></script>

    <!-- Gijgo Datepicker -->
    <link href="{{ asset('vendor/gijgo/gijgo.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('vendor/gijgo/gijgo.min.js') }}"></script>

    <!-- DataTables + Bootstrap 5 -->
    <link rel="stylesheet" href="{{ asset('vendor/datatables/dataTables.bootstrap5.min.css') }}" />
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap5.min.js') }}"></script>

    <!-- DataTables Responsive -->
    <link rel="stylesheet" href="{{ asset('vendor/datatables/responsive.bootstrap5.min.css') }}" />
    <script src="{{ asset('vendor/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/responsive.bootstrap5.min.js') }}"></script>


    <!-- Custom styles for this application-->
    <style>
        body {
            background-color: #f8f9fa !important;
        }

        .card {
            border-radius: 1.2rem !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
        }

        .dataTables_wrapper thead th {
            background: linear-gradient(to right, #dbeafe, #e0f2fe);
            color: #1e3a8a;

        }

        table.dataTable td,
        table.dataTable th {
            padding: 6px 12px;
            /* atas-bawah, kiri-kanan */
            font-size: 1rem;
        }

        .dataTables_wrapper .dataTables_filter {
            float: left !important;
            text-align: left !important;
            margin-bottom: 5px;
        }

        .dataTables_wrapper .dataTables_paginate {
            float: right !important;
            text-align: right !important;
            padding-top: 0.5em;
        }

        #table1 {
            table-layout: fixed;
            word-wrap: break-word;
            width: 100% !important;
        }

        #table1 td,
        #table1 th {
            word-break: break-word;
            white-space: normal;
        }

        #table2 {
            table-layout: fixed;
            word-wrap: break-word;
            width: 100% !important;
        }

        #table2 td,
        #table2 th {
            word-break: break-word;
            white-space: normal;
        }


        .dataTables_wrapper {
            overflow-x: auto;
        }


        .badge-status {
            display: inline-block;
            min-width: 70px;
            text-align: center;
            padding: 0.35em 0.6em;
            font-size: 0.875em;
            font-weight: 600;
            border-radius: 0.25rem;
        }

        .badge-open {
            background-color: #b0f7d7;
            color: #0f5132;
        }

        .badge-closed {
            background-color: #f0a5ab;
            color: #842029;
        }

        .accent-text {
            color: #003f66 !important;
        }
    </style>
    @stack('style')

</head>

<body>
    <div class="sidebar sidebar-light sidebar-fixed border-end" id="sidebar">
        <div class="sidebar-header border-bottom">
            <div class="sidebar-brand">
                <img src="{{ asset('coreui/assets/brand/logo-pwkn.svg') }}" alt="CoreUI Logo"
                    class="sidebar-brand-full" width="100%" height="100%" />
            </div>
            <button class="btn-close d-lg-none" type="button" data-coreui-theme="dark" aria-label="Close"
                onclick='coreui.Sidebar.getInstance(document.querySelector("#sidebar")).toggle()'></button>
        </div>
        <ul class="sidebar-nav" data-coreui="navigation" data-simplebar="">
            <li class="nav-title fw-semibold accent-text my-0">Fitur Umum</li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('x1.Homepage') }}">
                    <svg class="nav-icon text-dark">
                        <use xlink:href="{{ asset('coreui/vendors/@coreui/icons/svg/free.svg#cil-chart') }}">
                        </use>
                    </svg>
                    Homepage</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('x1.PeriodeTA') }}">
                    <svg class="nav-icon text-dark">
                        <use xlink:href="{{ asset('coreui/vendors/@coreui/icons/svg/free.svg#cil-paperclip') }}">
                        </use>
                    </svg>
                    Periode Tugas Akhir</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('x1.TopikTA') }}">
                    <svg class="nav-icon text-dark">
                        <use xlink:href="{{ asset('coreui/vendors/@coreui/icons/svg/free.svg#cil-library-add') }}">
                        </use>
                    </svg>
                    Input Topik Dosen</a>
            </li>

            <li class="nav-title fw-semibold accent-text my-0">Manajemen</li>

            <li class="nav-group">
                <a class="nav-link nav-group-toggle" href="#">
                    <svg class="nav-icon text-dark">
                        <use xlink:href="{{ asset('coreui/vendors/@coreui/icons/svg/free.svg#cil-people') }}"></use>
                    </svg>
                    Data Mahasiswa</a>
                <ul class="nav-group-items compact">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('x1.DataMahasiswa-1') }}"><span class="nav-icon"><span
                                    class=""></span></span>Pengajuan Dosbing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('x1.DataMahasiswa-2') }}"><span class="nav-icon"><span
                                    class=""></span></span>Data Bimbingan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('x1.DataMahasiswa-3') }}"><span class="nav-icon"><span
                                    class=""></span></span>Halaman Penilaian</a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('x1.AgendaSidang') }}">
                    <svg class="nav-icon text-dark">
                        <use xlink:href="{{ asset('coreui/vendors/@coreui/icons/svg/free.svg#cil-spreadsheet') }}">
                        </use>
                    </svg>
                    Agenda Sidang</a>
            </li>
            {{-- <li class="nav-item">
                <a class="nav-link" href="{{ route('x1.DataMahasiswa-3') }}">
                    <svg class="nav-icon text-dark">
                        <use xlink:href="{{ asset('coreui/vendors/@coreui/icons/svg/free.svg#cil-pen-alt') }}">
                        </use>
                    </svg>
                    Kelola Penilaian</a>
            </li> --}}
        </ul>
        <div class="sidebar-footer border-top d-none d-md-flex">
            <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
        </div>
    </div>
    <div class="wrapper d-flex flex-column min-vh-100">
        <header class="header header-sticky p-0 mb-4">
            <div class="container-fluid border-bottom px-4">
                <button class="header-toggler" type="button"
                    onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()"
                    style="margin-inline-start: -14px">
                    <svg class="icon icon-lg">
                        <use xlink:href="{{ asset('coreui/vendors/@coreui/icons/svg/free.svg#cil-menu') }}"></use>
                    </svg>
                </button>
                <ul class="header-nav d-none d-lg-flex">
                </ul>
                <ul class="header-nav ms-auto"></ul>
                <ul class="header-nav">
                    <li class="nav-item">
                        <span class="nav-link">{{ Auth::user()->name }}</span>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link py-0 pe-0" data-coreui-toggle="dropdown" href="#" role="button"
                            aria-haspopup="true" aria-expanded="false">
                            <div class="avatar avatar-md">
                                <img class="avatar-img"
                                    src="{{ asset('coreui/assets/img/avatars/profile1-Flaticon.png') }}"
                                    alt="user@email.com" />
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end pt-0">
                            <a class="dropdown-item" href="#">
                                <svg class="icon me-2">
                                    <use
                                        xlink:href="{{ asset('coreui/vendors/@coreui/icons/svg/free.svg#cil-user') }}">
                                    </use>
                                </svg>
                                Profile</a>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                <svg class="icon me-2">
                                    <use
                                        xlink:href="{{ asset('coreui/vendors/@coreui/icons/svg/free.svg#cil-account-logout') }}">
                                    </use>
                                </svg>
                                Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="container-fluid px-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb my-0">
                        <li class="breadcrumb-item accent-text">Dosen Panel</li>
                    </ol>
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <div class="body flex-grow-1">
            <div class="container-lg px-3">
                <div class="row g-4 mb-4">
                    @yield('content')
                </div>
            </div>
        </div>

        <footer class="footer px-2">
            <div>
                Sistem Informasi Tugas Akhir &copy 2025
                <a href="https://pwk.itera.ac.id/" target="_blank"> Perencanaan Wilayah dan
                    Kota ITERA</a>
            </div>
        </footer>
    </div>
    <!-- CoreUI and necessary plugins-->
    <script src="{{ asset('coreui/vendors/@coreui/coreui/js/coreui.bundle.min.js') }}"></script>
    <script src="{{ asset('coreui/vendors/simplebar/js/simplebar.min.js') }}"></script>
    <script>
        const header = document.querySelector("header.header");

        document.addEventListener("scroll", () => {
            if (header) {
                header.classList.toggle(
                    "shadow-sm",
                    document.documentElement.scrollTop > 0
                );
            }
        });
    </script>

    <!-- Custom Javascript -->
    @stack('script')
</body>

</html>
