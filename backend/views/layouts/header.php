<?php
use yii\helpers\Html;
use yii\helpers\Url;
use backend\models\NotifUser;
/* @var $this \yii\web\View */
/* @var $content string */
$notif = NotifUser::find()->where(['id_user'=>\Yii::$app->user->id,'status'=>0])->limit(5)->all();
$notifCount = count($notif);
?>

<!-- ============================================================== -->
<!-- Topbar header - style you can find in pages.scss -->
<!-- ============================================================== -->
<header class="topbar">
    <nav class="navbar top-navbar navbar-expand-md navbar-light">
        <!-- ============================================================== -->
        <!-- Logo -->
        <!-- ============================================================== -->
        <div class="navbar-header">
            <a class="navbar-brand" href="index.html">
                <!-- Logo icon -->
                <b>
                    <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                    <!-- Dark Logo icon -->
                    <img src="<?= Url::base(true)?>/images/logo-icon.png" alt="homepage" class="dark-logo" />
                    <!-- Light Logo icon -->
                    <img src="<?= Url::base(true)?>/images/logo-light-icon.png" alt="homepage" class="light-logo" />
                </b>
                <!--End Logo icon -->
                <!-- Logo text -->
                <span>
                 <!-- dark Logo text -->
                 <img src="<?= Url::base(true)?>/images/logo-text.png" alt="homepage" class="dark-logo" />

                 <!-- Light Logo text -->
                 <img src="<?= Url::base(true)?>/images/logo-light-text.png" class="light-logo" alt="homepage" /></span>
                </a>
        </div>
        <!-- ============================================================== -->
        <!-- End Logo -->
        <!-- ============================================================== -->
        <div class="navbar-collapse">
            <!-- ============================================================== -->
            <!-- toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="navbar-nav mr-auto mt-md-0">
                <!-- This is  -->
                <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
                <li class="nav-item"> <a class="nav-link sidebartoggler hidden-sm-down text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
            </ul>
            <!-- ============================================================== -->
            <!-- User profile and search -->
            <!-- ============================================================== -->
            <ul class="navbar-nav my-lg-0">
            <?php 
                if(\Yii::$app->authManager-> getAssignment('admin',\Yii::$app->user->id)):
            ?>
              <!-- ============================================================== -->
              <!-- Comment -->
              <!-- ============================================================== -->
              <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle text-muted text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-message"></i>
                      <div class="notify">
                        <?php
                          if($notifCount > 0){
                            echo '<span class="heartbit"></span> <span class="point"></span>';
                          }
                        ?>
                      </div>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right mailbox scale-up">
                      <ul>
                          <li>
                              <div class="drop-title">Notifications</div>
                          </li>
                          <li>
                              <div class="message-center">
                                  <?php
                                    foreach ($notif as $item):
                                  ?>
                                      <a href="<?= Url::to([$item->notif->notifType->route, 'id' => $item->notif->id_object]) ?>">
                                          <div class="btn btn-info btn-circle"><i class="fa fa-link"></i></div>
                                          <div class="mail-contnet">
                                            <h5><?= $item->notif->judul?></h5>
                                            <span class="mail-desc"><?= $item->notif->keterangan?></span>
                                            <span class="time"><?= $item->notif->tanggal?></span>
                                          </div>
                                      </a>
                                  <?php
                                    endforeach;
                                  ?>
                                  <!-- Message -->
                              </div>
                          </li>
                          <li>
                              <?php
                                echo Html::a(
                                    '<strong>Check all notifications</strong> <i class="fa fa-angle-right"></i>',
                                    ['/notif/index'],
                                    [
                                        'class' => 'nav-link text-center'
                                    ]
                                ) . ' ';
                              ?>
                          </li>
                      </ul>
                  </div>
              </li>
              <!-- ============================================================== -->
              <!-- End Comment -->
              <!-- ============================================================== -->
              <?php
                endif;
              ?>

                <!-- ============================================================== -->
                <!-- Profile -->
                <!-- ============================================================== -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="<?= Url::base(true)?>/images/users/profile.png" alt="user" class="profile-pic" /></a>
                    <div class="dropdown-menu dropdown-menu-right scale-up">
                        <ul class="dropdown-user">
                            <li>
                                <div class="dw-user-box">
                                    <div class="u-text">
                                        <h4><?= \Yii::$app->user->identity->nama?></h4>
                                    </div>
                                </div>
                            </li>
                            <li role="separator" class="divider"></li>
                            <li>
                                <?= Html::a(
                                            '<i class="fa fa-power-off"></i> Logout</span>',
                                            ['/site/logout'],
                                            ['data-method' => 'post']
                                ) ?>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>
<!-- ============================================================== -->
<!-- End Topbar header -->
<!-- ============================================================== -->
