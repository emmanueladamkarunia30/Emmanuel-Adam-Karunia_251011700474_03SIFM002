<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin</title>
    @vite('resources/css/app.css')
    <link rel=  "stylesheet" href="{{ asset('DataTables/datatables.min.css') }}">
    <script src="{{ asset('DataTables/datatables.min.js') }}"></script>
</head>
<style>
    .admin-sidebar {
        width: 250px;
        height: 100vh;
        background-color: #343a40;
        position: fixed;
        top: 0;
        left: 0;
        padding-top: 20px;
    }

    .admin-sidebar .nav-link {
        color: #fff;
        padding: 10px 20px;
    }

    .admin-sidebar .nav-link:hover {
        background-color: #495057;
        color: #fff;
    }

    .admin-content {
        margin-left: 250px;
        padding: 20px;
    }

<body>
    <div class="admin-sidebar shadow">
        <h4 class="text-center fw-bold text-white pb-3 border-bottom">
            Admin Panel
        </h4>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-white" href="{{ route('admin.home') }}">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="#">Projects</a>
            </li>
        </ul>
    </div>

    <div class="admin-content">
        <div class="d-flex justify-content-between align-items-center p-3 mb-4 border-bottom bg-white rounded-2">
            <h3>admin Dasboard</h3>
        </div>
        @yield('content')
    </div>
    @stack('scripts')
</body>