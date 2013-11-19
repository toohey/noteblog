<?php if(!isset($e)) { header ( 'location: /'); exit; } ?>
<html>
    <head>
        <title>iMVC Project Skeleton</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <script src="/access/js/jquery.min.1.9.1.js"></script>
        <script src="/access/js/modalAlert.js"></script>
                    <link href="/access/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
            <link href="/access/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
            <script src="/access/bootstrap/js/bootstrap.min.js"></script>
            <script src="/access/bootstrap/js/tooltip.js"></script>
            <script>
                $(document).ready(function(){
                   $('*[data-toggle="tooltip"]').tooltip(); 
                });
            </script>
                <link href="/access/css/global.css" rel="stylesheet"/>
    </head>
    <body>
        <noscript>
            <div class="alert-danger alert text-warning text-center" style="font-weight: bolder;width: 100%;height: 30px;padding-top: 20px;margin:0;" class='no-script'>
                This is required to enable your Javascript or use a modern browser.
                Some of site's functionalities won't work properly!
            </div>
        </noscript>
                <div class="container-fluid" style="clear: both;"> 
                    <div class="container-fluid row-fluid">
                        <?php if(RUNNING_ENV == PRODUCTION): ?>
                    <link href="/access/css/fuzzy-style.css" rel="stylesheet">
                    <div id='fuzzy-env'></div>
                    <div class='modal span8' id='fuzzy-content' style='left:15%;height: 50%;top:20%'>
                        <div class='modal-header '>
                            <b >Oops ...!</b>
                            <a class='btn btn-primary btn-danger pull-right' href='/' style='margin-top:-10px;'>Get Back</a>
                            <div class='clear'></div>
                        </div>
                        <div class='modal-body'>
                            <div style='font-weight: bolder;font-variant: small-caps' class='alert alert-block alert-error text-center'>
                                <?php
                                    echo $e->getMessage();
                                ?>
                            </div>
                    <?php else: ?>
                    <div class="span11" style="margin: 10px auto 0 auto;">
                        <legend>Error Message</legend>
                        <?php iMVC\Tools\Debug::_var ( $e->getMessage()); ?>
                        <legend>Stack strace</legend>
                        <pre class='alert alert-block alert-info' style='color:#000'><?php echo method_exists($e,"GetErrorTraceAsString")?$e->GetErrorTraceAsString():$e->getTraceAsString(); ?></pre>
                    <?php endif; if(RUNNING_ENV == PRODUCTION): ?>
                        <script>
                            $(document).ready(function(){
                               setTimeout(function(){window.location = '/';},2500); 
                            });
                        </script>
                        <legend></legend>
                        <blockquote style='font-variant: small-caps'>
                            The page will restart automatically, or you may redirect <a href='/'>now</a>.
                        </blockquote>
                    </div>
                    <?php endif; ?>
                        </div>
                    </div>
            </div>
    </body>
</html>