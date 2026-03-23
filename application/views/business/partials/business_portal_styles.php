<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
    /* Portail marchand B2B — aligné menu_nav_bar (#270345) */
    :root {
        --ripa-bar: #270345;
        --ripa-bar-hover: #3d0f5c;
        --ripa-muted: #5c4a6e;
        --ripa-bg: #f5f2fa;
    }
    body.ripa-portail-page {
        min-height: 100vh;
        background: var(--ripa-bg);
        margin: 0;
    }
    .ripa-portail-topbar {
        background-color: var(--ripa-bar);
        min-height: 55px;
        padding: 6px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        box-shadow: 0 2px 12px rgba(39, 3, 69, 0.35);
    }
    .ripa-portail-topbar .logo-wrap {
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .ripa-portail-topbar img.logo-white {
        width: 150px;
        height: 45px;
        object-fit: contain;
        border-radius: 10px;
    }
    .ripa-portail-topbar .title-block {
        color: #fff;
        font-size: 14px;
        line-height: 1.35;
    }
    .ripa-portail-topbar .title-block strong {
        font-size: 16px;
        letter-spacing: 0.02em;
    }
    .ripa-portail-topbar a.topbar-action {
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        padding: 8px 14px;
        border: 1px solid rgba(255,255,255,0.45);
        border-radius: 8px;
        transition: background 0.2s, border-color 0.2s;
    }
    .ripa-portail-topbar a.topbar-action:hover {
        background: rgba(255,255,255,0.12);
        border-color: rgba(255,255,255,0.7);
        color: #fff;
    }
    .ripa-portail-card {
        border: none;
        border-radius: 16px;
        border-top: 4px solid var(--ripa-bar);
        box-shadow: 0 8px 32px rgba(39, 3, 69, 0.08);
        overflow: hidden;
    }
    .ripa-portail-card .card-body-inner {
        padding: 2rem 2rem 2.25rem;
    }
    .ripa-portail-card h1 {
        color: var(--ripa-bar);
        font-weight: 700;
        font-size: 1.5rem;
    }
    .ripa-portail-card .lead-muted {
        color: var(--ripa-muted);
        font-size: 0.95rem;
    }
    .ripa-portail-card label {
        color: var(--ripa-bar);
        font-weight: 600;
        font-size: 0.875rem;
        margin-bottom: 0.35rem;
    }
    .ripa-portail-card .form-control {
        border-radius: 10px;
        border: 1px solid rgba(39, 3, 69, 0.18);
        padding: 0.65rem 0.9rem;
    }
    .ripa-portail-card .form-control:focus {
        border-color: var(--ripa-bar);
        box-shadow: 0 0 0 0.2rem rgba(39, 3, 69, 0.15);
    }
    .ripa-portail-card .form-control::placeholder {
        color: #a89bb8;
    }
    .ripa-portail-card .input-icon-wrap {
        position: relative;
    }
    .ripa-portail-card .input-icon-wrap .fa {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--ripa-muted);
        opacity: 0.85;
        pointer-events: none;
        font-size: 15px;
    }
    .ripa-portail-card .input-icon-wrap .form-control {
        padding-left: 2.5rem;
    }
    .ripa-portail-card .input-icon-wrap .form-control[type="password"] {
        padding-right: 2.5rem;
    }
    .btn-ripa-primary {
        background-color: var(--ripa-bar);
        border-color: var(--ripa-bar);
        color: #fff;
        font-weight: 600;
        padding: 0.65rem 1.5rem;
        border-radius: 10px;
        transition: background 0.2s, border-color 0.2s;
    }
    .btn-ripa-primary:hover {
        background-color: var(--ripa-bar-hover);
        border-color: var(--ripa-bar-hover);
        color: #fff;
    }
    .ripa-portail-card .alert {
        border-radius: 10px;
    }
    .ripa-portail-card .alert-success {
        background: #e8f5e9;
        border-color: #c8e6c9;
        color: #1b5e20;
    }
    .ripa-portail-card .alert-danger {
        background: #ffebee;
        border-color: #ffcdd2;
        color: #b71c1c;
    }
    .ripa-portail-footer-note {
        text-align: center;
        color: var(--ripa-muted);
        font-size: 0.8rem;
        padding: 1.5rem 1rem 2rem;
    }
</style>
