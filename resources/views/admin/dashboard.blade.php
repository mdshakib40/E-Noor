<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>NOOR — Storefront Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;0,600;1,500&display=swap" rel="stylesheet" />
<script src="https://unpkg.com/lucide@latest"></script>
<style>
:root{--bg:#0e0f0d;--surface:#161714;--line:#ffffff13;--line2:#ffffff22;--text:#f6f2e9;--muted:#918d84;--muted2:#6e6b63;--gold:#d7a858;--gold2:#f1cf8b;--mint:#b8d985;--blue:#a7c4e9;--red:#ed8b79;--serif:"Playfair Display",Georgia,serif;--sans:Manrope,Arial,sans-serif;--mono:"DM Mono",monospace}
*{box-sizing:border-box}
body{margin:0;background:var(--bg);color:var(--text);font-family:var(--sans);overflow-x:hidden}
button,select{font:inherit}button{cursor:pointer}a{text-decoration:none;color:inherit}.mono{font-family:var(--mono)}
.app{display:flex;min-height:100vh}
.sidebar{width:252px;flex:none;min-height:100vh;background:#11120f;border-right:1px solid var(--line);padding:25px 17px;display:flex;flex-direction:column}
.brand{padding:0 13px 34px;display:flex;align-items:center;gap:11px;letter-spacing:.23em;font-size:17px;font-weight:800}
.brand-mark{width:28px;height:28px;border:1px solid var(--gold);border-radius:50%;display:grid;place-items:center}
.brand-mark:after{content:"";width:7px;height:7px;background:var(--gold);border-radius:50%;box-shadow:0 0 0 5px #d7a8581d}
.nav-label{color:#6f6b63;font-size:9px;text-transform:uppercase;letter-spacing:.17em;font-weight:800;padding:0 13px;margin:0 0 9px}
.nav{display:grid;gap:4px}
.nav a{display:flex;align-items:center;gap:12px;color:#9c988e;font-size:11px;font-weight:700;padding:12px 13px;border-radius:12px;transition:.2s}
.nav a i{width:16px;height:16px}
.nav a:hover{color:#fff;background:#ffffff07}
.nav a.active{color:var(--gold2);background:linear-gradient(100deg,#d7a8581b,#d7a85803);border:1px solid #d7a85836}
.nav a.active i{color:var(--gold)}
.nav-count{margin-left:auto;background:#d7a858;color:#11120f;border-radius:99px;font-size:8px;padding:3px 6px}
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
.breadcrumb b{color:var(--gold2)}.breadcrumb i{width:12px}
.top-right{display:flex;align-items:center;gap:9px}
.date-wrap{position:relative}
.period{display:flex;gap:8px;align-items:center;border:1px solid var(--line);background:transparent;color:#b8b0a6;padding:9px 11px;border-radius:9px;font-size:9px}
.period i{width:13px;color:var(--gold)}
.date-menu{position:absolute;right:0;top:44px;background:#1b1d18;border:1px solid var(--line2);border-radius:10px;box-shadow:0 14px 40px #0009;display:none;z-index:180;overflow:hidden;min-width:140px}
.date-menu.open{display:block}
.date-option{display:block;width:100%;text-align:left;background:none;border:0;color:#b9b1a7;padding:10px 12px;font-size:9px}
.date-option:hover{background:#ffffff08;color:var(--gold2)}
.top-button{width:36px;height:36px;border:1px solid var(--line);border-radius:50%;background:transparent;color:#c5beb3;display:grid;place-items:center}
.top-button i{width:16px}
.hamburger{display:none}

/* Profile dropdown */
.profile-wrap{position:relative}
.profile-trigger{display:flex;align-items:center;gap:10px;padding:5px 11px 5px 5px;border:1px solid #d7a85838;border-radius:99px;background:linear-gradient(145deg,#d7a85814,#ffffff04);color:var(--text);transition:.2s}
.profile-trigger:hover,.profile-trigger.open{border-color:var(--gold);background:#d7a85822}
.p-avatar{width:30px;height:30px;border-radius:50%;flex:none;background:linear-gradient(135deg,var(--gold2),var(--gold));color:#161714;display:grid;place-items:center;font-size:10px;font-weight:900}
.p-avatar.lg{width:46px;height:46px;font-size:14px;box-shadow:0 0 0 5px #d7a85820}
.profile-trigger-text{text-align:left;line-height:1.2}
.profile-trigger-text strong{display:block;font-size:10px}
.profile-trigger-text small{display:block;font-size:8px;color:var(--muted);margin-top:2px}
.profile-trigger .chev{width:13px;height:13px;color:var(--gold);transition:.2s}
.profile-trigger.open .chev{transform:rotate(180deg)}
.profile-dropdown{position:absolute;top:52px;right:0;width:290px;background:#1a1c17;border:1px solid var(--line2);border-radius:18px;box-shadow:0 25px 70px #000c;overflow:hidden;display:none;z-index:200}
.profile-dropdown.open{display:block;animation:reveal .18s ease both}
.profile-head{display:flex;align-items:center;gap:13px;padding:18px;background:linear-gradient(120deg,#d7a85818,transparent);border-bottom:1px solid var(--line)}
.profile-who{min-width:0}
.profile-who strong{display:block;font-size:13px}
.profile-who>span{display:block;font:10px var(--mono);color:var(--muted);margin:4px 0 8px;overflow:hidden;text-overflow:ellipsis}
.profile-section{padding:8px}
.profile-caption{padding:8px 10px 6px;font-size:8px;font-weight:800;letter-spacing:.18em;color:var(--muted2)}
.profile-item{width:100%;display:flex;align-items:center;gap:12px;padding:11px 10px;border:0;border-radius:10px;background:transparent;color:#d8d0c5;font-size:11px;font-weight:600;text-align:left;transition:.15s}
.profile-item i{width:16px;height:16px;color:var(--gold)}
.profile-item:hover{background:#ffffff0a;color:var(--gold2);padding-left:14px}
.profile-visit{display:flex;align-items:center;gap:12px;margin:4px 8px 8px;padding:12px;border:1px solid #d7a85840;border-radius:13px;background:linear-gradient(100deg,#d7a85822,#d7a85808);transition:.2s}
.profile-visit:hover{border-color:var(--gold);transform:translateY(-1px)}
.visit-icon{width:34px;height:34px;border-radius:10px;flex:none;background:var(--gold);color:#161714;display:grid;place-items:center}
.visit-icon i{width:16px;height:16px}
.visit-text{flex:1;min-width:0}
.visit-text strong{display:block;font-size:11px;color:var(--gold2)}
.visit-text small{display:block;font-size:9px;color:var(--muted);margin-top:3px;line-height:1.4}
.visit-arrow{width:14px;color:var(--gold);transition:.2s}
.profile-visit:hover .visit-arrow{transform:translateX(3px)}

.welcome{padding:32px 0 25px;display:flex;align-items:end;justify-content:space-between}
.eyebrow{color:var(--gold2);font-size:10px;text-transform:uppercase;letter-spacing:.18em;font-weight:800}
.welcome h1{font:500 clamp(35px,4vw,52px)/.93 var(--serif);letter-spacing:-.065em;margin:12px 0 0}
.welcome h1 i{font-style:italic;color:var(--gold2)}
.welcome-copy{color:var(--muted);font-size:11px;margin-top:10px}
.welcome-date{text-align:right;color:var(--muted);font-size:10px;line-height:1.6}
.welcome-date strong{display:block;color:#e8e0d4;font-size:11px}
.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:13px}
.stat{position:relative;overflow:hidden;background:linear-gradient(145deg,#1b1d18,#151613);border:1px solid var(--line);border-radius:17px;padding:17px}
.stat:before{content:"";position:absolute;width:120px;height:120px;border:1px solid #d7a85812;border-radius:50%;right:-61px;bottom:-63px}
.stat-header{display:flex;align-items:center;justify-content:space-between;color:var(--muted);font-size:9px;text-transform:uppercase;letter-spacing:.1em}
.stat-header i{color:var(--gold);width:15px;height:15px}
.stat-value{font-size:26px;font-weight:700;letter-spacing:-.06em;margin:13px 0 5px}
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
.chart-grid small{position:absolute;right:0;top:-16px;color:#5e5b55;font:9px var(--mono)}
.chart svg{position:absolute;left:0;top:5px;width:calc(100% - 35px);height:200px;overflow:visible}
.chart-days{position:absolute;left:0;right:35px;bottom:0;display:flex;justify-content:space-between;color:#6b665e;font:9px var(--mono)}
.chart-legend{display:flex;gap:13px;font-size:9px;color:var(--muted)}
.chart-legend span{display:flex;align-items:center;gap:5px}
.legend-dot{width:7px;height:7px;border-radius:50%;background:var(--gold)}.legend-dot.mint{background:var(--mint)}
.mix-card{display:flex;flex-direction:column}
.donut-row{display:flex;align-items:center;justify-content:center;gap:22px;margin:13px 0 20px}
.donut{width:142px;height:142px;border-radius:50%;background:conic-gradient(var(--gold) 0 57%,var(--mint) 57% 84%,#3b3932 84%);display:grid;place-items:center}
.donut-inner{width:108px;height:108px;border-radius:50%;background:var(--surface);display:grid;place-items:center;text-align:center}
.donut-inner strong{font-size:22px;display:block}
.donut-inner span{display:block;color:var(--muted);font-size:9px;margin-top:-21px}
.mix-items{display:grid;gap:11px;flex:1}
.mix-item{display:flex;align-items:center;gap:7px;font-size:10px;color:#aaa39a}
.mix-item b{margin-left:auto;color:#f0e9de}
.mix-item i{width:8px;height:8px;border-radius:50%;background:var(--gold)}
.mix-item i.mint{background:var(--mint)}.mix-item i.grey{background:#3b3932}
.mix-insight{border-top:1px solid var(--line);padding-top:13px;margin-top:16px;font-size:9px;color:#9a948b;line-height:1.6}
.mix-insight b{color:var(--gold2)}
.orders{margin-top:15px;padding:0}
.orders-head{padding:18px 19px;display:flex;align-items:center;justify-content:space-between}
.view-all{color:var(--gold2);font-size:9px;border:0;background:transparent}
.table-scroll{overflow-x:auto}
table{width:100%;border-collapse:collapse}
th{text-align:left;padding:11px 18px;color:#67645c;font-size:8px;letter-spacing:.13em;text-transform:uppercase;border-top:1px solid var(--line);border-bottom:1px solid var(--line)}
td{padding:13px 18px;border-bottom:1px solid #ffffff09;color:#d0c8bd;font-size:10px;white-space:nowrap}
tbody tr:hover td{background:#ffffff05}
.order-id{display:block;color:#666159;font:8px var(--mono);margin-top:4px}
.customer{display:flex;align-items:center;gap:8px}
.mini-avatar{width:24px;height:24px;border-radius:50%;background:#564a3a;display:grid;place-items:center;color:var(--gold2);font-size:8px;font-weight:800}
.status{display:inline-flex;align-items:center;gap:6px;padding:5px 8px;border-radius:99px;font-size:8px;font-weight:800}
.status i{width:5px;height:5px;border-radius:50%;background:currentColor}
.status.blue{background:#a7c4e916;color:var(--blue)}
.status.gold{background:#d7a85818;color:var(--gold2)}
.status.green{background:#b8d98516;color:var(--mint)}
.amount{font-weight:800;color:#f2eadf!important;text-align:right}

/* Users */
.users-panel{margin-top:15px}
.users-toolbar{display:flex;gap:10px;align-items:center}
.users-search{background:#ffffff08;border:1px solid var(--line);border-radius:8px;color:var(--text);padding:8px 10px;font-size:9px;outline:0;width:170px}
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
.website-link:hover{background:#d7a85825}
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
.rider-avatar{width:30px;height:30px;border-radius:50%;background:#3e4c43;display:grid;place-items:center;color:var(--mint);font-size:9px;font-weight:800}
.rider-info{flex:1}
.rider-info strong{font-size:10px;display:block}
.rider-info span{font-size:9px;color:var(--muted);display:block;margin-top:3px}
.rider-state{font-size:8px;font-weight:800;color:var(--mint)}
.rider-state.busy{color:var(--gold2)}
.quick-note{background:linear-gradient(145deg,#24251e,#181a16);display:flex;flex-direction:column;justify-content:space-between}
.quote{font:500 22px/1.16 var(--serif);letter-spacing:-.03em;margin:19px 0 0;max-width:260px}
.quote em{font-style:italic;color:var(--gold2)}
.note-foot{font-size:9px;color:var(--muted);line-height:1.6;border-top:1px solid var(--line);padding-top:14px}
.note-foot b{color:var(--mint)}

.toast-ui{position:fixed;bottom:22px;left:50%;transform:translate(-50%,20px);opacity:0;pointer-events:none;background:var(--gold);color:#151612;border-radius:99px;padding:11px 16px;font-size:10px;font-weight:800;z-index:250;transition:.22s}
.toast-ui.show{opacity:1;transform:translate(-50%,0)}
.reveal{animation:reveal .55s ease both}
@keyframes reveal{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:none}}
@media(prefers-reduced-motion:reduce){*{animation:none!important;transition:none!important}}

@media(max-width:1050px){
.sidebar{width:215px}.main{padding:0 24px 35px}
.grid-main{grid-template-columns:1fr}
.bottom-grid{grid-template-columns:1fr 1fr}
.quick-note{min-height:230px}
.stats{grid-template-columns:repeat(2,1fr)}}
@media(max-width:700px){
.sidebar{display:none;position:fixed;z-index:60;width:245px;height:100vh;box-shadow:25px 0 65px #000}
.sidebar.open{display:flex}
.main{padding:0 14px 30px}
.topbar{height:69px}
.period{display:none}
.hamburger{display:grid}
.profile-trigger-text,.profile-trigger .chev{display:none}
.profile-trigger{padding:4px}
.profile-dropdown{width:min(290px,calc(100vw - 28px))}
.welcome{padding:26px 0 21px;align-items:start}
.welcome h1{font-size:38px}.welcome-date{font-size:9px}
.stats{gap:8px}.stat{padding:13px}.stat-value{font-size:21px}.stat-header,.stat-meta{font-size:8px}
.card{padding:14px;border-radius:15px}
.chart{height:195px}.chart svg{height:165px}
.donut-row{gap:14px}
.donut{width:112px;height:112px}.donut-inner{width:84px;height:84px}
.donut-inner strong{font-size:18px}.donut-inner span{font-size:8px;margin-top:-18px}
.bottom-grid{grid-template-columns:1fr}
td,th{padding-left:12px;padding-right:12px}
.orders th:nth-child(3),.orders td:nth-child(3){display:none}
.users-toolbar{align-items:stretch;flex-direction:column}
.users-search{width:100%}
.website-banner{align-items:flex-start;flex-direction:column}
.website-link{width:100%;text-align:center}}
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
@endphp


<div class="app">

    {{-- =========================================================
        SIDEBAR
    ========================================================== --}}

    <aside class="sidebar" id="sidebar">

        <div class="brand">
            <span class="brand-mark"></span>
            NOOR
        </div>


        <div class="nav-label">
            Workspace
        </div>

        <nav class="nav">

            <a class="active" href="#overview">
                <i data-lucide="layout-dashboard"></i>
                Overview
            </a>

            <a href="#orders">
                <i data-lucide="shopping-bag"></i>
                Orders

                <span class="nav-count">
                    12
                </span>
            </a>

            <a href="#restaurants">
                <i data-lucide="store"></i>
                Restaurants
            </a>

            <a href="#customers">
                <i data-lucide="users"></i>
                Customers
            </a>

            <a href="#user-management">
                <i data-lucide="user-cog"></i>
                Users

                <span class="nav-count">
                    {{ $users->count() }}
                </span>
            </a>

            <a href="#analytics">
                <i data-lucide="chart-no-axes-combined"></i>
                Analytics
            </a>

        </nav>


        <div class="side-spacer"></div>


        <div class="nav-label">
            Manage
        </div>

        <nav class="nav">

            <a href="#">
                <i data-lucide="megaphone"></i>
                Campaigns
            </a>

            <a href="#">
                <i data-lucide="ticket-percent"></i>
                Offers
            </a>

            <a href="#">
                <i data-lucide="settings-2"></i>
                Settings
            </a>

        </nav>


        <div class="system">

            <div class="system-head">
                <span class="pulse"></span>
                Everything is live
            </div>

            <p>
                Orders are moving normally across NOOR.
            </p>

        </div>


        {{-- Current logged in user --}}

        <div class="user">

            <div class="avatar">
                {{ strtoupper(substr($currentUser->name, 0, 2)) }}
            </div>

            <div>

                <strong>
                    {{ $currentUser->name }}
                </strong>

                <span>
                    {{ $roleLabels[$currentUser->role->value] ?? 'User' }}
                </span>

            </div>

            <i
                data-lucide="more-horizontal"
                style="width:14px;margin-left:auto;color:#777">
            </i>

        </div>

    </aside>


    {{-- =========================================================
        MAIN
    ========================================================== --}}

    <main class="main">


        {{-- =====================================================
            TOP BAR
        ====================================================== --}}

        <header class="topbar">

            <div class="breadcrumb">

                <span>
                    NOOR
                </span>

                <i data-lucide="chevron-right"></i>

                <b>
                    Storefront admin
                </b>

                <i data-lucide="chevron-right"></i>

                <span>
                    Overview
                </span>

            </div>


            <div class="top-right">


                {{-- Date --}}

                <div class="date-wrap">

                    <button
                        class="period"
                        id="dateRange">

                        <i data-lucide="calendar-days"></i>

                        <span id="dateLabel">
                            Loading date…
                        </span>

                        <i data-lucide="chevron-down"></i>

                    </button>


                    <div
                        class="date-menu"
                        id="dateMenu">

                        <button
                            class="date-option"
                            data-range="7">

                            Last 7 days

                        </button>

                        <button
                            class="date-option"
                            data-range="30">

                            Last 30 days

                        </button>

                        <button
                            class="date-option"
                            data-range="90">

                            Last 90 days

                        </button>

                    </div>

                </div>


                {{-- =================================================
                    PROFILE
                ================================================== --}}

                <div class="profile-wrap">

                    <button
                        class="profile-trigger"
                        id="actionTrigger"
                        aria-label="Open account menu"
                        aria-expanded="false">


                        <span class="p-avatar">

                            {{ strtoupper(substr($currentUser->name, 0, 2)) }}

                        </span>


                        <span class="profile-trigger-text">

                            <strong>
                                {{ $currentUser->name }}
                            </strong>

                            <small>
                                {{ $roleLabels[$currentUser->role->value] ?? 'User' }}
                            </small>

                        </span>


                        <i
                            data-lucide="chevron-down"
                            class="chev">
                        </i>

                    </button>


                    <div
                        class="profile-dropdown"
                        id="actionDropdown">


                        <div class="profile-head">

                            <span class="p-avatar lg">

                                {{ strtoupper(substr($currentUser->name, 0, 2)) }}

                            </span>


                            <div class="profile-who">

                                <strong>
                                    {{ $currentUser->name }}
                                </strong>

                                <span>
                                    {{ $currentUser->email }}
                                </span>

                                <em class="role-pill">
                                    {{ $currentUser->role->value }}
                                </em>

                            </div>

                        </div>


                        <div class="profile-section">

                            <div class="profile-caption">
                                NOOR
                            </div>


                            <a
                                class="profile-item"
                                href="#overview">

                                <i data-lucide="layout-dashboard"></i>

                                <span>
                                    Dashboard
                                </span>

                            </a>


                            <a
                                class="profile-item"
                                href="#user-management">

                                <i data-lucide="user-cog"></i>

                                <span>
                                    User Management
                                </span>

                            </a>


                            <button
                                class="profile-item"
                                id="settingsAction">

                                <i data-lucide="settings-2"></i>

                                <span>
                                    Settings
                                </span>

                            </button>

                        </div>


                        {{-- Visit Website --}}

                        <a
                            class="profile-visit"
                            href="{{ route('home') }}"
                            id="websiteAction">

                            <span class="visit-icon">

                                <i data-lucide="external-link"></i>

                            </span>


                            <span class="visit-text">

                                <strong>
                                    Visit Website
                                </strong>

                                <small>
                                    NOOR-এর Home Page-এ ফিরে যাওয়ার লিংক
                                </small>

                            </span>


                            <i
                                data-lucide="arrow-right"
                                class="visit-arrow">
                            </i>

                        </a>

                    </div>

                </div>


                <button
                    class="top-button hamburger"
                    id="toggle"
                    aria-label="Toggle menu">

                    <i data-lucide="menu"></i>

                </button>

            </div>

        </header>


        {{-- =====================================================
            FLASH MESSAGES
        ====================================================== --}}

        @if(session('success'))

            <div
                id="serverToast"
                data-message="{{ session('success') }}">
            </div>

        @endif


        @if(session('error'))

            <div
                id="serverToast"
                data-message="{{ session('error') }}">
            </div>

        @endif


        {{-- =====================================================
            WELCOME
        ====================================================== --}}

        <section
            class="welcome reveal"
            id="overview">

            <div>

                <div class="eyebrow">
                    Platform overview
                </div>


                <h1>
                    Good evening,
                    <i>
                        {{ $currentUser->name }}.
                    </i>
                </h1>


                <p class="welcome-copy">
                    Here’s what is happening across your table today.
                </p>

            </div>


            <div class="welcome-date">

                <span id="todayLabel">
                    Today
                </span>

                <strong>
                    Last updated just now
                </strong>

            </div>

        </section>


        {{-- =====================================================
            STATS
        ====================================================== --}}

        <section class="stats reveal">


            <div class="stat">

                <div class="stat-header">

                    Platform revenue

                    <i data-lucide="wallet-cards"></i>

                </div>

                <div class="stat-value">
                    ৳8,42,680
                </div>

                <div class="stat-meta">
                    ↑ 18.4%
                    <span>
                        vs last week
                    </span>
                </div>

            </div>


            <div class="stat">

                <div class="stat-header">

                    Total orders

                    <i data-lucide="shopping-bag"></i>

                </div>

                <div class="stat-value">
                    1,284
                </div>

                <div class="stat-meta">
                    ↑ 12.8%
                    <span>
                        vs last week
                    </span>
                </div>

            </div>


            <div class="stat">

                <div class="stat-header">

                    Restaurants

                    <i data-lucide="store"></i>

                </div>

                <div class="stat-value">
                    86
                </div>

                <div class="stat-meta">
                    ↑ 5
                    <span>
                        this month
                    </span>
                </div>

            </div>


            <div class="stat">

                <div class="stat-header">

                    Customers

                    <i data-lucide="users"></i>

                </div>

                <div class="stat-value">
                    {{ $users->where('role', UserRole::CUSTOMER)->count() }}
                </div>

                <div class="stat-meta">
                    Live
                    <span>
                        registered users
                    </span>
                </div>

            </div>

        </section>


        {{-- =====================================================
            ANALYTICS
        ====================================================== --}}

        <div
            class="grid-main"
            id="analytics">


            <section class="card reveal">

                <div class="card-head">

                    <div>

                        <div class="card-title">
                            Revenue overview
                        </div>

                        <div class="card-sub">
                            Platform performance · last 7 days
                        </div>

                    </div>


                    <div
                        style="display:flex;align-items:center;gap:15px">

                        <div class="chart-legend">

                            <span>
                                <i class="legend-dot"></i>
                                Revenue
                            </span>

                            <span>
                                <i class="legend-dot mint"></i>
                                Orders
                            </span>

                        </div>


                        <select class="card-select">

                            <option>
                                Last 7 days
                            </option>

                            <option>
                                Last 30 days
                            </option>

                        </select>

                    </div>

                </div>


                <div class="chart">

                    <div class="chart-grid">

                        <span>
                            <small>৳200k</small>
                        </span>

                        <span>
                            <small>৳150k</small>
                        </span>

                        <span>
                            <small>৳100k</small>
                        </span>

                        <span>
                            <small>৳50k</small>
                        </span>

                        <span>
                            <small>৳0</small>
                        </span>

                    </div>


                    <svg
                        viewBox="0 0 800 210"
                        preserveAspectRatio="none">

                        <defs>

                            <linearGradient
                                id="goldFill"
                                x1="0"
                                y1="0"
                                x2="0"
                                y2="1">

                                <stop
                                    offset="0"
                                    stop-color="#d7a858"
                                    stop-opacity=".3"/>

                                <stop
                                    offset="1"
                                    stop-color="#d7a858"
                                    stop-opacity="0"/>

                            </linearGradient>

                        </defs>


                        <path
                            d="M0 165 C45 155 70 120 115 135 S185 108 225 118 S290 76 335 95 S395 48 440 72 S500 55 545 65 S610 28 650 48 S725 18 800 30 L800 210 L0 210Z"
                            fill="url(#goldFill)"/>


                        <path
                            d="M0 165 C45 155 70 120 115 135 S185 108 225 118 S290 76 335 95 S395 48 440 72 S500 55 545 65 S610 28 650 48 S725 18 800 30"
                            fill="none"
                            stroke="#d7a858"
                            stroke-width="3"/>


                        <path
                            d="M0 181 C45 174 70 157 115 166 S185 145 225 156 S290 127 335 138 S395 116 440 132 S500 113 545 122 S610 98 650 109 S725 86 800 95"
                            fill="none"
                            stroke="#b8d985"
                            stroke-width="2"
                            stroke-dasharray="4 4"/>

                    </svg>


                    <div class="chart-days">

                        <span>MON</span>
                        <span>TUE</span>
                        <span>WED</span>
                        <span>THU</span>
                        <span>FRI</span>
                        <span>SAT</span>
                        <span>SUN</span>

                    </div>

                </div>

            </section>


            <section class="card mix-card reveal">

                <div class="card-head">

                    <div>

                        <div class="card-title">
                            Order mix
                        </div>

                        <div class="card-sub">
                            Current platform orders
                        </div>

                    </div>

                    <i
                        data-lucide="pie-chart"
                        class="panel-icon">
                    </i>

                </div>


                <div class="donut-row">

                    <div class="donut">

                        <div class="donut-inner">

                            <strong>
                                1,284
                            </strong>

                            <span>
                                orders
                            </span>

                        </div>

                    </div>


                    <div class="mix-items">

                        <div class="mix-item">

                            <i></i>
                            Delivered
                            <b>57%</b>

                        </div>

                        <div class="mix-item">

                            <i class="mint"></i>
                            In progress
                            <b>27%</b>

                        </div>

                        <div class="mix-item">

                            <i class="grey"></i>
                            Cancelled
                            <b>16%</b>

                        </div>

                    </div>

                </div>


                <div class="mix-insight">

                    <b>Good signal.</b>

                    Completion is up 9.6%
                    since last Tuesday.

                </div>

            </section>

        </div>


        {{-- =====================================================
            ORDERS
        ====================================================== --}}

        <section
            class="card orders reveal"
            id="orders">

            <div class="orders-head">

                <div>

                    <div class="card-title">
                        Live orders
                    </div>

                    <div class="card-sub">
                        Latest activity across all restaurants
                    </div>

                </div>

                <button class="view-all">
                    View all orders →
                </button>

            </div>


            <div class="table-scroll">

                <table>

                    <thead>

                        <tr>

                            <th>
                                Order
                            </th>

                            <th>
                                Customer
                            </th>

                            <th>
                                Restaurant
                            </th>

                            <th>
                                Status
                            </th>

                            <th style="text-align:right">
                                Total
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <tr>

                            <td>
                                <b>#NO-8421</b>
                                <span class="order-id">
                                    2 min ago
                                </span>
                            </td>

                            <td>
                                <div class="customer">

                                    <span class="mini-avatar">
                                        SA
                                    </span>

                                    Sarah Ahmed

                                </div>
                            </td>

                            <td>
                                Rong Kitchen
                            </td>

                            <td>
                                <span class="status blue">
                                    <i></i>
                                    On the way
                                </span>
                            </td>

                            <td class="amount">
                                ৳1,240
                            </td>

                        </tr>


                        <tr>

                            <td>
                                <b>#NO-8420</b>
                                <span class="order-id">
                                    5 min ago
                                </span>
                            </td>

                            <td>
                                <div class="customer">

                                    <span
                                        class="mini-avatar"
                                        style="background:#654c3b">

                                        MR

                                    </span>

                                    Mahin Rahman

                                </div>
                            </td>

                            <td>
                                Kacchi Bhai
                            </td>

                            <td>
                                <span class="status gold">
                                    <i></i>
                                    Preparing
                                </span>
                            </td>

                            <td class="amount">
                                ৳820
                            </td>

                        </tr>


                        <tr>

                            <td>
                                <b>#NO-8419</b>
                                <span class="order-id">
                                    8 min ago
                                </span>
                            </td>

                            <td>
                                <div class="customer">

                                    <span
                                        class="mini-avatar"
                                        style="background:#3f5a50">

                                        NI

                                    </span>

                                    Nabila Islam

                                </div>
                            </td>

                            <td>
                                Grill Room
                            </td>

                            <td>
                                <span class="status green">
                                    <i></i>
                                    Delivered
                                </span>
                            </td>

                            <td class="amount">
                                ৳1,560
                            </td>

                        </tr>


                        <tr>

                            <td>
                                <b>#NO-8418</b>
                                <span class="order-id">
                                    13 min ago
                                </span>
                            </td>

                            <td>
                                <div class="customer">

                                    <span
                                        class="mini-avatar"
                                        style="background:#58425e">

                                        RA

                                    </span>

                                    Rafi Ahmed

                                </div>
                            </td>

                            <td>
                                Bhorta Ghor
                            </td>

                            <td>
                                <span class="status green">
                                    <i></i>
                                    Delivered
                                </span>
                            </td>

                            <td class="amount">
                                ৳480
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </section>


        {{-- =====================================================
            USER ROLE MANAGEMENT
        ====================================================== --}}

        <section
            class="card users-panel reveal"
            id="user-management">


            <div class="card-head">

                <div>

                    <div class="card-title">
                        User role management
                    </div>

                    <div class="card-sub">
                        Manage access levels across the NOOR platform
                    </div>

                </div>


                <div class="users-toolbar">

                    <input
                        class="users-search"
                        id="userSearch"
                        placeholder="Search name or email…" />

                </div>

            </div>


            <div class="table-scroll">

                <table id="usersTable">

                    <thead>

                        <tr>

                            <th>
                                User
                            </th>

                            <th>
                                Email
                            </th>

                            <th>
                                Current role
                            </th>

                            <th>
                                Change role
                            </th>

                            <th style="text-align:right">
                                Access
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                        @forelse($users as $user)

                            @php

                                $role = $user->role->value;

                                $roleClass = match ($user->role) {

                                    UserRole::CUSTOMER =>
                                        'customer',

                                    UserRole::RIDER =>
                                        'rider',

                                    UserRole::RESTAURANT_STAFF =>
                                        'staff',

                                    default =>
                                        '',

                                };

                            @endphp


                            <tr
                                data-user="{{ strtolower($user->name . ' ' . $user->email) }}">


                                {{-- User --}}

                                <td>

                                    <div class="customer">

                                        <span class="mini-avatar">

                                            {{ strtoupper(substr($user->name, 0, 2)) }}

                                        </span>


                                        <b>
                                            {{ $user->name }}
                                        </b>

                                    </div>

                                </td>


                                {{-- Email --}}

                                <td>
                                    {{ $user->email }}
                                </td>


                                {{-- Current Role --}}

                                <td>

                                    <span
                                        class="role-pill {{ $roleClass }}">

                                        {{ $role }}

                                    </span>

                                </td>


                                {{-- Role Form --}}

                                <td>


                                    @if($user->id === auth()->id())

                                        <select
                                            class="role-select"
                                            disabled>

                                            <option selected>
                                                {{ $role }}
                                            </option>

                                        </select>

                                    @else

                                        <form
                                            action="{{ route('admin.users.role.update', $user) }}"
                                            method="POST"
                                            class="role-form"
                                            style="display:flex;align-items:center;gap:7px">

                                            @csrf

                                            @method('PATCH')


                                            <select
                                                name="role"
                                                class="role-select"
                                                onchange="this.form.submit()">


                                                @foreach(UserRole::cases() as $roleOption)

                                                    <option
                                                        value="{{ $roleOption->value }}"
                                                        @selected($user->role === $roleOption)>

                                                        {{ $roleOption->value }}

                                                    </option>

                                                @endforeach


                                            </select>

                                        </form>

                                    @endif


                                </td>


                                {{-- Access --}}

                                <td class="amount">


                                    @if($user->id === auth()->id())

                                        Protected

                                    @else

                                        Editable

                                    @endif


                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    style="text-align:center;padding:30px;color:#777">

                                    No users found.

                                </td>

                            </tr>

                        @endforelse


                    </tbody>

                </table>

            </div>


            <div
                class="role-note"
                style="margin-top:13px">

                <i
                    data-lucide="shield-check"
                    style="width:13px;vertical-align:middle;color:var(--mint)">
                </i>

                Role changes are saved directly to the
                <span class="mono">
                    users.role
                </span>
                database field.

                Your current Super Admin account is protected.

            </div>

        </section>


        {{-- =====================================================
            WEBSITE
        ====================================================== --}}

        <div class="website-banner">

            <div>

                <div class="card-title">
                    Storefront preview
                </div>

                <p>
                    Open the customer-facing NOOR website in this same tab.
                </p>

            </div>


            <a
                href="{{ route('home') }}"
                class="website-link">

                Visit website

                <i
                    data-lucide="arrow-right"
                    style="width:12px;vertical-align:middle">
                </i>

            </a>

        </div>


        {{-- =====================================================
            BOTTOM GRID
        ====================================================== --}}

        <div class="bottom-grid">


            <section
                class="card reveal"
                id="restaurants">

                <div class="card-head">

                    <div>

                        <div class="card-title">
                            Top restaurants
                        </div>

                        <div class="card-sub">
                            Revenue this week
                        </div>

                    </div>

                    <i
                        data-lucide="store"
                        class="panel-icon">
                    </i>

                </div>


                <div class="restaurant-list">

                    <div class="rank-row">

                        <span class="rank-num">
                            01
                        </span>

                        <div class="rank-info">

                            <div class="rank-info-top">

                                <strong>
                                    Rong Kitchen
                                </strong>

                                <span>
                                    ৳1,82,400
                                </span>

                            </div>

                            <div class="progress">
                                <i style="width:92%"></i>
                            </div>

                        </div>

                    </div>


                    <div class="rank-row">

                        <span class="rank-num">
                            02
                        </span>

                        <div class="rank-info">

                            <div class="rank-info-top">

                                <strong>
                                    Kacchi Bhai
                                </strong>

                                <span>
                                    ৳1,46,820
                                </span>

                            </div>

                            <div class="progress">
                                <i style="width:78%"></i>
                            </div>

                        </div>

                    </div>


                    <div class="rank-row">

                        <span class="rank-num">
                            03
                        </span>

                        <div class="rank-info">

                            <div class="rank-info-top">

                                <strong>
                                    Grill Room
                                </strong>

                                <span>
                                    ৳1,19,500
                                </span>

                            </div>

                            <div class="progress">
                                <i style="width:65%"></i>
                            </div>

                        </div>

                    </div>


                    <div class="rank-row">

                        <span class="rank-num">
                            04
                        </span>

                        <div class="rank-info">

                            <div class="rank-info-top">

                                <strong>
                                    Bhorta Ghor
                                </strong>

                                <span>
                                    ৳98,240
                                </span>

                            </div>

                            <div class="progress">
                                <i style="width:53%"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </section>


            <section
                class="card reveal"
                id="customers">

                <div class="card-head">

                    <div>

                        <div class="card-title">
                            Delivery network
                        </div>

                        <div class="card-sub">
                            Rider availability right now
                        </div>

                    </div>

                    <i
                        data-lucide="bike"
                        class="panel-icon">
                    </i>

                </div>


                <div class="rider-list">

                    <div class="rider-row">

                        <span class="rider-avatar">
                            RK
                        </span>

                        <div class="rider-info">

                            <strong>
                                Riyad Khan
                            </strong>

                            <span>
                                Gulshan route · 3 orders
                            </span>

                        </div>

                        <span class="rider-state">
                            Available
                        </span>

                    </div>


                    <div class="rider-row">

                        <span
                            class="rider-avatar"
                            style="background:#4b4355">

                            JM

                        </span>

                        <div class="rider-info">

                            <strong>
                                Jahid Mia
                            </strong>

                            <span>
                                Dhanmondi route · 2 orders
                            </span>

                        </div>

                        <span class="rider-state busy">
                            Delivering
                        </span>

                    </div>


                    <div class="rider-row">

                        <span
                            class="rider-avatar"
                            style="background:#544334">

                            AS

                        </span>

                        <div class="rider-info">

                            <strong>
                                Alim Sheikh
                            </strong>

                            <span>
                                Mohammadpur route · 1 order
                            </span>

                        </div>

                        <span class="rider-state">
                            Available
                        </span>

                    </div>


                    <div class="rider-row">

                        <span
                            class="rider-avatar"
                            style="background:#3e5650">

                            NH

                        </span>

                        <div class="rider-info">

                            <strong>
                                Noman Hossain
                            </strong>

                            <span>
                                Banani route · 4 orders
                            </span>

                        </div>

                        <span class="rider-state busy">
                            Delivering
                        </span>

                    </div>

                </div>

            </section>


            <section
                class="card quick-note reveal">

                <div>

                    <div class="card-head">

                        <div>

                            <div class="card-title">
                                Quick insight
                            </div>

                            <div class="card-sub">
                                A note from your data
                            </div>

                        </div>

                        <i
                            data-lucide="lightbulb"
                            class="panel-icon">
                        </i>

                    </div>


                    <div class="quote">

                        “Your customers spend more when the evening starts
                        <em>before 7pm.</em>”

                    </div>

                </div>


                <div class="note-foot">

                    <b>↑ 18%</b>

                    average order value between 5–7pm.

                </div>

            </section>


        </div>

    </main>

</div>


{{-- =========================================================
    TOAST
========================================================== --}}

<div
    class="toast-ui"
    id="toastUi"
    role="status">
</div>


{{-- =========================================================
    JAVASCRIPT
========================================================== --}}

<script>

lucide.createIcons();


const $ = (s) => document.querySelector(s);

const $$ = (s) => [
    ...document.querySelectorAll(s)
];


/*
|--------------------------------------------------------------------------
| Toast
|--------------------------------------------------------------------------
*/

const toast = (message) => {

    const t = $("#toastUi");

    if (!t) return;

    t.textContent = message;

    t.classList.add("show");

    clearTimeout(toast.timer);

    toast.timer = setTimeout(() => {

        t.classList.remove("show");

    }, 2200);

};


/*
|--------------------------------------------------------------------------
| Laravel Flash Toast
|--------------------------------------------------------------------------
*/

const serverToast = $("#serverToast");

if (serverToast) {

    const message = serverToast.dataset.message;

    if (message) {

        setTimeout(() => {

            toast(message);

        }, 150);

    }

}


/*
|--------------------------------------------------------------------------
| Sidebar Mobile
|--------------------------------------------------------------------------
*/

const sidebar = $("#sidebar");

$("#toggle")?.addEventListener("click", () => {

    sidebar.classList.toggle("open");

});


$$(".nav a").forEach((link) => {

    link.addEventListener("click", () => {

        $$(".nav a").forEach((item) => {

            item.classList.remove("active");

        });

        link.classList.add("active");


        if (innerWidth <= 700) {

            sidebar.classList.remove("open");

        }

    });

});


/*
|--------------------------------------------------------------------------
| Profile Dropdown
|--------------------------------------------------------------------------
*/

const trigger = $("#actionTrigger");

const dropdown = $("#actionDropdown");

const dateMenu = $("#dateMenu");


const closeProfile = () => {

    if (!dropdown || !trigger) return;

    dropdown.classList.remove("open");

    trigger.classList.remove("open");

    trigger.setAttribute(
        "aria-expanded",
        "false"
    );

};


trigger?.addEventListener("click", (e) => {

    e.stopPropagation();

    const open =
        dropdown.classList.toggle("open");

    trigger.classList.toggle(
        "open",
        open
    );

    trigger.setAttribute(
        "aria-expanded",
        open
    );

    dateMenu?.classList.remove("open");

});


$("#settingsAction")?.addEventListener(
    "click",
    () => {

        closeProfile();

        toast(
            "Settings panel is ready for your workspace preferences"
        );

    }
);


$$(".profile-item[href], #websiteAction")
    .forEach((el) => {

        el.addEventListener(
            "click",
            closeProfile
        );

    });


document.addEventListener(
    "click",
    (e) => {

        if (
            dropdown &&
            trigger &&
            !dropdown.contains(e.target) &&
            !trigger.contains(e.target)
        ) {

            closeProfile();

        }


        if (
            !e.target.closest(".date-wrap")
        ) {

            dateMenu?.classList.remove("open");

        }

    }
);


document.addEventListener(
    "keydown",
    (e) => {

        if (e.key === "Escape") {

            closeProfile();

            dateMenu?.classList.remove(
                "open"
            );

        }

    }
);


/*
|--------------------------------------------------------------------------
| Date Range
|--------------------------------------------------------------------------
*/

const fmt = (d) =>

    d.toLocaleDateString(
        "en-GB",
        {
            day: "2-digit",
            month: "short",
            year: "numeric"
        }
    );


const today = new Date();


const setRange = (days) => {

    const start = new Date(today);

    start.setDate(
        today.getDate() - days + 1
    );


    $("#dateLabel").textContent =
        `${fmt(start)} — ${fmt(today)}`;

};


setRange(7);


$("#todayLabel").textContent =
    today.toLocaleDateString(
        "en-GB",
        {
            weekday: "long",
            day: "2-digit",
            month: "long",
            year: "numeric"
        }
    );


$("#dateRange")?.addEventListener(
    "click",
    (e) => {

        e.stopPropagation();

        dateMenu?.classList.toggle(
            "open"
        );

        closeProfile();

    }
);


$$(".date-option").forEach((b) => {

    b.addEventListener("click", () => {

        const days =
            Number(b.dataset.range);

        setRange(days);

        dateMenu?.classList.remove(
            "open"
        );

        toast(
            `Showing last ${days} days`
        );

    });

});


/*
|--------------------------------------------------------------------------
| User Search
|--------------------------------------------------------------------------
*/

$("#userSearch")?.addEventListener(
    "input",
    (e) => {

        const q =
            e.target.value.toLowerCase().trim();


        $$("#usersTable tbody tr")
            .forEach((row) => {

                const data =
                    row.dataset.user || "";


                row.style.display =
                    data.includes(q)
                        ? ""
                        : "none";

            });

    }
);

</script>

</body>
</html>
</html>