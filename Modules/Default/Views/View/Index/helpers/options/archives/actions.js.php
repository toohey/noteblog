<li class="dropdown-submenu mono-select select">
       <a href="#"><span class='icon-cog'></span> Actions</a>
       <?php 
           $properties_called_from_headline_menu_file = true;
           require MODULE_PATH. 'Default/Views/View/Index/helpers/pjs-scripts/properties-options.php';
           unset($properties_called_from_headline_menu_file);
       ?>
   </li>
<li class="divider select mono-select"></li>
<li class='select <?php echo $type!='folder'?'': 'mono-select'?>' >
    <a href="#" onclick='ShareFolder()' >
        <span class="icon-share"></span> Toggle-Share
    </a>
</li>
<li class="select">
    <a href="#" onclick='De_Archive(0)'>
        <span class="icon-briefcase"></span> Dearchive Selections
    </a>
</li>
<hr class="select"  style="margin: 7px 0 7px 0"/>
<li class="select">
    <a href="#" onclick="DeleteItems(undefined, 't');">
        <span class="icon-remove"></span> Delete Selections
    </a>
</li>