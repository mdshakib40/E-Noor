
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>NOOR — Super Admin Dashboard</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;0,600;1,500&display=swap" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>

<style>
:root{--bg:#0e0f0d;--surface:#161714;--line:#ffffff13;--line2:#ffffff22;--text:#f6f2e9;--muted:#918d84;--muted2:#6e6b63;--gold:#d7a858;--gold2:#f1cf8b;--mint:#b8d985;--blue:#a7c4e9;--red:#ed8b79;--serif:"Playfair Display",Georgia,serif;--sans:Manrope,Arial,sans-serif;--mono:"DM Mono",monospace}
*{box-sizing:border-box}
body{margin:0;background:var(--bg);color:var(--text);font-family:var(--sans);overflow-x:hidden}
button,select,input{font:inherit}button{cursor:pointer}a{text-decoration:none;color:inherit}
.mono{font-family:var(--mono)}
.app{display:flex;min-height:100vh}
.sidebar{width:252px;flex:none;min-height:100vh;background:#11120f;border-right:1px solid var(--line);padding:25px 17px;display:flex;flex-direction:column}
.brand{padding:0 13px 34px;display:flex;align-items:center;gap:11px;letter-spacing:.23em;font-size:17px;font-weight:800}
.brand-mark{width:28px;height:28px;border:1px solid var(--gold);border-radius:50%;display:grid;place-items:center}
.brand-mark:after{content:"";width:7px;height:7px;background:var(--gold);border-radius:50%;box-shadow:0 0 0 5px #d7a8581d}
.nav-label{color:#6f6b63;font-size:9px;text-transform:uppercase;letter-spacing:.17em;font-weight:800;padding:0 13px;margin:0 0 9px}
.nav{display:grid;gap:4px}
.nav a{display:flex;align-items:center;gap:12px;color:#9c988e;font-size:11px;font-weight:700;padding:12px 13px;border-radius:12px}
.nav a i{width:16px;height:16px}
.nav a:hover{color:#fff;background:#ffffff07}
.nav a.active{color:var(--gold2);background:linear-gradient(100deg,#d7a8581b,#d7a85803);border:1px solid #d7a85836}
.nav-count{margin-left:auto;background:var(--gold);color:#11120f;border-radius:99px;font-size:8px;padding:3px 6px}
.side-spacer{height:30px}
.system{margin:auto 4px 14px;border:1px solid #b8d9852b;background:#b8d9850d;border-radius:15px;padding:14px}
.system-head{display:flex;align-items:center;gap:8px;color:var(--mint);font-size:10px;font-weight:800}
.pulse{width:7px;height:7px;border-radius:50%;background:var(--mint);box-shadow:0 0 0 5px #b8d98518}
.system p{color:#8d9a80;line-height:1.5;font-size:9px;margin:8px 0 0}
.user{border-top:1px solid var(--line);padding:16px 10px 0;display:flex;align-items:center;gap:9px}
.avatar{width:33px;height:33px;border-radius:50%;background:var(--gold);color:#161714;display:grid;place-items:center;font-size:10px;font-weight:900}
.user strong{display:block;font-size:10px}
.user span{display:block;color:var(--muted2);font-size:9px;margin-top:3px}
.main{flex:1;min-width:0;padding:0 39px 48px}
.topbar{height:79px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid var(--line)}
.breadcrumb{display:flex;align-items:center;gap:8px;color:var(--muted2);font-size:10px}
.breadcrumb b{color:var(--gold2)}
.top-right{display:flex;align-items:center;gap:9px}
.date-wrap,.profile-wrap{position:relative}
.period{display:flex;gap:8px;align-items:center;border:1px solid var(--line);background:transparent;color:#b8b0a6;padding:9px 11px;border-radius:9px;font-size:9px}
.period i{width:13px;color:var(--gold)}
.date-menu{position:absolute;right:0;top:44px;background:#1b1d18;border:1px solid var(--line2);border-radius:10px;box-shadow:0 14px 40px #0009;display:none;z-index:180;overflow:hidden;min-width:140px}
.date-menu.open{display:block}
.date-option{display:block;width:100%;text-align:left;background:none;border:0;color:#b9b1a7;padding:10px 12px;font-size:9px}
.date-option:hover{background:#ffffff08;color:var(--gold2)}
.top-button{width:36px;height:36px;border:1px solid var(--line);border-radius:50%;background:transparent;color:#c5beb3;display:grid;place-items:center}
.top-button i{width:16px}
.hamburger{display:none}
.profile-trigger{display:flex;align-items:center;gap:10px;padding:5px 11px 5px 5px;border:1px solid #d7a85838;border-radius:99px;background:linear-gradient(145deg,#d7a85814,#ffffff04);color:var(--text)}
.profile-trigger:hover,.profile-trigger.open{border-color:var(--gold);background:#d7a85822}
.p-avatar{width:30px;height:30px;border-radius:50%;flex:none;background:linear-gradient(135deg,var(--gold2),var(--gold));color:#161714;display:grid;place-items:center;font-size:10px;font-weight:900}
.p-avatar.lg{width:46px;height:46px;font-size:14px}
.profile-trigger-text{text-align:left;line-height:1.2}
.profile-trigger-text strong{display:block;font-size:10px}
.profile-trigger-text small{display:block;font-size:8px;color:var(--muted);margin-top:2px}
.profile-trigger .chev{width:13px;height:13px;color:var(--gold)}
.profile-dropdown{position:absolute;top:52px;right:0;width:290px;background:#1a1c17;border:1px solid var(--line2);border-radius:18px;box-shadow:0 25px 70px #000c;overflow:hidden;display:none;z-index:200}
.profile-dropdown.open{display:block}
.profile-head{display:flex;align-items:center;gap:13px;padding:18px;background:linear-gradient(120deg,#d7a85818,transparent);border-bottom:1px solid var(--line)}
.profile-who{min-width:0}
.profile-who strong{display:block;font-size:13px}
.profile-who>span{display:block;font:10px var(--mono);color:var(--muted);margin:4px 0 8px;overflow:hidden;text-overflow:ellipsis}
.profile-section{padding:8px}
.profile-caption{padding:8px 10px 6px;font-size:8px;font-weight:800;letter-spacing:.18em;color:var(--muted2)}
.profile-item{width:100%;display:flex;align-items:center;gap:12px;padding:11px 10px;border:0;border-radius:10px;background:transparent;color:#d8d0c5;font-size:11px;font-weight:600;text-align:left}
.profile-item i{width:16px;height:16px;color:var(--gold)}
.profile-item:hover{background:#ffffff0a;color:var(--gold2)}
.profile-visit{display:flex;align-items:center;gap:12px;margin:4px 8px 8px;padding:12px;border:1px solid #d7a85840;border-radius:13px;background:linear-gradient(100deg,#d7a85822,#d7a85808)}
.visit-icon{width:34px;height:34px;border-radius:10px;background:var(--gold);color:#161714;display:grid;place-items:center}
.visit-text{flex:1;min-width:0}
.visit-text strong{display:block;font-size:11px;color:var(--gold2)}
.visit-text small{display:block;font-size:9px;color:var(--muted);margin-top:3px;line-height:1.4}
.welcome{padding:32px 0 25px;display:flex;align-items:end;justify-content:space-between}
.eyebrow{color:var(--gold2);font-size:10px;text-transform:uppercase;letter-spacing:.18em;font-weight:800}
.welcome h1{font:500 clamp(35px,4vw,52px)/.98 var(--serif);letter-spacing:-.065em;margin:12px 0 0}
.welcome h1 i{font-style:italic;color:var(--gold2)}
.welcome-copy{color:var(--muted);font-size:11px;margin-top:10px}
.welcome-date{text-align:right;color:var(--muted);font-size:10px;line-height:1.6}
.welcome-date strong{display:block;color:#e8e0d4;font-size:11px}
.stats{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:13px}
.stat{position:relative;overflow:hidden;background:linear-gradient(145deg,#1b1d18,#151613);border:1px solid var(--line);border-radius:17px;padding:17px}
.stat:before{content:"";position:absolute;width:120px;height:120px;border:1px solid #d7a85812;border-radius:50%;right:-61px;bottom:-63px}
.stat-header{display:flex;align-items:center;justify-content:space-between;color:var(--muted);font-size:9px;text-transform:uppercase;letter-spacing:.1em}
.stat-header i{color:var(--gold);width:15px;height:15px}
.stat-value{font-size:clamp(19px,2vw,26px);font-weight:700;letter-spacing:-.06em;margin:13px 0 5px;overflow-wrap:anywhere}
.stat-meta{color:var(--mint);font-size:9px;font-weight:800}
.stat-meta span{color:var(--muted2);font-weight:500;margin-left:4px}
.grid-main{display:grid;grid-template-columns:1.45fr .75fr;gap:15px;margin-top:15px}
.card{border:1px solid var(--line);background:var(--surface);border-radius:18px;padding:19px;overflow:hidden}
.card-head{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:18px;gap:12px}
.card-title{font-size:12px;font-weight:800}
.card-sub{font-size:9px;color:var(--muted);margin-top:5px}
.card-select{background:transparent;border:1px solid var(--line);border-radius:8px;color:#aaa39a;padding:7px 9px;font-size:9px;outline:0}
.chart{height:230px;position:relative}
.chart-grid{position:absolute;inset:5px 0 25px;display:flex;flex-direction:column;justify-content:space-between}
.chart-grid span{border-top:1px dashed #ffffff12;position:relative}
.chart-grid small{position:absolute;right:0;top:-16px;color:#8b867c;font:9px var(--mono)}
.chart svg{position:absolute;left:0;top:5px;width:calc(100% - 35px);height:200px;overflow:visible}
.chart-days{position:absolute;left:0;right:35px;bottom:0;display:flex;justify-content:space-between;gap:4px;color:#8b867c;font:9px var(--mono)}
.chart-legend{display:flex;gap:13px;font-size:9px;color:var(--muted)}
.chart-legend span{display:flex;align-items:center;gap:5px}
.legend-dot{width:7px;height:7px;border-radius:50%;background:var(--gold)}
.legend-dot.mint{background:var(--mint)}
.donut-row{display:flex;align-items:center;justify-content:center;gap:22px;margin:13px 0 20px}
.donut{width:142px;height:142px;flex:none;border-radius:50%;display:grid;place-items:center}
.donut-inner{width:108px;height:108px;border-radius:50%;background:var(--surface);display:grid;place-items:center;text-align:center}
.donut-inner strong{font-size:22px;display:block}
.donut-inner span{display:block;color:var(--muted);font-size:9px}
.mix-items{display:grid;gap:11px;flex:1}
.mix-item{display:flex;align-items:center;gap:7px;font-size:10px;color:#aaa39a}
.mix-item b{margin-left:auto;color:#f0e9de}
.mix-item i{width:8px;height:8px;border-radius:50%;flex:none}
.mix-insight{border-top:1px solid var(--line);padding-top:13px;margin-top:16px;font-size:9px;color:#9a948b;line-height:1.6}
.mix-insight b{color:var(--gold2)}
.orders{margin-top:15px;padding:0}
.orders-head{padding:18px 19px;display:flex;align-items:center;justify-content:space-between}
.table-scroll{overflow-x:auto}
table{width:100%;border-collapse:collapse}
th{text-align:left;padding:11px 18px;color:#8b867c;font-size:8px;letter-spacing:.13em;text-transform:uppercase;border-top:1px solid var(--line);border-bottom:1px solid var(--line)}
td{padding:13px 18px;border-bottom:1px solid #ffffff09;color:#d0c8bd;font-size:10px;white-space:nowrap}
tbody tr:hover td{background:#ffffff05}
.order-id{display:block;color:#8b867c;font:8px var(--mono);margin-top:4px}
.customer{display:flex;align-items:center;gap:8px}
.mini-avatar{width:24px;height:24px;border-radius:50%;background:#564a3a;display:grid;place-items:center;color:var(--gold2);font-size:8px;font-weight:800;flex:none}
.status{display:inline-flex;align-items:center;gap:6px;padding:5px 8px;border-radius:99px;font-size:8px;font-weight:800}
.status i{width:5px;height:5px;border-radius:50%;background:currentColor}
.status.blue{background:#a7c4e916;color:var(--blue)}
.status.gold{background:#d7a85818;color:var(--gold2)}
.status.green{background:#b8d98516;color:var(--mint)}
.amount{font-weight:800;color:#f2eadf!important;text-align:right}
.users-panel,.settlements-panel{margin-top:15px}
.users-toolbar{display:flex;gap:10px;align-items:center}
.users-search{background:#ffffff08;border:1px solid var(--line);border-radius:8px;color:var(--text);padding:8px 10px;font-size:9px;outline:0;width:200px}
.users-search:focus{border-color:#d7a85866}
.role-select{background:#25251f;border:1px solid var(--line2);color:#eee6da;border-radius:7px;padding:6px 8px;font-size:9px;outline:0}
.role-select:disabled{opacity:.45;cursor:not-allowed}
.role-note{font-size:9px;color:var(--muted);line-height:1.6}
.role-pill{display:inline-flex;padding:5px 8px;border-radius:99px;background:#d7a85814;color:var(--gold2);font-size:8px;font-weight:800;font-style:normal}
.role-pill.customer{background:#a7c4e916;color:var(--blue)}
.role-pill.rider{background:#b8d98516;color:var(--mint)}
.role-pill.staff{background:#ed8b7916;color:var(--red)}
.website-banner{display:flex;align-items:center;justify-content:space-between;gap:16px;margin-top:15px;padding:15px 18px;border:1px solid #d7a8582d;border-radius:15px;background:linear-gradient(100deg,#d7a85810,#ffffff03)}
.website-banner p{margin:4px 0 0;color:var(--muted);font-size:9px}
.website-link{border:1px solid #d7a85865;background:#d7a85815;color:var(--gold2);border-radius:8px;padding:9px 12px;font-size:9px;font-weight:800;white-space:nowrap}
.bottom-grid{display:grid;grid-template-columns:1fr 1fr 1fr;gap:15px;margin-top:15px}
.restaurant-list,.rider-list{display:grid;gap:16px;margin-top:20px}
.rank-row,.rider-row{display:flex;align-items:center;gap:10px}
.rank-num{font:10px var(--mono);color:var(--muted2);width:14px}
.rank-info{flex:1;min-width:0}
.rank-info-top{display:flex;justify-content:space-between;gap:8px;font-size:10px}
.rank-info-top strong{font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.rank-info-top span{color:var(--muted);font:9px var(--mono)}
.progress{height:4px;background:#302e27;border-radius:99px;overflow:hidden;margin-top:7px}
.progress i{display:block;height:100%;border-radius:99px;background:linear-gradient(90deg,var(--gold),var(--gold2))}
.panel-icon{width:16px;color:var(--gold)}
.rider-avatar{width:30px;height:30px;border-radius:50%;background:#3e4c43;display:grid;place-items:center;color:var(--mint);font-size:9px;font-weight:800;flex:none}
.rider-info{flex:1;min-width:0}
.rider-info strong{font-size:10px;display:block}
.rider-info span{font-size:9px;color:var(--muted);display:block;margin-top:3px}
.rider-state{font-size:8px;font-weight:800;color:var(--mint)}
.rider-state.busy{color:var(--gold2)}
.quick-note{background:linear-gradient(145deg,#24251e,#181a16);display:flex;flex-direction:column;justify-content:space-between}
.quote{font:500 22px/1.16 var(--serif);letter-spacing:-.03em;margin:19px 0 25px;max-width:260px}
.quote em{font-style:italic;color:var(--gold2)}
.note-foot{font-size:9px;color:var(--muted);line-height:1.6;border-top:1px solid var(--line);padding-top:14px}
.note-foot b{color:var(--mint)}
.toast-ui{position:fixed;bottom:22px;left:50%;transform:translate(-50%,20px);opacity:0;pointer-events:none;background:var(--gold);color:#151612;border-radius:99px;padding:11px 16px;font-size:10px;font-weight:800;z-index:250;transition:.22s}
.toast-ui.show{opacity:1;transform:translate(-50%,0)}
.reveal{animation:reveal .55s ease both}
@keyframes reveal{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:none}}
@media(prefers-reduced-motion:reduce){*{animation:none!important;transition:none!important}}
@media(max-width:1050px){.sidebar{width:215px}.main{padding:0 24px 35px}.grid-main{grid-template-columns:1fr}.bottom-grid{grid-template-columns:1fr 1fr}.quick-note{min-height:230px}.stats{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media(max-width:700px){.sidebar{display:none;position:fixed;z-index:60;width:245px;height:100vh;box-shadow:25px 0 65px #000}.sidebar.open{display:flex}.main{padding:0 14px 30px}.topbar{height:69px}.period{display:none}.hamburger{display:grid}.profile-trigger-text,.profile-trigger .chev{display:none}.profile-trigger{padding:4px}.profile-dropdown{width:min(290px,calc(100vw - 28px))}.welcome{padding:26px 0 21px;align-items:start}.welcome h1{font-size:34px}.welcome-date{font-size:9px}.stats{gap:8px}.stat{padding:13px}.stat-value{font-size:20px}.stat-header,.stat-meta{font-size:8px}.card{padding:14px;border-radius:15px}.chart{height:195px}.chart svg{height:165px}.donut-row{gap:14px}.donut{width:112px;height:112px}.donut-inner{width:84px;height:84px}.donut-inner strong{font-size:18px}.bottom-grid{grid-template-columns:1fr}.users-toolbar{align-items:stretch;flex-direction:column}.users-search{width:100%}.website-banner{align-items:flex-start;flex-direction:column}.website-link{width:100%;text-align:center}td,th{padding-left:12px;padding-right:12px}}
</style>
</head>

<body>
@php
    use App\Enums\UserRole;

    $currentUser = auth()->user();

    $roleLabels = [
        UserRole::SUPER_ADMIN->value => 'Super Admin',
        UserRole::CUSTOMER->value => 'Customer',
        UserRole::RESTAURANT_OWNER->value => 'Restaurant Owner',
        UserRole::RESTAURANT_STAFF->value => 'Restaurant Staff',
        UserRole::RIDER->value => 'Rider',
    ];

    $totalRestaurantCommission = $totalRestaurantCommission ?? 0;
    $totalRestaurantPayable = $totalRestaurantPayable ?? 0;
    $pendingSettlementAmount = $pendingSettlementAmount ?? 0;
    $paidSettlementAmount = $paidSettlementAmount ?? 0;
    $recentSettlements = $recentSettlements ?? collect();
@endphp

<div class="app">

<aside class="sidebar" id="sidebar">
    <div class="brand"><span class="brand-mark"></span>NOOR</div>

    <div class="nav-label">Workspace</div>
    <nav class="nav">
        <a class="active" href="#overview"><i data-lucide="layout-dashboard"></i>Overview</a>
        <a href="#orders"><i data-lucide="shopping-bag"></i>Orders<span class="nav-count">{{ $ordersCount }}</span></a>
        <a href="#restaurants"><i data-lucide="store"></i>Restaurants</a>
        <a href="#customers"><i data-lucide="users"></i>Customers</a>
        <a href="#user-management"><i data-lucide="user-cog"></i>Users<span class="nav-count">{{ $users->count() }}</span></a>
        <a href="#analytics"><i data-lucide="chart-no-axes-combined"></i>Analytics</a>
        <a href="#settlements"><i data-lucide="wallet"></i>Settlements</a>
    </nav>

    <div class="side-spacer"></div>
    <div class="nav-label">Manage</div>
    <nav class="nav">
        <a href="#website"><i data-lucide="external-link"></i>Storefront</a>
    </nav>

    <div class="system">
        <div class="system-head"><span class="pulse"></span>Live database overview</div>
        <p>Dashboard figures are loaded from your database.</p>
    </div>

    <div class="user">
        <div class="avatar">{{ strtoupper(substr($currentUser->name, 0, 2)) }}</div>
        <div>
            <strong>{{ $currentUser->name }}</strong>
            <span>{{ $roleLabels[$currentUser->role->value] ?? 'User' }}</span>
        </div>
    </div>
</aside>

<main class="main">

<header class="topbar">
    <div class="breadcrumb">
        <span>NOOR</span>
        <i data-lucide="chevron-right"></i>
        <b>Storefront admin</b>
        <i data-lucide="chevron-right"></i>
        <span>Overview</span>
    </div>

    <div class="top-right">
        <div class="date-wrap">
            <button class="period" id="dateRange" type="button">
                <i data-lucide="calendar-days"></i>
                <span id="dateLabel">Last {{ $rangeDays }} days</span>
                <i data-lucide="chevron-down"></i>
            </button>
            <div class="date-menu" id="dateMenu">
                <a class="date-option" href="{{ route('admin.dashboard', ['range' => 7]) }}">Last 7 days</a>
                <a class="date-option" href="{{ route('admin.dashboard', ['range' => 30]) }}">Last 30 days</a>
                <a class="date-option" href="{{ route('admin.dashboard', ['range' => 90]) }}">Last 90 days</a>
            </div>
        </div>

        <div class="profile-wrap">
            <button class="profile-trigger" id="actionTrigger" aria-label="Open account menu" aria-expanded="false" type="button">
                <span class="p-avatar">{{ strtoupper(substr($currentUser->name, 0, 2)) }}</span>
                <span class="profile-trigger-text">
                    <strong>{{ $currentUser->name }}</strong>
                    <small>{{ $roleLabels[$currentUser->role->value] ?? 'User' }}</small>
                </span>
                <i data-lucide="chevron-down" class="chev"></i>
            </button>

            <div class="profile-dropdown" id="actionDropdown">
                <div class="profile-head">
                    <span class="p-avatar lg">{{ strtoupper(substr($currentUser->name, 0, 2)) }}</span>
                    <div class="profile-who">
                        <strong>{{ $currentUser->name }}</strong>
                        <span>{{ $currentUser->email }}</span>
                        <em class="role-pill">{{ $roleLabels[$currentUser->role->value] ?? $currentUser->role->value }}</em>
                    </div>
                </div>
                <div class="profile-section">
                    <div class="profile-caption">NOOR</div>
                    <a class="profile-item" href="#overview"><i data-lucide="layout-dashboard"></i>Dashboard</a>
                    <a class="profile-item" href="#user-management"><i data-lucide="user-cog"></i>User Management</a>
                    <a class="profile-item" href="#settlements"><i data-lucide="wallet"></i>Settlements</a>
                </div>
                <a class="profile-visit" href="{{ route('home') }}">
                    <span class="visit-icon"><i data-lucide="external-link"></i></span>
                    <span class="visit-text">
                        <strong>Visit Website</strong>
                        <small>Open the NOOR storefront</small>
                    </span>
                    <i data-lucide="arrow-right"></i>
                </a>
            </div>
        </div>

        <button class="top-button hamburger" id="toggle" aria-label="Toggle menu" type="button"><i data-lucide="menu"></i></button>
    </div>
</header>

@if(session('success'))
    <div id="serverToast" data-message="{{ session('success') }}"></div>
@endif
@if(session('error'))
    <div id="serverToast" data-message="{{ session('error') }}"></div>
@endif

<section class="welcome reveal" id="overview">
    <div>
        <div class="eyebrow">Platform overview</div>
        <h1>Welcome back,<br><i>{{ $currentUser->name }}.</i></h1>
        <p class="welcome-copy">Here’s what is happening across your table today.</p>
    </div>
    <div class="welcome-date">
        <span id="todayLabel">Today</span>
        <strong>Updated {{ now()->format('M d, Y · h:i A') }}</strong>
    </div>
</section>

{{-- MAIN PLATFORM STATS --}}
<section class="stats reveal">
    <div class="stat">
        <div class="stat-header">Order value <i data-lucide="wallet-cards"></i></div>
        <div class="stat-value">৳{{ number_format((float) $platformRevenue, 2) }}</div>
        <div class="stat-meta">Database total <span>non-cancelled orders</span></div>
    </div>
    <div class="stat">
        <div class="stat-header">Total orders <i data-lucide="shopping-bag"></i></div>
        <div class="stat-value">{{ number_format($ordersCount) }}</div>
        <div class="stat-meta">Live <span>recorded orders</span></div>
    </div>
    <div class="stat">
        <div class="stat-header">Restaurants <i data-lucide="store"></i></div>
        <div class="stat-value">{{ number_format($restaurantsCount) }}</div>
        <div class="stat-meta">Live <span>registered restaurants</span></div>
    </div>
    <div class="stat">
        <div class="stat-header">Customers <i data-lucide="users"></i></div>
        <div class="stat-value">{{ number_format($customersCount) }}</div>
        <div class="stat-meta">Live <span>registered customers</span></div>
    </div>
</section>

{{-- SETTLEMENT STATS --}}
<section class="stats reveal" style="margin-top:15px">
    <div class="stat">
        <div class="stat-header">Total commission <i data-lucide="coins"></i></div>
        <div class="stat-value">৳{{ number_format((float) $totalRestaurantCommission, 2) }}</div>
        <div class="stat-meta">Recorded commission</div>
    </div>
    <div class="stat">
        <div class="stat-header">Restaurant payable <i data-lucide="wallet"></i></div>
        <div class="stat-value">৳{{ number_format((float) $totalRestaurantPayable, 2) }}</div>
        <div class="stat-meta">Restaurant earnings</div>
    </div>
    <div class="stat">
        <div class="stat-header">Pending payout <i data-lucide="clock"></i></div>
        <div class="stat-value">৳{{ number_format((float) $pendingSettlementAmount, 2) }}</div>
        <div class="stat-meta">Awaiting payment</div>
    </div>
    <div class="stat">
        <div class="stat-header">Paid out <i data-lucide="check-circle"></i></div>
        <div class="stat-value">৳{{ number_format((float) $paidSettlementAmount, 2) }}</div>
        <div class="stat-meta">Settlements marked paid</div>
    </div>
</section>

{{-- ANALYTICS --}}
<div class="grid-main" id="analytics">
    <section class="card reveal">
        <div class="card-head">
            <div>
                <div class="card-title">Order value overview</div>
                <div class="card-sub">Order activity · last {{ $rangeDays }} days</div>
            </div>
            <div class="chart-legend">
                <span><i class="legend-dot"></i>Revenue</span>
                <span><i class="legend-dot mint"></i>Orders</span>
            </div>
        </div>

        @php
            $chartMaxRevenue = max(1, (float) $dailyStats->max('revenue'));
            $chartMaxOrders = max(1, (float) $dailyStats->max('orders'));
            $chartCount = max(1, $dailyStats->count() - 1);

            $revenuePoints = $dailyStats->values()->map(
                fn ($day, $i) => ($i * (800 / $chartCount)) . ',' .
                    (190 - ((float) $day->revenue / $chartMaxRevenue * 165))
            )->implode(' ');

            $orderPoints = $dailyStats->values()->map(
                fn ($day, $i) => ($i * (800 / $chartCount)) . ',' .
                    (190 - ((float) $day->orders / $chartMaxOrders * 165))
            )->implode(' ');

            $revenueArea = '0,210 ' . $revenuePoints . ' 800,210';
        @endphp

        <div class="chart">
            <div class="chart-grid">
                @foreach([1, .75, .5, .25, 0] as $scale)
                    <span><small>৳{{ number_format($chartMaxRevenue * $scale, 0) }}</small></span>
                @endforeach
            </div>

            <svg viewBox="0 0 800 210" preserveAspectRatio="none">
                <defs>
                    <linearGradient id="goldFill" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0" stop-color="#d7a858" stop-opacity=".3"/>
                        <stop offset="1" stop-color="#d7a858" stop-opacity="0"/>
                    </linearGradient>
                </defs>
                <polygon points="{{ $revenueArea }}" fill="url(#goldFill)"/>
                <polyline points="{{ $revenuePoints }}" fill="none" stroke="#d7a858" stroke-width="3" stroke-linejoin="round" stroke-linecap="round"/>
                <polyline points="{{ $orderPoints }}" fill="none" stroke="#b8d985" stroke-width="2" stroke-dasharray="4 4" stroke-linejoin="round" stroke-linecap="round"/>
            </svg>

            <div class="chart-days">
                @foreach($dailyStats as $day)
                    <span>{{ $day->label }}</span>
                @endforeach
            </div>
        </div>
    </section>

    <section class="card reveal">
        <div class="card-head">
            <div>
                <div class="card-title">Order mix</div>
                <div class="card-sub">Current platform orders</div>
            </div>
            <i data-lucide="pie-chart" class="panel-icon"></i>
        </div>

        <div class="donut-row">
            <div class="donut" style="background:{{ $orderMixStyle }}">
                <div class="donut-inner">
                    <strong>{{ number_format($ordersCount) }}</strong>
                    <span>orders</span>
                </div>
            </div>

            <div class="mix-items">
                @forelse($orderStatusStats as $statusStat)
                    <div class="mix-item">
                        <i style="background:{{ $statusStat->color }}"></i>
                        {{ $statusStat->label }}
                        <b>{{ $statusStat->percent }}%</b>
                    </div>
                @empty
                    <div class="mix-item">No order status data</div>
                @endforelse
            </div>
        </div>

        <div class="mix-insight">
            <b>Live database summary.</b>
            Status percentages are calculated from recorded orders.
        </div>
    </section>
</div>

{{-- RECENT ORDERS --}}
<section class="card orders reveal" id="orders">
    <div class="orders-head">
        <div>
            <div class="card-title">Live orders</div>
            <div class="card-sub">Latest activity across all restaurants</div>
        </div>
        <span class="card-sub">{{ $ordersCount }} total orders</span>
    </div>

    <div class="table-scroll">
        <table>
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Restaurant</th>
                    <th>Status</th>
                    <th style="text-align:right">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                    <tr>
                        <td>
                            <b>#{{ $order->display_id }}</b>
                            <span class="order-id">{{ $order->created_at ? $order->created_at->diffForHumans() : '—' }}</span>
                        </td>
                        <td>
                            <div class="customer">
                                <span class="mini-avatar">{{ strtoupper(substr($order->customer_name ?: 'GU', 0, 2)) }}</span>
                                {{ $order->customer_name ?: 'Guest customer' }}
                            </div>
                        </td>
                        <td>{{ $order->restaurant_name ?: '—' }}</td>
                        <td><span class="status {{ $order->status_class }}"><i></i>{{ $order->status_label }}</span></td>
                        <td class="amount">৳{{ number_format((float) $order->amount, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align:center;padding:30px;color:#8b867c">No orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

{{-- RESTAURANT SETTLEMENTS --}}
<section class="card settlements-panel reveal" id="settlements">
    <div class="card-head">
        <div>
            <div class="card-title">Restaurant settlements</div>
            <div class="card-sub">Review restaurant earnings and payout status</div>
        </div>
        <span class="card-sub">{{ $recentSettlements->count() }} recent records</span>
    </div>

    <div class="table-scroll">
        <table>
            <thead>
                <tr>
                    <th>Restaurant</th>
                    <th>Order</th>
                    <th>Order amount</th>
                    <th>Commission</th>
                    <th>Restaurant payable</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentSettlements as $settlement)
                    <tr>
                        <td>{{ $settlement->restaurant->name ?? 'Restaurant' }}</td>
                        <td>#{{ $settlement->order_id }}</td>
                        <td>৳{{ number_format((float) $settlement->order_amount, 2) }}</td>
                        <td>৳{{ number_format((float) $settlement->commission_amount, 2) }}</td>
                        <td class="amount">৳{{ number_format((float) $settlement->restaurant_amount, 2) }}</td>
                        <td>
                            <span class="status {{ $settlement->status === 'paid' ? 'green' : 'gold' }}">
                                <i></i>{{ ucfirst($settlement->status) }}
                            </span>
                        </td>
                        <td>
                            @if($settlement->status === 'pending')
                                <form method="POST" action="{{ route('admin.settlements.pay', $settlement) }}" onsubmit="return confirm('Mark this settlement as paid?')">
                                    @csrf
                                    <button type="submit" class="website-link">Mark paid</button>
                                </form>
                            @else
                                <span class="card-sub">
                                    {{ $settlement->settled_at?->format('d M Y') ?? 'Paid' }}
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:30px;color:#8b867c">
                            No restaurant settlements found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

{{-- USER ROLE MANAGEMENT --}}
<section class="card users-panel reveal" id="user-management">
    <div class="card-head">
        <div>
            <div class="card-title">User role management</div>
            <div class="card-sub">Manage access levels across the NOOR platform</div>
        </div>
        <div class="users-toolbar">
            <input class="users-search" id="userSearch" placeholder="Search name or email…">
        </div>
    </div>

    <div class="table-scroll">
        <table id="usersTable">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Current role</th>
                    <th>Change role</th>
                    <th style="text-align:right">Access</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    @php
                        $role = $user->role->value;
                        $roleClass = match ($user->role) {
                            UserRole::CUSTOMER => 'customer',
                            UserRole::RIDER => 'rider',
                            UserRole::RESTAURANT_STAFF => 'staff',
                            default => '',
                        };
                    @endphp

                    <tr data-user="{{ strtolower($user->name . ' ' . $user->email) }}">
                        <td>
                            <div class="customer">
                                <span class="mini-avatar">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                <b>{{ $user->name }}</b>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td><span class="role-pill {{ $roleClass }}">{{ $roleLabels[$role] ?? $role }}</span></td>
                        <td>
                            @if($user->id === auth()->id())
                                <select class="role-select" disabled>
                                    <option selected>{{ $role }}</option>
                                </select>
                            @else
                                <form action="{{ route('admin.users.role.update', $user) }}" method="POST" style="display:flex;align-items:center;gap:7px">
                                    @csrf
                                    @method('PATCH')
                                    <select name="role" class="role-select" onchange="this.form.submit()">
                                        @foreach(UserRole::cases() as $roleOption)
                                            <option value="{{ $roleOption->value }}" @selected($user->role === $roleOption)>
                                                {{ $roleLabels[$roleOption->value] ?? $roleOption->value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            @endif
                        </td>
                        <td class="amount">{{ $user->id === auth()->id() ? 'Protected' : 'Editable' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align:center;padding:30px;color:#8b867c">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="role-note" style="margin-top:13px">
        <i data-lucide="shield-check" style="width:13px;vertical-align:middle;color:var(--mint)"></i>
        Your current Super Admin account is protected from role changes.
    </div>
</section>

{{-- STOREFRONT LINK --}}
<div class="website-banner" id="website">
    <div>
        <div class="card-title">Storefront preview</div>
        <p>Open the customer-facing NOOR website.</p>
    </div>
    <a href="{{ route('home') }}" class="website-link">Visit website <i data-lucide="arrow-right" style="width:12px;vertical-align:middle"></i></a>
</div>

{{-- RESTAURANTS / RIDERS / INSIGHT --}}
<div class="bottom-grid">
    <section class="card reveal" id="restaurants">
        <div class="card-head">
            <div>
                <div class="card-title">Top restaurants</div>
                <div class="card-sub">Ranked by recorded order value</div>
            </div>
            <i data-lucide="store" class="panel-icon"></i>
        </div>

        <div class="restaurant-list">
            @forelse($topRestaurants as $index => $restaurant)
                <div class="rank-row">
                    <span class="rank-num">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <div class="rank-info">
                        <div class="rank-info-top">
                            <strong>{{ $restaurant->name }}</strong>
                            <span>৳{{ number_format((float) $restaurant->revenue, 2) }}</span>
                        </div>
                        <div class="progress"><i style="width:{{ $restaurant->bar_percent }}%"></i></div>
                    </div>
                </div>
            @empty
                <p class="card-sub">No restaurant order data available yet.</p>
            @endforelse
        </div>
    </section>

    <section class="card reveal" id="customers">
        <div class="card-head">
            <div>
                <div class="card-title">Delivery network</div>
                <div class="card-sub">Rider availability summary</div>
            </div>
            <i data-lucide="bike" class="panel-icon"></i>
        </div>

        <div class="rider-list">
            @forelse($riders as $rider)
                <div class="rider-row">
                    <span class="rider-avatar">{{ strtoupper(substr($rider->name, 0, 2)) }}</span>
                    <div class="rider-info">
                        <strong>{{ $rider->name }}</strong>
                        <span>{{ $rider->active_order_count }} active {{ $rider->active_order_count === 1 ? 'order' : 'orders' }}</span>
                    </div>
                    <span class="rider-state {{ $rider->active_order_count > 0 ? 'busy' : '' }}">
                        {{ $rider->active_order_count > 0 ? 'Delivering' : 'Available' }}
                    </span>
                </div>
            @empty
                <p class="card-sub">No rider accounts found.</p>
            @endforelse
        </div>
    </section>

    <section class="card quick-note reveal">
        <div>
            <div class="card-head">
                <div>
                    <div class="card-title">Quick insight</div>
                    <div class="card-sub">A note from your data</div>
                </div>
                <i data-lucide="lightbulb" class="panel-icon"></i>
            </div>
            <div class="quote">“NOOR currently has <em>{{ number_format($customersCount) }} customers</em> registered.”</div>
        </div>
        <div class="note-foot"><b>{{ number_format($ordersCount) }}</b> orders recorded in the database.</div>
    </section>
</div>

</main>
</div>

<div class="toast-ui" id="toastUi" role="status"></div>

<script>
if (window.lucide) {
    lucide.createIcons();
}

const $ = (selector) => document.querySelector(selector);
const $$ = (selector) => [...document.querySelectorAll(selector)];

function showToast(message) {
    const toast = $("#toastUi");
    if (!toast) return;
    toast.textContent = message;
    toast.classList.add("show");
    clearTimeout(window.noorToastTimer);
    window.noorToastTimer = setTimeout(() => toast.classList.remove("show"), 2400);
}

const serverToast = $("#serverToast");
if (serverToast?.dataset.message) {
    showToast(serverToast.dataset.message);
}

const sidebar = $("#sidebar");
$("#toggle")?.addEventListener("click", () => {
    sidebar?.classList.toggle("open");
});

const trigger = $("#actionTrigger");
const dropdown = $("#actionDropdown");
const dateMenu = $("#dateMenu");

function closeProfile() {
    dropdown?.classList.remove("open");
    trigger?.classList.remove("open");
    trigger?.setAttribute("aria-expanded", "false");
}

trigger?.addEventListener("click", (event) => {
    event.stopPropagation();
    const isOpen = dropdown?.classList.toggle("open") ?? false;
    trigger.classList.toggle("open", isOpen);
    trigger.setAttribute("aria-expanded", String(isOpen));
    dateMenu?.classList.remove("open");
});

$("#dateRange")?.addEventListener("click", (event) => {
    event.stopPropagation();
    dateMenu?.classList.toggle("open");
    closeProfile();
});

document.addEventListener("click", (event) => {
    if (!dropdown?.contains(event.target) && !trigger?.contains(event.target)) {
        closeProfile();
    }

    if (!event.target.closest(".date-wrap")) {
        dateMenu?.classList.remove("open");
    }
});

document.addEventListener("keydown", (event) => {
    if (event.key === "Escape") {
        closeProfile();
        dateMenu?.classList.remove("open");
    }
});

$$(".nav a").forEach((link) => {
    link.addEventListener("click", () => {
        $$(".nav a").forEach((item) => item.classList.remove("active"));
        link.classList.add("active");
        if (window.innerWidth <= 700) sidebar?.classList.remove("open");
    });
});

const today = new Date();
const todayLabel = $("#todayLabel");
if (todayLabel) {
    todayLabel.textContent = today.toLocaleDateString("en-GB", {
        weekday: "long",
        day: "2-digit",
        month: "long",
        year: "numeric"
    });
}

$("#userSearch")?.addEventListener("input", (event) => {
    const query = event.target.value.toLowerCase().trim();

    $$("#usersTable tbody tr").forEach((row) => {
        row.style.display = (row.dataset.user || "").includes(query) ? "" : "none";
    });
});
</script>

</body>
</html>
