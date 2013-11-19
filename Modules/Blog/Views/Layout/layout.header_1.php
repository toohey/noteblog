<html>
    <head>
        <?php require 'head-tag-cont.phtml';?>
    </head>
    <body>
        <noscript>
            <div class="alert-danger alert text-warning text-center" style="font-weight: bolder;width: 100%;height: 30px;padding-top: 20px;margin:0;" class='no-script'>
                This is required to enable your Javascript or use a modern browser.
                Some of site's functionalities won't work properly!
            </div>
        </noscript>
        <?php if(!isset($this->options->no_header) || !$this->options->no_header) : ?>
        <div class="navbar navbar-inverse">
            <div class="navbar-inner">
              <div class="container-fluid" >
                <div class="navbar">
                    <ul class="nav">
                        <li class="active">
                          <a class="brand" href="/">NoteBlog</a>
                        </li>
                        <li class="">
                          <a href="#">PlaceHolder1</a>
                        </li>
                        <li class="">
                          <a href="#">PlaceHolder2</a>
                        </li>
                        <li class="">
                          <a href="#">PlaceHolder3</a>
                        </li>
                        <li class="">
                          <a href="#">PlaceHolder4</a>
                        </li>
                    </ul>
                    <ul class="nav pull-right">
                        <?php if(\User::IsLoggedIn()) : ?>
                        <?php 
                            $fr = new \iMVC\Routing\FakeRequest('/auth/login.info');
                            try
                            {
                                echo $fr->Send(1);
                            }catch(Exception $e)
                            {}
                        ?>
                        <?php else : ?>
                        <?php
                            $login = new iMVC\Routing\FakeRequest('/auth/login.menu');
                            $signup = new iMVC\Routing\FakeRequest('/auth/signup.menu');
                            try
                            {
                                echo $login->Send(1);
                            }catch(Exception $e)
                            {}
                            try
                            {
                                echo $signup->Send(1);
                            }catch(Exception $e)
                            {}
                        ?>
                        <?php endif; ?>
                    </ul>
                </div>
              </div>
            </div>
          </div>
        <?php endif; ?>
        <div class="container-fluid" style="clear: both;"> 
        <?php if(isset($_SESSION['app']['tmp']['alert']['messages'])) : ?>
            <div class="block" id="general-alert-info">
                <div class="text-center inline ">
                <?php echo $_SESSION['app']['tmp']['alert']['messages']; ?>
                </div>
                <script>
                    $(document).ready(function(){
                        setTimeout(function(){$('#general-alert-info').slideUp('slow');},7500);
                    });
                </script>
            </div>
        <?php unset($_SESSION['app']['tmp']['alert']); endif; ?>