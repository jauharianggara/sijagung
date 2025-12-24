<?php

use yii\helpers\Html;
use hscstudio\mimin\components\Mimin;
use backend\components\NavWidget;
use yii\helpers\Url;

$menuItems = [
    [
        'label' => '<span class="hide-menu">Dashboard</span>',
        'encode' => false,
        'icon' => 'mdi mdi-gauge',
        'class' => 'waves-effect waves-dark',
        'url' => ['/site/index']
    ],
    [
        'label' => '<span class="hide-menu">Data</span>',
        'encode' => false,
        'icon' => 'mdi mdi-pencil',
        'class' => 'has-arrow waves-effect waves-dark',
        'items' => [
            ['label' => 'Pembelian', 'icon' => 'mdi mdi-table', 'class' => 'waves-effect waves-dark', 'url' => ['/input-data-pembelian/index']],
            ['label' => 'Stok', 'icon' => 'mdi mdi-table', 'class' => 'waves-effect waves-dark', 'url' => ['/input-data-stok/index']],
            ['label' => 'Proyeksi', 'icon' => 'mdi mdi-table', 'class' => 'waves-effect waves-dark', 'url' => ['/data-proyeksi/index']],
        ]
    ],
    [
        'label' => '<span class="hide-menu">Laporan</span>',
        'encode' => false,
        'icon' => 'mdi mdi-file',
        'class' => 'has-arrow waves-effect waves-dark',
        'items' => [
            ['label' => 'Pembelian', 'icon' => 'mdi mdi-table', 'class' => 'waves-effect waves-dark', 'url' => ['/laporan-pembelian/index']],
            ['label' => 'Stok', 'icon' => 'mdi mdi-table', 'class' => 'waves-effect waves-dark', 'url' => ['/laporan-stok/index']],
            ['label' => 'Proyeksi', 'icon' => 'mdi mdi-table', 'class' => 'waves-effect waves-dark', 'url' => ['/laporan-proyeksi/index']],
            ['label' => 'Rekap Proyeksi', 'icon' => 'mdi mdi-table', 'class' => 'waves-effect waves-dark', 'url' => ['/laporan-rekap-proyeksi/index']],
            ['label' => 'Laporan Pemasok', 'icon' => 'mdi mdi-file', 'class' => 'waves-effect waves-dark', 'url' => ['/profil-supplier/laporan-bulanan']],
            ['label' => 'Penggunaan', 'icon' => 'mdi mdi-file', 'class' => 'waves-effect waves-dark', 'url' => ['/data-penggunaan/index']],
            ['label' => 'Stock To Use Ratio', 'icon' => 'mdi mdi-file', 'class' => 'waves-effect waves-dark', 'url' => ['/data-rasio-penggunaan/index']],
            ['label' => 'Perubahan Infrastruktur', 'icon' => 'mdi mdi-file', 'class' => 'waves-effect waves-dark', 'url' => ['/laporan-perubahan-infrastruktur/index']],

        ]
    ],
    [
        'label' => '<span class="hide-menu">Peta</span>',
        'encode' => false,
        'icon' => 'mdi mdi-map',
        'class' => 'has-arrow waves-effect waves-dark',
        'items' => [
            ['label' => 'Peta Sebaran', 'icon' => 'mdi mdi-map', 'class' => 'waves-effect waves-dark', 'url' => ['/profil-plant/map']],
        ]
    ],

    [
        'label' => '<span class="hide-menu">Berita</span>',
        'encode' => false,
        'icon' => 'mdi mdi-bullhorn',
        'class' => 'waves-effect waves-dark',
        'url' => ['/berita/list']
    ],
    [
        'label' => '<span class="hide-menu">Ubah Password</span>',
        'encode' => false,
        'icon' => 'mdi mdi-lock',
        'url' => ['/site/ubah-password']
    ],

    [
        'label' => '<span class="hide-menu">Setting</span>',
        'encode' => false,
        'icon' => 'mdi mdi-settings',
        'class' => 'has-arrow waves-effect waves-dark',
        'items' => [
            ['label' => 'Kunci Entry', 'icon' => 'mdi mdi-lock', 'class' => 'waves-effect waves-dark', 'url' => ['/setting/index']],
            ['label' => 'User', 'icon' => 'mdi mdi-account', 'class' => 'waves-effect waves-dark', 'url' => ['/mimin/user']],
            ['label' => 'Admin Provinsi', 'icon' => 'mdi mdi-account', 'class' => 'waves-effect waves-dark', 'url' => ['/admin-provinsi/index']],
            ['label' => 'Admin Kota/Kabupaten', 'icon' => 'mdi mdi-account', 'class' => 'waves-effect waves-dark', 'url' => ['/admin-kota-kabupaten/index']],
            ['label' => 'Pulau', 'icon' => 'mdi mdi-database', 'class' => 'waves-effect waves-dark', 'url' => ['/pulau/index']],
            ['label' => 'Provinsi', 'icon' => 'mdi mdi-database', 'class' => 'waves-effect waves-dark', 'url' => ['/provinsi/index']],
            ['label' => 'Provinsi Gabungan', 'icon' => 'mdi mdi-database', 'class' => 'waves-effect waves-dark', 'url' => ['/provinsi-gabungan/index']],
            ['label' => 'Kota/Kabupaten', 'icon' => 'mdi mdi-database', 'class' => 'waves-effect waves-dark', 'url' => ['/kota-kabupaten/index']],
            ['label' => 'Kecamatan', 'icon' => 'mdi mdi-database', 'class' => 'waves-effect waves-dark', 'url' => ['/kecamatan/index']],
            ['label' => 'Kelurahan', 'icon' => 'mdi mdi-database', 'class' => 'waves-effect waves-dark', 'url' => ['/kelurahan/index']],
            ['label' => 'Jenis Produksi', 'icon' => 'mdi mdi-database', 'class' => 'waves-effect waves-dark', 'url' => ['/jenis-produksi/index']],
            ['label' => 'Profile Plant', 'icon' => 'mdi mdi-account', 'class' => 'waves-effect waves-dark', 'url' => ['/profil-plant/index']],
            ['label' => 'Berita', 'icon' => 'mdi mdi-bullhorn', 'class' => 'waves-effect waves-dark', 'url' => ['/berita/index']],
            ['label' => 'Data Master Pemasok', 'icon' => 'mdi mdi-package', 'class' => 'waves-effect waves-dark', 'url' => ['/profil-supplier/index']],
        ]
    ],
    [
        'label' => '<span class="hide-menu">Panduan</span>',
        'encode' => false,
        'icon' => 'mdi mdi-help',
        'class' => 'waves-effect waves-dark',
        'url' => 'https://simpakan.ditjenpkh.pertanian.go.id/sijagung/backend/web/panduan_sijagung_2023.pdf'
    ],

];

