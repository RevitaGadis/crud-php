<?php
include 'config/app.php';

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="color-scheme" content="light dark" />

    <title><?= $title; ?> | CRUD PHP</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.1.0/dist/css/adminlte.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.8/css/dataTables.bootstrap5.css" />

    <style>
      .app-header { box-shadow: 0 1px 3px rgba(0,0,0,.08); }
      .sidebar-user-panel { padding: .9rem 1rem; border-bottom: 1px solid rgba(255,255,255,.1); }
      .sidebar-user-panel img { width: 40px; height: 40px; object-fit: cover; }
      .sidebar-menu .nav-header { padding: .8rem 1rem .3rem; font-size: .8rem; letter-spacing: .03em; color: rgba(255,255,255,.55); }
    </style>
  </head>
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">

      <!--begin::Header-->
      <nav class="app-header navbar navbar-expand bg-body">
        <div class="container-fluid">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                <i class="bi bi-list fs-5"></i>
              </a>
            </li>
          </ul>
        </div>
      </nav>
      <!--end::Header-->

      <!--begin::Sidebar-->
      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <!--begin::Brand-->
        <div class="sidebar-brand">
          <a href="index.php" class="brand-link">
            <span class="brand-text">CRUD PHP</span>
          </a>
        </div>
        <!--end::Brand-->

        <!--begin::User panel-->
        <div class="sidebar-user-panel d-flex align-items-center">
          <img
            src="https://cdn.jsdelivr.net/npm/admin-lte@4.1.0/dist/assets/img/avatar.png"
            class="rounded-circle shadow me-2"
            alt="User"
          />
          <span class="text-white fw-semibold"><?= $_SESSION['nama']; ?></span>
        </div>
        <!--end::User panel-->

        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" aria-label="Main navigation" data-accordion="false" id="navigation">

              <li class="nav-header">DAFTAR MENU</li>

              <?php if ($_SESSION['level'] == 1 || $_SESSION['level'] == 2) : ?>
              <li class="nav-item">
                <a href="index.php" class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? ' active' : ''; ?>">
                  <i class="nav-icon bi bi-list-ul"></i>
                  <p>Data Barang</p>
                </a>
              </li>
              <?php endif; ?>

              <?php if ($_SESSION['level'] == 1 || $_SESSION['level'] == 3) : ?>
              <li class="nav-item">
                <a href="mahasiswa.php" class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'mahasiswa.php' ? ' active' : ''; ?>">
                  <i class="nav-icon bi bi-mortarboard-fill"></i>
                  <p>Data Mahasiswa</p>
                </a>
              </li>
              <?php endif; ?>

              <li class="nav-item">
                <a href="crud-modal.php" class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'crud-modal.php' ? ' active' : ''; ?>">
                  <i class="nav-icon bi bi-person-badge-fill"></i>
                  <p>Data Akun</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="logout.php" class="nav-link">
                  <i class="nav-icon bi bi-box-arrow-right"></i>
                  <p>Keluar</p>
                </a>
              </li>
            </ul>
          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
      </aside>
      <!--end::Sidebar-->