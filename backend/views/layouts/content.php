<?php
use yii\widgets\Breadcrumbs;
use yii\bootstrap4\Alert;

?>
<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-12 col-12 align-self-center">
                <h3 class="text-themecolor m-b-0 m-t-0"><?= $this->title?></h3>
                <?= Breadcrumbs::widget([
                    'tag' => 'ol',
                    //'options' => ['class' => 'breadcrumb float-sm-right'],
                    'itemTemplate' => "<li class=\"breadcrumb-item\">{link}</li>\n",
                    'activeItemTemplate' => "<li class=\"breadcrumb-item active\">{link}</li>\n",
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <?php 
                if(Yii::$app->session->hasFlash('info')){
                    echo '
                    <div class="alert alert-info"> 
                        '. Yii::$app->session->getFlash('info').'
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                    </div>';
                }

                if(Yii::$app->session->hasFlash('warning')){
                    echo '
                    <div class="alert alert-warning"> 
                        '. Yii::$app->session->getFlash('warning').'
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                    </div>'; 
                }

                if(Yii::$app->session->hasFlash('success')){
                    echo '
                    <div class="alert alert-success"> 
                        '. Yii::$app->session->getFlash('success').'
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                    </div>'; 
                }

                if(Yii::$app->session->hasFlash('danger')){
                    echo '
                    <div class="alert alert-danger"> 
                        '. Yii::$app->session->getFlash('danger').'
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                    </div>';  
                }
        ?>
        <?= $content ?>
        <!-- ============================================================== -->
        <!-- End PAge Content -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- footer -->
    <!-- ============================================================== -->
    <footer class="footer">
        © 2022
    </footer>
    <!-- ============================================================== -->
    <!-- End footer -->
    <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Page wrapper  -->
<!-- ============================================================== -->