$menuItems = Mimin::filterMenu($menuItems);
?>


<!-- ============================================================== -->
<!-- Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- User profile -->
        <div class="user-profile"
            style="background: url(<?= Url::base(true) ?>/images/background/user-info.jpg) no-repeat;">
            <!-- User profile image -->
            <div class="profile-img">&nbsp;</div>
            <!-- User profile text-->
            <div class="profile-text">
                <div class="dropdown-menu animated flipInY"> <a href="#" class="dropdown-item"><i class="ti-user"></i>
                        My Profile</a> <a href="#" class="dropdown-item"><i class="ti-wallet"></i> My Balance</a> <a
                        href="#" class="dropdown-item"><i class="ti-email"></i> Inbox</a>
                    <div class="dropdown-divider"></div> <a href="#" class="dropdown-item"><i class="ti-settings"></i>
                        Account Setting</a>
                    <div class="dropdown-divider"></div>
                    <?= Html::a(
                        '<i class="fa fa-power-off"></i> Logout',
                        ['/site/logout'],
                        [
                            'data-method' => 'post',
                            'class' => 'dropdown-item',
                        ]
                    ) ?>
                </div>
            </div>
        </div>
        <!-- End User profile text-->
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <?= NavWidget::widget(
                [
                    'options' => ['id' => 'sidebarnav', 'class' => 'in'],
                    'items' => $menuItems,
                ]
            ) ?>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
    <!-- Bottom points-->
    <div class="sidebar-footer">

    </div>
    <!-- End Bottom points-->
</aside>
<!-- ============================================================== -->
<!-- End Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
