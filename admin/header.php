
<script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js" integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc" crossorigin="anonymous"></script>


<nav class="navbar navbar-expand-custom navbar-mainbg">
    <a class="navbar-brand navbar-logo" href="#">GARSOFT</a>
    <button class="navbar-toggler" type="button" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fas fa-bars text-white"></i>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto"><?php

               $tab = sanitize_text_field(isset($_REQUEST['tab']) ? $_REQUEST['tab'] : 'home');


            ?>
            <li class="nav-item <?php if($tab=="home"){echo 'active';} ?>">
                <a class="nav-link" href="<?php echo admin_url('admin.php?page=gar_yik_yurtici_kargo') ?>"><i class="fas fa-home"></i>Giriş</a>
            </li>
            <li class="nav-item <?php if($tab=="garsoft-gonderi-listesi"){echo 'active';} ?>">
                <a class="nav-link" href="<?php echo admin_url('admin.php?page=gar_yik_yurtici_kargo&tab=garsoft-gonderi-listesi') ?>"><i class="fas fa-truck"></i>Gönderiler</a>
            </li>
            <li class="nav-item <?php if($tab=="yik_ayarlar"){echo 'active';} ?>">
                <a class="nav-link" href="<?php echo admin_url('admin.php?page=gar_yik_yurtici_kargo&tab=yik_ayarlar') ?>"><i class="fas fa-cog"></i>Ayarlar</a>
            </li>
            <li class="nav-item <?php if($tab=="yik_premium"){echo 'active';} ?>">
                <a class="nav-link" href="<?php echo admin_url('admin.php?page=gar_yik_yurtici_kargo&tab=yik_premium') ?>"><i class="far fa-calendar-alt"></i>Premium</a>
            </li>

        </ul>
    </div>
</nav>
