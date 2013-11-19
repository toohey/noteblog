<style>
    div#profile-content div#step2 div strong{margin-top: 7px;}
    div#profile-content div#step2 div .bit-left {margin-left:-10px;}
</style>
<div id="step2">
    <div class="span12">
        <strong class="span2">Introduction</strong>
        <input type="text" name="intro" class=" span10 input input-medium" value="<?=$this->post_back?$this->post['intro']:''?>" />
    </div>
    <div class="clear" ></div>
    <hr />
    <div class="span6 " style="margin: 0">
        <strong class="span4 ">Occupation</strong>
        <input type="text" id="first" name="occu" class="bit-left span8 input input-medium"  value="<?=$this->post_back && isset($this->post['occu'])?$this->post['occu']:''?>"/>
    </div>
    <div class="span6">
        <strong class="span4">Education</strong>
        <input type="text" name="edu" class="bit-left span8 input input-medium" value="<?=$this->post_back && isset($this->post['edu'])?$this->post['edu']:''?>"/>
    </div>
    <div class="clear"></div>
    <hr />
    
    <div id="block3" class="block">
        <div class="span6">
            <strong class="span4">Country</strong>
            <select name="country" class="bit-left input input-medium span8">
                <option selected="selected" class="" disabled="disabled" style="color:#e6e6e6">Choose</option>
                <?php
                if(false){
                    $this->l = new Load();
                    $this->l->external_helper();
                    $this->cl = get_country_list();
                    foreach($this->cl as $this->c)
                    {
                        echo "<option ".($this->post_back && isset($this->post['country']) && $this->post['country']==$this->c?'selected="selected"':'')." value='$this->c'>$this->c</option>";


                }}
                ?>
            </select>
        </div>
        <div class="span6">
            <strong class="span4">Town</strong>
            <input type="text" name="city" class="bit-left input input-medium span8" value="<?=$this->post_back && isset($this->post['city'])?$this->post['city']:''?>" />
        </div>
    </div>
</div>
<div class="clear" style="margin-top: 10px;" ></div>