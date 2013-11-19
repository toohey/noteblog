<?php if($pid!=-1): ?>
<div class="pull-right" style="">
    <div class="btn-group">
      <button class="btn dropdown-toggle" data-toggle="dropdown">
          <span class="icon-cog"></span>
          Properties
          <span class="caret"></span>
      </button>
    <?php require MODULE_PATH.'Default/Views/View/Index/helpers/pjs-scripts/properties-options.php'; ?>
    </div>
</div>
<?php endif; ?>