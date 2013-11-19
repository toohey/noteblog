<?php if(RUNNING_ENV === DEVELOPMENT && isset($GLOBALS[CONFIGS]['project']['debug']) && $GLOBALS[CONFIGS]['project']['debug']) : ?>
    <div  class='clear'></div>
    <script>
        $(document).ready(function(){
            $('.debug-data').hide();
            $('.debug-title').click(function(){
               $(this).next().toggle(); 
            }).css('cursor','pointer');
            $('#Debug-Section a').click(function($e){
                $e.preventDefault();
            });
        });
    </script>
    <div id="Debug-Section" style="margin:10px;padding:10px">
        <fieldset>
            <legend onclick="$('#debug-info').toggle();"><a href=''>Debug Section</a></legend>
            <div id="debug-info" style='display:none;'>
                <div id='configs'>
                    <table class="table table-striped" style='border: 0px dotted #e6e6e6'>
                        <tbody>
                            <?php if(User::IsLoggedIn()): ?>
                            <tr>
                                <td>
                                    <div class='debug-title'><a href=''>User Data</a></div>
                                    <div class='debug-data'><?php \iMVC\Tools\Debug::_var(\User::GetInstance()) ?></div>
                                </td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <td>
                                    <div class='debug-title'><a href=''>User Request</a></div>
                                    <div class='debug-data'><?php \iMVC\Tools\Debug::_var($this->GetRequest()) ?></div>
                                </td>
                            </tr>
                            <?php if(count($_POST)): ?>
                            <tr>
                                <td>
                                    <div class='debug-title'><a href=''>POST Data</a></div>
                                    <div class='debug-data'><?php \iMVC\Tools\Debug::_var($_POST) ?></div>
                                </td>
                            </tr>
                            <?php endif; ?>
                            <?php if(count($_GET)): ?>
                            <tr>
                                <td>
                                    <div class='debug-title'><a href=''>GET Data</a></div>
                                    <div class='debug-data'><?php \iMVC\Tools\Debug::_var($_GET) ?></div>
                                </td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <td>
                                    <div class='debug-title'><a href=''>Loaded Session</a></div>
                                    <div class='debug-data'><?php \iMVC\Tools\Debug::_var($_SESSION); ?></div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class='debug-title'><a href=''>Loaded Cookie</a></div>
                                    <div class='debug-data'><?php \iMVC\Tools\Debug::_var($_COOKIE); ?></div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class='debug-title'><a href=''>Loaded Configurations</a></div>
                                    <div class='debug-data'><?php \iMVC\Tools\Debug::_var($GLOBALS[CONFIGS]); ?></div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </fieldset>
    </div>
<?php endif; ?>