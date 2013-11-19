<style>
    div#profile-content div#step3 div strong{margin-top: 7px;}
    div#profile-content div#step3 .bit-left {margin-left:0px;}
</style>
<div id="step3">
    <div class="span11">
        <strong class="span3">Public Email Address</strong>
        <input type="text" class="span6 input input-medium" name="public_email" value="<?=$this->post_back?isset($this->post['email'])?$this->post['email']:'':''?>"/>
    </div>
    
    <div class="clear"></div>
    <hr />
    
    <div class="span11 bit-left">
        <strong class="span3 ">Phone Number</strong>
        <input type="text" class="span6 input input-medium" name="phone" value="<?=$this->post_back?$this->post['phone']:''?>" />
    </div>
    
    <div class="clear"></div>
    <hr />
    
    <div class="span11 bit-left">
        <strong class="span3">Online Site</strong>
        <input type="text" class="span6 input input-medium" name="site" value="<?=$this->post_back?$this->post['site']:''?>" />
    </div>
</div>