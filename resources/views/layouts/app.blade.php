

<!doctype html>

<html lang="id">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Posting Sosmed') - Posting Sosmed</title>

    <link rel="stylesheet" href="{{ asset('css/posbersama.css') }}">

    <style>

        /* LOGO DAN SIDEBAR */

        .side {

            display: flex;

            flex-direction: column;

            min-height: 100vh;

        }

        .brand {

            display: flex;

            align-items: center;

            gap: 10px;

            padding: 12px;

            font-size: 18px;

            font-weight: 700;

            white-space: nowrap;

        }

        .brand-bird {

            display: block;

            width: 44px;

            height: 44px;

            flex-shrink: 0;

        }

        .nav {

            flex: 1;

        }

        .side-bottom {

            margin-top: auto;

            padding: 14px 12px 20px;

            border-top: 1px solid rgba(255,255,255,.12);

        }

        .side-logout {

            display: flex;

            align-items: center;

            gap: 12px;

            width: 100%;

            padding: 12px 14px;

            color: white;

            background: rgba(255,255,255,.06);

            border: 1px solid rgba(255,255,255,.12);

            border-radius: 10px;

            font: inherit;

            cursor: pointer;

            text-align: left;

        }

        .side-logout:hover {

            background: rgba(255,255,255,.15);

        }

        .side-logout svg {

            width: 20px;

            height: 20px;

        }

        /* HEADER */

        .top {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 16px;

        }

        .top h1 {

            margin: 0;

        }

        .top-actions {

            display: flex;

            align-items: center;

            gap: 10px;

        }

        /* TOMBOL IKON */

        .dropdown {

            position: relative;

        }

        .icon-button {

            position: relative;

            display: flex;

            align-items: center;

            justify-content: center;

            width: 42px;

            height: 42px;

            padding: 0;

            color: #123052;

            background: white;

            border: 1px solid #d5dfeb;

            border-radius: 12px;

            cursor: pointer;

            list-style: none;

        }

        .icon-button::-webkit-details-marker {

            display: none;

        }

        .icon-button svg {

            width: 22px;

            height: 22px;

        }

        .icon-button:hover {

            background: #f0f5fb;

        }

        .icon-button .cnt {

            position: absolute;

            top: -6px;

            right: -6px;

            display: flex;

            align-items: center;

            justify-content: center;

            min-width: 18px;

            height: 18px;

            padding: 0 4px;

            color: white;

            background: #e53945;

            border-radius: 20px;

            font-size: 11px;

            font-weight: bold;

        }

        /* DROPDOWN PROFIL */

        .dropdown-pop {

            position: absolute;

            top: calc(100% + 10px);

            right: 0;

            z-index: 1000;

            width: 270px;

            padding: 10px;

            color: #17314f;

            background: white;

            border: 1px solid #dce5ef;

            border-radius: 14px;

            box-shadow: 0 12px 32px rgba(13,35,62,.18);

        }

        .dropdown-heading {

            display: flex;

            align-items: center;

            gap: 10px;

            padding: 10px;

            margin-bottom: 6px;

            border-bottom: 1px solid #e8edf4;

        }

        .avatar-circle {

            display: flex;

            align-items: center;

            justify-content: center;

            width: 42px;

            height: 42px;

            flex-shrink: 0;

            color: #173b65;

            background: #e5effc;

            border-radius: 50%;

        }

        .avatar-circle svg {

            width: 24px;

            height: 24px;

        }

        .user-info {

            min-width: 0;

        }

        .user-info strong,

        .user-info small {

            display: block;

            overflow-wrap: anywhere;

        }

        .user-info strong {

            font-size: 14px;

        }

        .user-info small {

            margin-top: 4px;

            color: #74859a;

            font-size: 12px;

        }

        .dropdown-link,

        .dropdown-logout {

            display: flex;

            align-items: center;

            gap: 10px;

            width: 100%;

            padding: 11px 10px;

            color: #233c59;

            background: transparent;

            border: 0;

            border-radius: 8px;

            font: inherit;

            font-size: 13px;

            text-align: left;

            text-decoration: none;

            cursor: pointer;

        }

        .dropdown-link:hover,

        .dropdown-logout:hover {

            background: #f0f5fb;

            text-decoration: none;

        }

        .dropdown-link svg,

        .dropdown-logout svg {

            width: 19px;

            height: 19px;

            flex-shrink: 0;

        }

        .dropdown-divider {

            height: 1px;

            margin: 6px 0;

            background: #e8edf4;

        }

        .dropdown-logout {

            color: #d23b43;

        }

        /* PANEL NOTIFIKASI */

        .bell-pop {

            position: absolute;

            top: calc(100% + 10px);

            right: 0;

            z-index: 1000;

            width: 330px;

            max-width: calc(100vw - 30px);

            max-height: 400px;

            overflow-y: auto;

            padding: 14px;

            color: #17314f;

            background: white;

            border: 1px solid #dce5ef;

            border-radius: 14px;

            box-shadow: 0 12px 32px rgba(13,35,62,.18);

        }

        .bell-title {

            padding: 4px 2px 12px;

            margin-bottom: 10px;

            border-bottom: 1px solid #e8edf4;

            font-size: 15px;

            font-weight: 700;

        }

        .notification-empty {

            padding: 12px 2px;

            color: #75869b;

            font-size: 13px;

        }

        .bell-pop .attn {

            margin-bottom: 8px;

        }

        @media (max-width: 650px) {

            .brand {

                gap: 7px;

                padding: 8px;

                font-size: 15px;

            }

            .brand-bird {

                width: 35px;

                height: 35px;

            }

            .top-actions {

                gap: 7px;

            }

            .icon-button {

                width: 38px;

                height: 38px;

            }

        }

        /* HAMBURGER MENU DI SIDEBAR */

        .side-top {

            display: flex;

            align-items: center;

            gap: 8px;

            padding: 8px 12px 4px;

        }

        .hamburger-toggle {

            width: 36px;

            height: 36px;

            flex: 0 0 36px;

            display: flex;

            flex-direction: column;

            align-items: center;

            justify-content: center;

            gap: 5px;

            border: 1px solid rgba(255,255,255,.2);

            border-radius: 8px;

            background: transparent;

            cursor: pointer;

        }

        .hamburger-toggle:hover {

            background: rgba(255,255,255,.12);

        }

        .hamburger-toggle span,

        .hamburger-reopen span {

            display: block;

            width: 18px;

            height: 2px;

            border-radius: 2px;

            background: #fff;

        }

        .side-top .brand {

            flex: 1;

            min-width: 0;

            padding: 8px 0;

        }

        .hamburger-reopen {

            display: none;

            position: fixed;

            top: 98px;

            left: 12px;

            z-index: 1100;

            width: 40px;

            height: 40px;

            flex-direction: column;

            align-items: center;

            justify-content: center;

            gap: 5px;

            border: 1px solid rgba(255,255,255,.2);

            border-radius: 9px;

            background: #10243a;

            cursor: pointer;

            box-shadow: 0 3px 12px rgba(0,0,0,.15);

        }

        .shell.sidebar-collapsed {

            grid-template-columns: minmax(0, 1fr) !important;

        }

        .shell.sidebar-collapsed > .side {

            display: none !important;

        }

        .shell.sidebar-collapsed > .main {

            width: 100% !important;

            min-width: 0 !important;

            max-width: none !important;

            margin-left: 0 !important;

        }

        .shell.sidebar-collapsed \~ .hamburger-reopen {

            display: flex;

        }

        @media (max-width: 768px) {

            .shell {

                grid-template-columns: minmax(0, 1fr);

            }

            .shell > .side {

                position: fixed;

                top: 0;

                left: 0;

                width: 230px;

                max-width: 85vw;

                height: 100vh;

                z-index: 1050;

                overflow-y: auto;

                box-shadow: 8px 0 24px rgba(0,0,0,.18);

            }

            .shell.sidebar-collapsed > .side {

                display: none !important;

            }

            .shell.sidebar-collapsed \~ .hamburger-reopen {

                display: flex;

            }

        }

        /* PERAPIHAN SIDEBAR: OVERRIDE CSS LAMA */

        .shell {

            display: grid !important;

            grid-template-columns: 230px minmax(0, 1fr) !important;

            min-height: 100vh;

            align-items: stretch;

            transition: grid-template-columns .25s ease;

        }

        .shell > .side {

            position: sticky !important;

            top: 0;

            display: flex !important;

            flex-direction: column !important;

            align-items: stretch !important;

            width: 230px;

            min-width: 0;

            height: 100vh;

            min-height: 100vh;

            overflow-x: hidden;

            overflow-y: auto;

            padding: 0 !important;

            order: 0 !important;

            z-index: 20;

        }

        .side > .side-top {

            position: relative;

            display: flex !important;

            flex-direction: column !important;

            align-items: stretch !important;

            justify-content: flex-start !important;

            gap: 8px;

            flex: 0 0 auto;

            width: 100%;

            min-height: 112px;

            padding: 12px !important;

            order: 0 !important;

        }

        .side-top > .hamburger-toggle {

            position: static !important;

            align-self: flex-start;

            margin: 0;

        }

        .side-top > .brand {

            display: flex !important;

            position: static !important;

            align-items: center;

            justify-content: flex-start;

            gap: 8px;

            width: 100%;

            min-width: 0;

            margin: 0 !important;

            padding: 0 !important;

            font-size: 16px;

            line-height: 1.2;

            white-space: nowrap;

        }

        .side-top .brand-bird {

            display: block;

            width: 38px !important;

            height: 38px !important;

            flex: 0 0 38px;

            object-fit: contain;

        }

        .side-top .brand > span {

            display: block;

            min-width: 0;

            overflow: visible;

            white-space: nowrap;

        }

        .side > .nav {

            position: static !important;

            display: flex !important;

            flex: 1 1 auto !important;

            flex-direction: column !important;

            align-items: stretch !important;

            gap: 4px;

            width: 100%;

            min-width: 0;

            margin: 0 !important;

            padding: 0 12px !important;

            order: 1 !important;

        }

        .side > .nav > a {

            position: static !important;

            display: flex !important;

            align-items: center;

            width: 100%;

            min-height: 42px;

            margin: 0 !important;

            padding: 11px 10px;

            border-radius: 9px;

            box-sizing: border-box;

            white-space: normal;

            overflow-wrap: anywhere;

            line-height: 1.35;

        }

        .side > .nav > a.on {

            padding-left: 7px;

        }

        .side > .side-bottom {

            position: static !important;

            flex: 0 0 auto;

            width: 100%;

            margin-top: auto !important;

            padding: 14px 12px 20px !important;

            order: 2 !important;

        }

        .side > .side-bottom form,

        .side > .side-bottom .side-logout {

            width: 100%;

        }

        .shell > .main {

            width: 100%;

            min-width: 0;

            max-width: none;

            margin: 0 !important;

            order: 1;

        }

        .shell.sidebar-collapsed {

            grid-template-columns: minmax(0, 1fr) !important;

        }

        .shell.sidebar-collapsed > .side {

            display: none !important;

        }

        .shell.sidebar-collapsed > .main {

            width: 100% !important;

            min-width: 0 !important;

            max-width: none !important;

            margin: 0 !important;

        }

        .hamburger-reopen {

            top: 100px;

            left: 12px;

        }

        @media (max-width: 768px) {

            .shell {

                grid-template-columns: minmax(0, 1fr) !important;

            }

            .shell > .side {

                position: fixed !important;

                left: 0;

                top: 0;

                width: 230px;

                max-width: 85vw;

                height: 100vh;

                min-height: 100vh;

                box-shadow: 8px 0 24px rgba(0,0,0,.2);

            }

            .shell.sidebar-collapsed > .side {

                display: none !important;

            }

        }

        /* FINAL FIX: SIDEBAR RAPI DAN AREA KONTEN FULL WIDTH */

        .shell {

            width: 100% !important;

            max-width: none !important;

            min-width: 0 !important;

            grid-template-columns: 230px minmax(0, 1fr) !important;

            align-items: stretch !important;

        }

        .shell > .side {

            position: sticky !important;

            top: 0 !important;

            width: 230px !important;

            height: 100vh !important;

            min-height: 100vh !important;

            max-height: 100vh !important;

            padding: 0 !important;

            display: flex !important;

            flex-direction: column !important;

            overflow-x: hidden !important;

            overflow-y: auto !important;

        }

        /* Hamburger di sebelah kiri logo burung, dalam satu baris */

        .side > .side-top {

            width: 100% !important;

            min-height: 78px !important;

            height: auto !important;

            padding: 14px 12px !important;

            display: flex !important;

            flex-direction: row !important;

            align-items: center !important;

            justify-content: flex-start !important;

            gap: 9px !important;

            flex: 0 0 auto !important;

        }

        .side-top > .hamburger-toggle {

            position: static !important;

            display: flex !important;

            flex: 0 0 36px !important;

            width: 36px !important;

            height: 36px !important;

            margin: 0 !important;

            padding: 0 !important;

        }

        .side-top > .brand {

            display: flex !important;

            flex: 1 1 auto !important;

            width: auto !important;

            min-width: 0 !important;

            margin: 0 !important;

            padding: 0 !important;

            flex-direction: row !important;

            align-items: center !important;

            justify-content: flex-start !important;

            gap: 7px !important;

            font-size: 15px !important;

            line-height: 1.2 !important;

            white-space: nowrap !important;

        }

        .side-top > .brand .brand-bird {

            width: 35px !important;

            height: 35px !important;

            flex: 0 0 35px !important;

            object-fit: contain !important;

        }

        .side-top > .brand > span {

            display: block !important;

            min-width: 0 !important;

            overflow: visible !important;

            white-space: nowrap !important;

        }

        /* Navigasi tetap vertikal */

        .side > .nav {

            display: flex !important;

            flex: 1 1 auto !important;

            flex-direction: column !important;

            align-items: stretch !important;

            width: 100% !important;

            min-width: 0 !important;

            margin: 0 !important;

            padding: 4px 12px 12px !important;

            gap: 4px !important;

        }

        .side > .nav > a {

            display: flex !important;

            width: 100% !important;

            min-height: 42px !important;

            height: auto !important;

            margin: 0 !important;

            padding: 11px 10px !important;

            align-items: center !important;

            box-sizing: border-box !important;

            line-height: 1.35 !important;

        }

        .side > .nav > a.on {

            padding-left: 7px !important;

        }

        /* Logout selalu berada di bagian paling bawah sidebar */

        .side > .side-bottom {

            position: static !important;

            display: block !important;

            flex: 0 0 auto !important;

            width: 100% !important;

            margin-top: auto !important;

            padding: 14px 12px 20px !important;

            border-top: 1px solid rgba(255,255,255,.12) !important;

        }

        .side > .side-bottom form,

        .side > .side-bottom .side-logout {

            display: flex;

            width: 100% !important;

        }

        /* Konten selalu memakai seluruh ruang yang tersedia */

        .shell > .main {

            display: flex !important;

            flex-direction: column !important;

            width: 100% !important;

            min-width: 0 !important;

            max-width: none !important;

            margin: 0 !important;

            padding: 0 !important;

            justify-self: stretch !important;

            box-sizing: border-box !important;

        }

        .shell > .main > .top {

            width: 100% !important;

            max-width: none !important;

            flex: 0 0 auto !important;

            box-sizing: border-box !important;

        }

        .shell > .main > .page {

            display: block !important;

            width: 100% !important;

            max-width: none !important;

            min-width: 0 !important;

            margin: 0 !important;

            box-sizing: border-box !important;

        }

        .shell.sidebar-collapsed {

            display: grid !important;

            width: 100% !important;

            max-width: none !important;

            grid-template-columns: minmax(0, 1fr) !important;

        }

        .shell.sidebar-collapsed > .side {

            display: none !important;

        }

        .shell.sidebar-collapsed > .main {

            display: flex !important;

            width: 100% !important;

            min-width: 0 !important;

            max-width: none !important;

            margin: 0 !important;

            padding: 0 !important;

            grid-column: 1 / -1 !important;

        }

        .shell.sidebar-collapsed > .main > .top,

        .shell.sidebar-collapsed > .main > .page {

            width: 100% !important;

            max-width: none !important;

            margin-left: 0 !important;

            margin-right: 0 !important;

        }

        /* Hilangkan batas lebar pada panel/kolom konten halaman saat sidebar ditutup */

        .shell.sidebar-collapsed > .main .page > \*,

        .shell.sidebar-collapsed > .main .page .grid,

        .shell.sidebar-collapsed > .main .page .cards,

        .shell.sidebar-collapsed > .main .page .dashboard-grid,

        .shell.sidebar-collapsed > .main .page .content-grid {

            max-width: none !important;

        }

        .shell.sidebar-collapsed \~ .hamburger-reopen {

            display: flex !important;

            position: fixed !important;

            top: 98px !important;

            left: 12px !important;

            z-index: 1100 !important;

        }

        @media (max-width: 768px) {

            .shell {

                grid-template-columns: minmax(0, 1fr) !important;

            }

            .shell > .side {

                position: fixed !important;

                left: 0 !important;

                top: 0 !important;

                width: 230px !important;

                max-width: 85vw !important;

                height: 100vh !important;

                z-index: 1050 !important;

            }

            .shell.sidebar-collapsed > .side {

                display: none !important;

            }

            .shell.sidebar-collapsed > .main,

            .shell.sidebar-collapsed > .main > .top,

            .shell.sidebar-collapsed > .main > .page {

                width: 100% !important;

                max-width: none !important;

            }

        }

    

        /* SIDEBAR LAYOUT FIX */
        .shell {
            display: grid !important;
            grid-template-columns: 230px minmax(0, 1fr) !important;
            width: 100% !important;
            max-width: none !important;
            min-width: 0 !important;
            min-height: 100vh;
            align-items: stretch !important;
        }
        .shell > .side {
            display: flex !important;
            flex-direction: column !important;
            align-items: stretch !important;
            position: sticky !important;
            top: 0 !important;
            width: 230px !important;
            min-width: 230px !important;
            height: 100vh !important;
            min-height: 100vh !important;
            max-height: 100vh !important;
            padding: 0 !important;
            overflow-x: hidden !important;
            overflow-y: auto !important;
            box-sizing: border-box !important;
        }
        .shell > .side > .side-top {
            display: flex !important;
            flex-direction: row !important;
            align-items: center !important;
            justify-content: flex-start !important;
            gap: 8px !important;
            width: 100% !important;
            min-height: 76px !important;
            padding: 12px !important;
            flex: 0 0 auto !important;
            box-sizing: border-box !important;
        }
        .shell > .side > .side-top > .hamburger-toggle {
            display: flex !important;
            flex: 0 0 36px !important;
            width: 36px !important;
            height: 36px !important;
            position: static !important;
            align-items: center !important;
            justify-content: center !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .shell > .side > .side-top > .brand {
            display: flex !important;
            flex: 1 1 auto !important;
            flex-direction: row !important;
            align-items: center !important;
            justify-content: flex-start !important;
            gap: 6px !important;
            width: auto !important;
            min-width: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            font-size: 14px !important;
            line-height: 1.2 !important;
            white-space: nowrap !important;
            box-sizing: border-box !important;
        }
        .shell > .side > .side-top > .brand .brand-bird {
            display: block !important;
            width: 34px !important;
            height: 34px !important;
            flex: 0 0 34px !important;
            object-fit: contain !important;
        }
        .shell > .side > .side-top > .brand > span {
            display: block !important;
            min-width: 0 !important;
            font-size: 14px !important;
            white-space: nowrap !important;
        }
        .shell > .side > .nav {
            display: flex !important;
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 4px !important;
            flex: 1 1 auto !important;
            width: 100% !important;
            min-width: 0 !important;
            margin: 0 !important;
            padding: 4px 12px 12px !important;
            box-sizing: border-box !important;
        }
        .shell > .side > .nav > a {
            display: flex !important;
            align-items: center !important;
            justify-content: flex-start !important;
            width: 100% !important;
            min-height: 42px !important;
            height: auto !important;
            margin: 0 !important;
            padding: 11px 10px !important;
            line-height: 1.35 !important;
            box-sizing: border-box !important;
        }
        .shell > .side > .nav > a.on { padding-left: 7px !important; }
        .shell > .side > .side-bottom {
            display: block !important;
            position: static !important;
            flex: 0 0 auto !important;
            width: 100% !important;
            margin-top: auto !important;
            padding: 14px 12px 20px !important;
            border-top: 1px solid rgba(255,255,255,.12) !important;
            box-sizing: border-box !important;
        }
        .shell > .side > .side-bottom form { width: 100% !important; margin: 0 !important; }
        .shell > .side > .side-bottom .side-logout {
            display: flex !important;
            align-items: center !important;
            width: 100% !important;
            min-height: 48px !important;
            box-sizing: border-box !important;
        }
        .shell > .main {
            display: block !important;
            width: 100% !important;
            min-width: 0 !important;
            max-width: none !important;
            margin: 0 !important;
            padding: 0 !important;
            box-sizing: border-box !important;
        }
        .shell > .main > .top,
        .shell > .main > .page {
            width: 100% !important;
            min-width: 0 !important;
            max-width: none !important;
            box-sizing: border-box !important;
        }
        .shell.sidebar-collapsed { grid-template-columns: minmax(0, 1fr) !important; width: 100% !important; }
        .shell.sidebar-collapsed > .side { display: none !important; }
        .shell.sidebar-collapsed > .main {
            grid-column: 1 / -1 !important;
            width: 100% !important;
            min-width: 0 !important;
            max-width: none !important;
            margin: 0 !important;
        }
        .shell.sidebar-collapsed > .main > .top,
        .shell.sidebar-collapsed > .main > .page {
            width: 100% !important;
            min-width: 0 !important;
            max-width: none !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
        }
        .shell.sidebar-collapsed ~ .hamburger-reopen { display: flex !important; }
        @media (max-width: 768px) {
            .shell { grid-template-columns: minmax(0, 1fr) !important; }
            .shell > .side {
                position: fixed !important; top: 0 !important; left: 0 !important;
                width: 230px !important; min-width: 0 !important; max-width: 85vw !important;
                height: 100vh !important; z-index: 1050 !important;
                box-shadow: 8px 0 24px rgba(0,0,0,.18);
            }
            .shell.sidebar-collapsed > .side { display: none !important; }
            .shell.sidebar-collapsed > .main,
            .shell.sidebar-collapsed > .main > .top,
            .shell.sidebar-collapsed > .main > .page { width: 100% !important; max-width: none !important; }
        }



        /* FIX HAMBURGER MENU UNTUK SEMUA HALAMAN */
        #appShell {
            transition: grid-template-columns .25s ease !important;
        }
        #appShell.sidebar-collapsed {
            display: grid !important;
            grid-template-columns: minmax(0, 1fr) !important;
            width: 100% !important;
            max-width: none !important;
        }
        #appShell.sidebar-collapsed > .side {
            display: none !important;
        }
        #appShell.sidebar-collapsed > .main {
            grid-column: 1 / -1 !important;
            width: 100% !important;
            min-width: 0 !important;
            max-width: none !important;
            margin: 0 !important;
        }
        #sidebarReopen {
            display: none;
            position: fixed !important;
            top: 16px !important;
            left: 16px !important;
            z-index: 9999 !important;
            width: 42px !important;
            height: 42px !important;
            padding: 0 !important;
            border: 1px solid #dce5ef !important;
            border-radius: 10px !important;
            background: #102d55 !important;
            box-shadow: 0 4px 12px rgba(0,0,0,.16) !important;
            cursor: pointer !important;
        }
        #sidebarReopen span {
            background: #fff !important;
        }
        @media (max-width: 768px) {
            #appShell { grid-template-columns: minmax(0, 1fr) !important; }
            #appShell > .side {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                z-index: 1050 !important;
                width: 230px !important;
                max-width: 85vw !important;
                height: 100vh !important;
            }
            #appShell.sidebar-collapsed > .side { display: none !important; }
            #appShell.sidebar-collapsed > .main { width: 100% !important; }
        }

        /* SIDEBAR DITUTUP: KONTEN MEMENUHI LAYAR */
            #appShell.sidebar-collapsed {
                display: block !important;
                width: 100% !important;
                max-width: 100% !important;
        }

            #appShell.sidebar-collapsed .side {
                display: none !important;
        }

            #appShell.sidebar-collapsed .main {
                width: 100% !important;
                max-width: 100% !important;
                min-width: 0 !important;
                margin-left: 0 !important;
        }

        /* TOMBOL BUKA SIDEBAR TIDAK MENUTUPI JUDUL */
            #sidebarReopen {
                position: fixed !important;
                top: 14px !important;
                left: 14px !important;
                z-index: 1000 !important;
                width: 42px;
                height: 42px;
                display: none;
                align-items: center;
                justify-content: center;
                border-radius: 10px;
                background: #0f2238;
                color: #fff;
                border: 1px solid #334a63;
                box-shadow: 0 3px 12px #0002;
                cursor: pointer;
        }

            #appShell.sidebar-collapsed ~ #sidebarReopen {
                display: flex !important;
        }

        /* Beri ruang untuk hamburger di baris judul */
            #appShell.sidebar-collapsed .top {
                padding-left: 68px !important;
        }

        @media (max-width: 600px) {
            #appShell.sidebar-collapsed .top {
            padding-left: 64px !important;
    }
}

