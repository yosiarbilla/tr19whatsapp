<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
	<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
	<link rel="icon" href="{{ asset('images/Logo_Green.png') }}">
	<link rel="stylesheet" href="{{ asset('admin_asset/css/style.css') }}">

	<title>TheRoom19</title>
    <style>
        /* Reduced sidebar width */
        #sidebar {
            max-width: 220px !important;
            background: #595442 !important;
        }
        
        #sidebar.hide {
            max-width: 60px !important;
        }
        
        #content {
            width: calc(100% - 220px) !important;
            left: 220px !important;
        }
        
        #sidebar.hide + #content {
            width: calc(100% - 60px) !important;
            left: 60px !important;
        }
        
        /* Navbar styling */
        #content nav {
            background: #595442 !important;
            height: 64px;
            padding: 0 24px;
            display: flex;
            align-items: center;
            grid-gap: 24px;
            position: sticky;
            top: 0;
            left: 0;
            z-index: 1000;
        }
        
        #content nav .toggle-sidebar {
            font-size: 24px;
            cursor: pointer;
            color: white;
        }
        
        #content nav .divider {
            width: 1px;
            background: rgba(255, 255, 255, 0.2);
            height: 12px;
        }
        
        #content nav .profile {
            position: relative;
        }
        
        #content nav .profile .empty-avatar {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            cursor: pointer;
        }
        
        #content nav .profile .profile-link {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            background: white;
            padding: 10px 0;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            width: 180px;
            display: none;
            z-index: 1001;
        }
        
        #content nav .profile .profile-link.show {
            display: block;
        }
        
        #content nav .profile .profile-link a {
            display: flex;
            align-items: center;
            padding: 10px 16px;
            color: #333;
            transition: all 0.3s ease;
        }
        
        #content nav .profile .profile-link a:hover {
            background: #f5f5f5;
            border-left: 4px solid #595442;
        }
        
        /* Brand/logo styles */
        #sidebar .brand {
            font-size: 20px;
            margin-left: 0;
            display: flex;
            align-items: center;
            color: #fff;
            height: 80px;
            overflow: hidden;
            justify-content: center;
            padding: 10px 0;
        }
        
        #sidebar .brand img {
            height: 60px;
            margin-right: 0;
            transition: all 0.3s ease;
        }
        
        #sidebar.hide .brand img {
            transform: scale(0.7);
            height: 40px;
        }
        
        /* Updated sidebar menu styles */
        #sidebar .side-menu {
            padding: 0 !important;
        }
        
        #sidebar .side-menu a {
            color: #fff !important;
            font-size: 14px;
            padding: 10px 20px !important;
            border-radius: 0 !important;
            margin: 0 !important;
            width: 100%;
        }
        
        #sidebar .side-menu .icon {
            width: 16px !important;
            margin-right: 8px;
        }
        
        #sidebar .side-menu > li > a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff !important;
        }
        
        #sidebar .side-menu > li > a.active,
        #sidebar .side-menu > li > a.active:hover {
            background: #6F674D;
            color: #fff !important;
        }
        
        /* Full width active indicator */
        #sidebar .side-menu > li {
            position: relative;
        }
        
        #sidebar .side-menu > li > a.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background-color: #ffff00;
        }
        
        /* Enhanced logout icon and style */
        .side-menu.bottom {
            position: absolute;
            bottom: 20px;
            width: 100%;
            margin: 0;
            padding: 0 !important;
        }
        
        .side-menu.bottom a {
            color: #fff !important;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            padding: 10px 20px !important;
        }
        
        .side-menu.bottom a:hover {
            background: rgba(255, 100, 100, 0.2);
        }
        
        .side-menu.bottom a:hover .icon {
            transform: translateX(3px);
        }
        
        .side-menu.bottom .icon {
            color: #ff6b6b;
            transition: transform 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .icon-logout {
            position: relative;
            display: inline-flex;
            font-size: 18px !important;
            width: 22px !important;
        }
        
        /* Empty avatar style */
        .empty-avatar {
            width: 36px;
            height: 36px;
            background-color: #ddd;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            font-size: 18px;
        }
        
        /* Hide sidebar scrollbar when sidebar is collapsed */
        #sidebar.hide {
            overflow: hidden;
        }
        
        #sidebar.hide .side-menu {
            padding: 0 !important;
        }
        
        #sidebar.hide .side-menu a {
            padding: 10px 0 10px 20px !important;
        }
        
        #sidebar.hide .side-menu.bottom {
            padding: 0 !important;
        }
        
        #sidebar.hide:hover .side-menu.bottom {
            padding: 0 !important;
        }
        
        /* Media queries for responsive layout */
        @media screen and (max-width: 768px) {
            #content {
                width: 100% !important;
                left: 0 !important;
            }
        }
    </style>
</head>
<body>
	
	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="#" class="brand">
			<img src="{{ asset('images/Logo_Green.png') }}" alt="TheRoom19 Logo" style="height: 60px;">
		</a>
		<ul class="side-menu">
            <li><a href="{{ route('data.peminjam') }}" class="{{ request()->routeIs('welcome') || request()->routeIs('data.peminjam') ? 'active' : '' }}"><i class='bx bxs-calendar icon'></i> Data Peminjam</a></li>
            <li><a href="{{ route('history') }}" class="{{ request()->routeIs('history') ? 'active' : '' }}"><i class='bx bx-history icon'></i> Riwayat Peminjam</a></li>
        </ul>
	
        <!-- Logout menu at bottom of sidebar -->
        <ul class="side-menu bottom">
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                        <i class='bx bx-power-off icon icon-logout'></i> Logout
                    </a>
                </form>
            </li>
        </ul>
	</section>
	<!-- SIDEBAR -->

	<!-- NAVBAR -->
	<section id="content">
		<!-- NAVBAR -->
		<nav>
			<i class='bx bx-menu toggle-sidebar' ></i>
			<form action="#">
				<div class="form-group">
				</div>
			</form>
		
			<span class="divider"></span>
			<div class="profile">
				<div class="empty-avatar">
                    <i class="fas fa-user"></i>
                </div>
				<ul class="profile-link">
					<li><a href="#"><i class='bx bxs-user-circle icon' ></i> Profile</a></li>
					<li><a href="#"><i class='bx bxs-cog' ></i> Settings</a></li>
					<li>
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <a href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                                <i class='bx bx-power-off'></i> Logout
                            </a>
                        </form>
                    </li>
				</ul>
			</div>
		</nav>
		<!-- NAVBAR -->

		<!-- MAIN -->
		<main>

            @yield ('admin_layout')
			
		</main>
		<!-- MAIN -->
	</section>
	<!-- NAVBAR -->

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
	<script src="{{ asset('admin_asset/js/script.js') }}"></script>
</body>
</html>