<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CASCRM</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="{{asset('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('assets/profile/plugins/fontawesome-free/css/all.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('assets/profile/css/adminlte.min.css')}}">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css" />
    <link rel="stylesheet" href="https://uicdn.toast.com/tui-calendar/latest/tui-calendar.min.css">
    <style>
        #garland {
            position: absolute;
            top: 0;
            left: 0;
            background-image: url('http://imapo.ru/img/christmas.png');
            height: 36px;
            width: 100%;
            overflow: hidden;
            z-index: 99;
        }

        .garland_1 {
            background-position: 0 0
        }

        .garland_2 {
            background-position: 0 -36px
        }

        .garland_3 {
            background-position: 0 -72px
        }

        .garland_4 {
            background-position: 0 -108px
        }
    </style>
</head>
<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="/profile" class="nav-link">Home</a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- Navbar Search -->
            <li class="nav-item">
                    {{--@dump(\Illuminate\Support\Facades\Auth::check())--}}
                <a class="nav-link" {{--data-widget="navbar-search"--}} href="{{route('logout')}}" role="button">
                    {{--<i class="fas fa-search"></i>--}}выход
                </a>
                <div class="navbar-search-block">
                    <form class="form-inline">
                        <div class="input-group input-group-sm">
                            <input class="form-control form-control-navbar" type="search" placeholder="Search"
                                   aria-label="Search">
                            <div class="input-group-append">
                                <button class="btn btn-navbar" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </li>
        </ul>
        <div id="garland" class="garland_4">
            <div id="nums_1">1</div>
        </div>
        <script>
            function garland() {
                nums = document.getElementById('nums_1').innerHTML
                if (nums == 1) {
                    document.getElementById('garland').className = 'garland_1';
                    document.getElementById('nums_1').innerHTML = '2'
                }
                if (nums == 2) {
                    document.getElementById('garland').className = 'garland_2';
                    document.getElementById('nums_1').innerHTML = '3'
                }
                if (nums == 3) {
                    document.getElementById('garland').className = 'garland_3';
                    document.getElementById('nums_1').innerHTML = '4'
                }
                if (nums == 4) {
                    document.getElementById('garland').className = 'garland_4';
                    document.getElementById('nums_1').innerHTML = '1'
                }
            }

            setInterval(function() {
                garland()
            }, 400)
        </script>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <p href="#" class="brand-link">
            <img src="{{asset('assets/profile/img/photo.svg')}}"
                 class=" brand-text font-weight-light">
        </p>
        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="{{asset('assets/profile/img/user1-128x128.jpg')}}" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="{{route('index.profile')}}" class="d-block">{{\Illuminate\Support\Facades\Auth::user()->name . ' ' . \Illuminate\Support\Facades\Auth::user()->surname}}</a>
                </div>
            </div>
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                         with font-awesome or any other icon font library -->
                    @if(\Illuminate\Support\Facades\Auth::user()->role == 'admin')
                    <li class="nav-item">
                        <a href="{{route('users.list')}}" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Пользователи</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-file"></i>
                            <p>
                                Расписание
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{route('rooms.index')}}" class="nav-link">
                                    <i class="fas nav-icon"></i>
                                    <p>Залы</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('training.list')}}" class="nav-link">
                                    <i class="far nav-icon"></i>
                                    <p>Занятия тренеров</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    {{--<li class="nav-header">MISCELLANEOUS</li>--}}
                    @endif
                    @if(\Illuminate\Support\Facades\Auth::user()->role == 'coach')
                        <li class="nav-item">
                            <a href="{{route('training.profile')}}" class="nav-link">
                                <i class="nav-icon fas fa-file"></i>
                                <p>Мои занятия</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('groups.index')}}" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Мои группы</p>
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
    <!-- /.sidebar -->

    </aside>

    @yield('content')

    <footer class="main-footer">
        <div class="float-right d-none d-sm-block">
            <b>Version</b> 1.0.0
        </div>
        <strong>cascrm</strong>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{asset('assets/profile/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('assets/profile/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('assets/profile/js/adminlte.min.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('assets/profile/js/demo.js')}}"></script>
<script src="https://uicdn.toast.com/tui.code-snippet/latest/tui-code-snippet.min.js"></script>
<script src="https://uicdn.toast.com/tui-calendar/latest/tui-calendar.min.js"></script>

<script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>
