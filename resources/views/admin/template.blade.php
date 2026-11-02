<!DOCTYPE html>

<html lang="id">

<head> <meta charset="UTF-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>@yield('title', 'Admin Dashboard')</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    * {
        box-sizing: border-box;
    }

    html, body {
        margin: 0;
        padding: 0;
        min-height: 100%;
    }

    body {
        background: #f4f6f9;
        font-family: Arial, sans-serif;
        font-size: 13px;
    }

    /* SIDEBAR */
    .admin-sidebar {
        display: block !important;
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 168px !important;
        height: 100vh !important;
        background-color: #212529 !important;
        z-index: 9999 !important;
        overflow-y: auto;
    }

    .admin-sidebar .nav {
        display: flex;
        flex-direction: column;
        padding: 10px 0;
    }

    .admin-sidebar .nav-link {
        display: block;
        color: #d5d5d5;
        padding: 12px 16px;
        text-decoration: none;
        border-radius: 0;
        font-size: 13px;
    }

    .admin-sidebar .nav-link:hover {
        background-color: #343a40;
        color: white;
    }

    .admin-sidebar .nav-link.active {
        background-color: #343a40;
        color: white;
    }

    /* MAIN CONTENT */
    .admin-content {
        display: block;
        margin-left: 168px !important;
        min-height: 100vh;
        padding: 16px 20px;
        background: #f4f6f9;
    }

    /* HEADER */
    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 18px;
    }

    .dashboard-header h2 {
        font-size: 23px;
        font-weight: bold;
        margin-bottom: 4px;
        color: #212529;
    }

    .dashboard-header p {
        color: #6c757d;
        margin: 0;
        font-size: 12px;
    }

    /* CARD */
    .dashboard-card {
        background: white;
        border: 1px solid #e2e5e9;
        border-radius: 6px;
        padding: 16px;
        width: 100%;
    }

    /* TABLE */
    .table {
        width: 100%;
        font-size: 12px;
        margin-bottom: 0;
    }

    .table th {
        background: #f8f9fa;
        font-weight: bold;
        white-space: nowrap;
    }

    .table td {
        vertical-align: middle;
    }

    .table img {
        width: 48px;
        height: 38px;
        object-fit: cover;
        border-radius: 3px;
    }

    /* CATEGORY */
    .badge-hardware {
        display: inline-block;
        background: #e3f0ff;
        color: #2878d0;
        border-radius: 20px;
        padding: 4px 8px;
        font-size: 10px;
    }

    .badge-software {
        display: inline-block;
        background: #d8eee5;
        color: #277653;
        border-radius: 20px;
        padding: 4px 8px;
        font-size: 10px;
    }

    /* BUTTON */
    .btn-sm {
        font-size: 11px;
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {

        .admin-sidebar {
            width: 120px !important;
        }

        .admin-content {
            margin-left: 120px !important;
            padding: 12px;
        }

        .dashboard-header {
            flex-wrap: wrap;
            gap: 10px;
        }
    }
</style>

</head>

<body>

<!-- SIDEBAR -->
<aside class="admin-sidebar">

    <ul class="nav flex-column">

        <li class="nav-item">
            <a href="/" class="nav-link">
                Home
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('admin.home') }}" class="nav-link active">
                Projects
            </a>
        </li>

    </ul>

</aside>

<!-- MAIN CONTENT -->
<main class="admin-content">

    <!-- HEADER -->
    <div class="dashboard-header">

        <div>
            <h2>@yield('heading', 'Admin Dashboard')</h2>

            <p>
                @yield('subtitle', 'Halaman Kelola Projects')
            </p>
        </div>

        @yield('header-action')

    </div>

    <!-- CONTENT -->
    @yield('content')

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')

</body> </html>