</style>

</head>

<body>

@php

    $uid = auth()->id();

    $currentUser = auth()->user();

    $navScheduled = \App\Models\Post::where('user_id', $uid)

        ->where('state', 'scheduled')

        ->count();

    $navFailed = \App\Models\Post::where('user_id', $uid)

        ->where('state', 'done')

        ->whereHas('targets', fn ($q) => $q->where('status', 'failed'))

        ->count();

    $notifs = $currentUser->notifications()->limit(8)->get();

    $unread = $currentUser->unreadNotifications()->count();

@endphp

<div class="shell" id="appShell">

    {{-- SIDEBAR --}}

    <aside class="side">

        <div class="side-top">

            <button type="button"

                    class="hamburger-toggle"

                    id="sidebarHamburger"

                    aria-label="Tutup sidebar"

                    aria-expanded="true"

                    title="Tutup sidebar">

                <span></span>

                <span></span>

                <span></span>

            </button>

            <div class="brand">

            {{-- LOGO BURUNG SVG --}}

            <svg class="brand-bird"

                 viewBox="0 0 160 160"

                 xmlns="http://www.w3.org/2000/svg"

                 role="img"

                 aria-label="Logo burung Posting Sosmed">

                <g fill="#1675d1">

                    {{-- Sayap utama --}}

                    <path d="M73 94

                        C51 79 29 48 32 16

                        C43 39 59 55 79 68

                        C68 47 67 31 72 20

                        C82 42 96 59 105 76

                        C111 88 103 100 94 106 Z"/>

                    {{-- Lapisan bulu sayap --}}

                    <path d="M75 87 C54 72 41 54 35 36

                        C51 52 66 64 83 74 Z"/>

                    <path d="M80 92 C62 81 48 69 42 56

                        C58 67 73 76 89 82 Z"/>

                    <path d="M86 98 C70 88 59 80 53 69

                        C67 77 81 84 95 87 Z"/>

                    <path d="M92 103 C78 97 68 91 62 83

                        C75 90 86 94 99 95 Z"/>

                    {{-- Sayap belakang --}}

                    <path d="M91 97

                        C103 77 103 54 112 39

                        C112 57 120 69 120 81

                        C117 94 107 105 96 111 Z"/>

                    <path d="M101 91 C108 76 109 63 115 52

                        C115 67 118 78 114 89 Z"/>

                    <path d="M95 100 C102 87 104 76 110 67

                        C109 81 106 92 100 102 Z"/>

                    {{-- Badan, kepala, dan paruh --}}

                    <path d="M73 107

                        C88 96 101 83 111 70

                        C120 56 134 54 144 64

                        L157 72 L141 71

                        C135 81 130 94 121 105

                        C109 120 90 128 68 122

                        L45 114 L70 115 Z"/>

                    {{-- Ekor --}}

                    <path d="M59 104 L8 119 L48 108

                        L24 130 L65 112 Z"/>

                    {{-- Bulu bawah --}}

                    <path d="M80 117 L39 143 L72 132

                        L57 147 L91 124 Z"/>

                </g>

            </svg>

            <span>Posting Sosmed</span>

        </div>
        </div>

        

        {{-- MENU UTAMA --}}

        <nav class="nav" aria-label="Menu utama">

            <a href="{{ route('dashboard') }}"

                @class(['on' => request()->routeIs('dashboard')])>

                Dashboard

            </a>

            <a href="{{ route('accounts.index') }}"

                @class(['on' => request()->routeIs('accounts.*')])>

                Akun Sosmed

            </a>

            <a href="{{ route('posts.create') }}"

                @class(['on' => request()->routeIs('posts.*')])>

                Buat posting

            </a>

            <a href="{{ route('schedule.index') }}"

                @class(['on' => request()->routeIs('schedule.*')])>

                Terjadwal

                @if($navScheduled)

                    <span class="cnt">{{ $navScheduled }}</span>

                @endif

            </a>

            <a href="{{ route('history.index') }}"

                @class(['on' => request()->routeIs('history.*')])>

                Riwayat

                @if($navFailed)

                    <span class="cnt bad">{{ $navFailed }}</span>

                @endif

            </a>

        </nav>

        {{-- LOGOUT DI BAWAH SIDEBAR --}}

        <div class="side-bottom">

            <form method="POST" action="{{ route('logout') }}">

                @csrf

                <button type="submit" class="side-logout">

                    <svg viewBox="0 0 24 24" fill="none"

                        stroke="currentColor" stroke-width="1.8"

                        stroke-linecap="round" stroke-linejoin="round"

                        aria-hidden="true">

                        <path d="M10 17l5-5-5-5"/>

                        <path d="M15 12H3"/>

                        <path d="M12 3h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6"/>

                    </svg>

                    <span>Logout</span>

                </button>

            </form>

        </div>

    </aside>

    {{-- AREA KONTEN --}}

    <div class="main">

        <header class="top">

            <h1>@yield('title')</h1>

            <div class="top-actions">

                {{-- IKON LONCENG --}}

                <details class="dropdown bell">

                    <summary class="icon-button"

                        aria-label="Notifikasi"

                        title="Notifikasi">

                        <svg viewBox="0 0 24 24" fill="none"

                            stroke="currentColor" stroke-width="1.8"

                            stroke-linecap="round" stroke-linejoin="round"

                            aria-hidden="true">

                            <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/>

                            <path d="M10 21h4"/>

                        </svg>

                        @if($unread)

                            <span class="cnt">{{ $unread }}</span>

                        @endif

                    </summary>

                    <div class="bell-pop">

                        <div class="bell-title">Notifikasi</div>

                        @forelse($notifs as $n)

                            <div class="attn {{ ($n->data['type'] ?? '') === 'ok' ? 'ok' : 'bad' }}">

                                <span>

                                    {{ $n->data['text'] ?? 'Ada pembaruan notifikasi.' }}

                                    <br>

                                    <small class="muted">

                                        {{ $n->created_at->diffForHumans() }}

                                    </small>

                                </span>

                            </div>

                        @empty

                            <p class="notification-empty">

                                Belum ada notifikasi.

                            </p>

                        @endforelse

                        @if($unread)

                            <form method="POST" action="{{ route('notifications.read') }}">

                                @csrf

                                <button class="btn sm" type="submit">

                                    Tandai sudah dibaca

                                </button>

                            </form>

                        @endif

                    </div>

                </details>

                {{-- IKON PROFIL --}}

                <details class="dropdown profile-dropdown">

                    <summary class="icon-button"

                        aria-label="Menu profil"

                        title="Profil">

                        <svg viewBox="0 0 24 24" fill="none"

                            stroke="currentColor" stroke-width="1.8"

                            stroke-linecap="round" stroke-linejoin="round"

                            aria-hidden="true">

                            <circle cx="12" cy="8" r="4"/>

                            <path d="M4 21v-2a8 8 0 0 1 16 0v2"/>

                        </svg>

                    </summary>

                    <div class="dropdown-pop">

                        <div class="dropdown-heading">

                            <div class="avatar-circle">

                                <svg viewBox="0 0 24 24" fill="none"

                                    stroke="currentColor" stroke-width="1.8"

                                    stroke-linecap="round" stroke-linejoin="round">

                                    <circle cx="12" cy="8" r="4"/>

                                    <path d="M4 21v-2a8 8 0 0 1 16 0v2"/>

                                </svg>

                            </div>

                            <div class="user-info">

                                <strong>{{ $currentUser->name }}</strong>

                                <small>{{ $currentUser->email }}</small>

                            </div>

                        </div>

                        @if(\Illuminate\Support\Facades\Route::has('profile.edit'))

                            <a href="{{ route('profile.edit') }}" class="dropdown-link">

                                <svg viewBox="0 0 24 24" fill="none"

                                    stroke="currentColor" stroke-width="1.8"

                                    stroke-linecap="round" stroke-linejoin="round">

                                    <circle cx="12" cy="8" r="4"/>

                                    <path d="M4 21v-2a8 8 0 0 1 16 0v2"/>

                                </svg>

                                Profil

                            </a>

                        @endif

                        @if(\Illuminate\Support\Facades\Route::has('settings.index'))

                            <a href="{{ route('settings.index') }}" class="dropdown-link">

                                <svg viewBox="0 0 24 24" fill="none"

                                    stroke="currentColor" stroke-width="1.8"

                                    stroke-linecap="round" stroke-linejoin="round">

                                    <circle cx="12" cy="12" r="3"/>

                                    <path d="M19.4 15a8 8 0 0 0 0-6l1.4-1.1-1.5-2.6-1.7.7a8 8 0 0 0-5.2-1.5L12 2.7H9l-.4 1.8a8 8 0 0 0-2.3 1.3l-1.7-.7-1.5 2.6L4.5 9a8 8 0 0 0 0 6l-1.4 1.1 1.5 2.6 1.7-.7a8 8 0 0 0 5.2 1.5l.4 1.8h3l.4-1.8a8 8 0 0 0 2.3-1.3l1.7.7 1.5-2.6z"/>

                                </svg>

                                Pengaturan

                            </a>

                        @endif

                        <div class="dropdown-divider"></div>

                        <form method="POST" action="{{ route('logout') }}">

                            @csrf

                            <button type="submit" class="dropdown-logout">

                                <svg viewBox="0 0 24 24" fill="none"

                                    stroke="currentColor" stroke-width="1.8"

                                    stroke-linecap="round" stroke-linejoin="round">

                                    <path d="M10 17l5-5-5-5"/>

                                    <path d="M15 12H3"/>

                                    <path d="M12 3h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6"/>

                                </svg>

                                Logout

                            </button>

                        </form>

                    </div>

                </details>

            </div>

        </header>

        {{-- PESAN SUKSES --}}

        <main class="page">

            @if(session('ok'))

                <div class="flash ok" role="status">

                    {{ session('ok') }}

                </div>

            @endif

            {{-- PESAN ERROR --}}

            @if($errors->any())

                <div class="flash bad" role="alert">

                    <ul>

                        @foreach($errors->all() as $e)

                            <li>{{ $e }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif

            {{-- ISI DASHBOARD DAN HALAMAN LAIN --}}

            @yield('content')

        </main>

    </div>

</div>

<button type="button"

        class="hamburger-reopen"

        id="sidebarReopen"

        aria-label="Buka sidebar"

        aria-expanded="false"

        title="Buka sidebar">

    <span></span>

    <span></span>

    <span></span>

</button>

<script>
(function () {
    'use strict';

    function initPostingSosmedSidebar() {
        const shell = document.getElementById('appShell') || document.querySelector('.shell');
        const closeButton = document.getElementById('sidebarHamburger');
        const openButton = document.getElementById('sidebarReopen');
        const storageKey = 'postingSosmedSidebarCollapsed';

        if (!shell || !closeButton || !openButton) return;

        function setCollapsed(collapsed, saveState) {
            shell.classList.toggle('sidebar-collapsed', collapsed);
            openButton.style.display = collapsed ? 'flex' : 'none';
            closeButton.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
            closeButton.setAttribute('aria-label', collapsed ? 'Tutup sidebar' : 'Tutup sidebar');
            closeButton.setAttribute('title', 'Tutup sidebar');
            openButton.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
            openButton.setAttribute('aria-label', collapsed ? 'Buka sidebar' : 'Buka sidebar');
            openButton.setAttribute('title', 'Buka sidebar');

            if (saveState) {
                try { localStorage.setItem(storageKey, collapsed ? '1' : '0'); } catch (error) {}
            }
        }

        let initiallyCollapsed = false;
        try { initiallyCollapsed = localStorage.getItem(storageKey) === '1'; } catch (error) {}
        setCollapsed(initiallyCollapsed, false);

        // Gunakan onclick agar tidak menumpuk listener lama ketika halaman dipanggil ulang.
        closeButton.onclick = function (event) {
            event.preventDefault();
            event.stopPropagation();
            setCollapsed(true, true);
        };

        openButton.onclick = function (event) {
            event.preventDefault();
            event.stopPropagation();
            setCollapsed(false, true);
        };
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPostingSosmedSidebar, { once: true });
    } else {
        initPostingSosmedSidebar();
    }

    document.addEventListener('click', function (event) {
        document.querySelectorAll('.dropdown[open]').forEach(function (dropdown) {
            if (!dropdown.contains(event.target)) dropdown.removeAttribute('open');
        });
    });
})();
</script>

@stack('scripts')

</body>

</html>
