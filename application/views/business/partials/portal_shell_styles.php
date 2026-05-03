<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
    :root {
        --ripa-bar: #270345;
        --ripa-bar-hover: #3d0f5c;
        --ripa-muted: #5c4a6e;
        --ripa-bg: #f0ecf5;
        --ripa-card-shadow: 0 4px 24px rgba(39, 3, 69, 0.08);
        --ripa-radius: 14px;
    }
    body.ripa-portal-app {
        min-height: 100vh;
        background: var(--ripa-bg);
        margin: 0;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }
    /* <header> et non <nav> : évite les règles globales Materialize (nav { height:56px; line-height:56px; } etc.) */
    header.ripa-portal-nav {
        background: linear-gradient(135deg, var(--ripa-bar) 0%, #1a0229 100%);
        box-shadow: 0 4px 20px rgba(39, 3, 69, 0.35);
        padding: 0 1rem;
        position: sticky;
        top: 0;
        z-index: 1030;
        display: block;
    }
    header.ripa-portal-nav .nav-inner {
        max-width: 1320px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.5rem;
        min-height: 58px;
    }
    header.ripa-portal-nav .brand-block {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        color: #fff !important;
    }
    header.ripa-portal-nav .brand-block img {
        height: 36px;
        width: auto;
        object-fit: contain;
        border-radius: 8px;
    }
    header.ripa-portal-nav .brand-text strong {
        font-size: 1rem;
        letter-spacing: 0.02em;
    }
    header.ripa-portal-nav .brand-text span {
        font-size: 0.78rem;
        opacity: 0.95;
        display: block;
        line-height: 1.25;
        color: rgba(255, 255, 255, 0.92);
    }
    header.ripa-portal-nav .nav-links {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 4px;
    }
    header.ripa-portal-nav .nav-links a {
        color: rgba(255,255,255,0.88);
        text-decoration: none;
        padding: 0.45rem 0.75rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        transition: background 0.2s, color 0.2s;
        line-height: 1.35;
        display: inline-flex;
        align-items: center;
        box-sizing: border-box;
    }
    header.ripa-portal-nav .nav-links a:hover {
        color: #fff;
        background: rgba(255,255,255,0.1);
    }
    header.ripa-portal-nav .nav-links a.is-active {
        color: #fff;
        background: rgba(255,255,255,0.18);
        font-weight: 600;
    }
    header.ripa-portal-nav .btn-logout {
        border: 1px solid rgba(255,255,255,0.45);
        color: #fff !important;
        padding: 0.4rem 0.9rem !important;
        border-radius: 8px;
        font-size: 0.8rem !important;
        margin-left: 0.25rem;
        line-height: 1.35 !important;
        display: inline-flex !important;
        align-items: center;
        box-sizing: border-box;
        background: transparent !important;
        text-transform: none;
        letter-spacing: normal;
        box-shadow: none !important;
    }
    header.ripa-portal-nav .btn-logout:hover {
        background: rgba(255,255,255,0.12) !important;
    }
    .ripa-portal-main {
        max-width: 1320px;
        margin: 0 auto;
        padding: 1.25rem 1rem 2.5rem;
    }
    .ripa-dash-hero {
        background: linear-gradient(125deg, var(--ripa-bar) 0%, #4a1a6e 55%, #6b3d8f 100%);
        color: #fff;
        border-radius: var(--ripa-radius);
        padding: 1.5rem 1.75rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--ripa-card-shadow);
    }
    .ripa-dash-hero h1 {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0 0 0.35rem;
    }
    .ripa-dash-hero p {
        margin: 0;
        opacity: 0.92;
        font-size: 0.95rem;
    }
    .ripa-kpi-card {
        background: #fff;
        border-radius: var(--ripa-radius);
        padding: 1.15rem 1.25rem;
        box-shadow: var(--ripa-card-shadow);
        border: 1px solid rgba(39, 3, 69, 0.06);
        height: 100%;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .ripa-kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 28px rgba(39, 3, 69, 0.12);
    }
    .ripa-kpi-card .kpi-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        margin-bottom: 0.65rem;
    }
    .ripa-kpi-card .kpi-label {
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--ripa-muted);
        font-weight: 600;
        margin-bottom: 0.2rem;
    }
    .ripa-kpi-card .kpi-value {
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--ripa-bar);
        line-height: 1.2;
    }
    .ripa-kpi-card .kpi-sub {
        font-size: 0.8rem;
        color: #888;
        margin-top: 0.35rem;
    }
    .ripa-panel {
        background: #fff;
        border-radius: var(--ripa-radius);
        box-shadow: var(--ripa-card-shadow);
        border: 1px solid rgba(39, 3, 69, 0.06);
        overflow: hidden;
        margin-bottom: 1.25rem;
    }
    .ripa-panel .panel-head {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid rgba(39, 3, 69, 0.08);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    .ripa-panel .panel-head h2 {
        margin: 0;
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--ripa-bar);
    }
    .ripa-panel .panel-body {
        padding: 1rem 1.25rem;
    }
    .ripa-badge-statut {
        display: inline-block;
        padding: 0.2rem 0.55rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }
    .ripa-badge-actif { background: #e8f5e9; color: #1b5e20; }
    .ripa-badge-attente { background: #fff3e0; color: #e65100; }
    .ripa-badge-refuse { background: #ffebee; color: #b71c1c; }
    .ripa-badge-suspendu { background: #fce4ec; color: #880e4f; }
    .ripa-badge-default { background: #ede7f6; color: var(--ripa-bar); }
    .ripa-list-item {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        padding: 0.65rem 0;
        border-bottom: 1px solid rgba(0,0,0,0.06);
    }
    .ripa-list-item:last-child { border-bottom: none; }
    .ripa-tx-credit { color: #2e7d32; font-weight: 600; }
    .ripa-tx-debit { color: #c62828; font-weight: 600; }
    .ripa-chart-wrap {
        position: relative;
        height: 280px;
        margin-top: 0.5rem;
    }
    @media (max-width: 576px) {
        .ripa-chart-wrap { height: 220px; }
    }
</style>
