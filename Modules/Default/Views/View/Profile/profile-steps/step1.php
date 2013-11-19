<style>
    div#profile-content div#step1 div strong{margin-top: 7px;}
    div#profile-content div#step1 div#name-section div{float:left;}
    div#profile-content div#step1 div#birthday-section {padding-bottom: 20px;}
    div#profile-content div#step1 div#gender select,
    div#profile-content div#step1 div#birthday-section select{float: left;margin-right: 20px;width: 100px}
</style>
<div id="step1">
    <div id="name-section">
        <div class="span3">
            <strong class="span4">First Name</strong>
            <input type="text" name="first_name" class="input input-medium span8" value="<?=$this->post_back && isset($this->post['first_name']) ?$this->post['first_name']:isset($this->user->userprofile->first_name)?$this->user->userprofile->first_name:""?>" required=""/>
        </div>
        <div class="span3">
            <strong>Nick Name</strong>
            <input type="text" name="nick_name" class="input input-medium span8" value="<?=$this->post_back && isset($this->post['nick_name'])?$this->post['nick_name']:isset($this->user->userprofile->nick_name)?$this->user->userprofile->nick_name:""?>" />
        </div>
        <div class="span3">
            <strong class="span4">Last Name</strong>
            <input type="text" name="last_name"  class="input input-medium span8" value="<?=$this->post_back && isset($this->post['last_name'])?$this->post['last_name']:isset($this->user->userprofile->last_name)?$this->user->userprofile->last_name:""?>" required=""/>
        </div>
    </div>
    <div class="clear" ></div>
    <hr />
    <div id="birthday-section" class="clear">
        <div class="span6">
            <strong class="span2">Birthday</strong>
            <select name="birth_month" required="">
                <option <?=$this->post_back?'':'selected="selected"'?> disabled="disabled" style="color:#e6e6e6">Month</option>
                <?php $i=1; while($i<=12) echo "<option ".((($this->post_back && isset($this->post['birth_month']) && $i==$this->post['birth_month']) || (isset($this->user->userprofile->birth_month) && $this->user->userprofile->birth_month==$i))?'selected="selected"':'')." value='$i'>".date('F',mktime(0,0,0,$i++))."</option>"; ?>
            </select>
            <select name="birth_day" required="">
                <option selected="selected" disabled="disabled" style="color:#e6e6e6">Day</option>
                <?php $i=1; while($i<=31) echo "<option ".((((isset($this->user->userprofile->birth_day) && $this->user->userprofile->birth_day==$i)
                        || ($this->post_back && isset($this->post['birth_day']) && $i==$this->post['birth_day'])))?'selected="selected"':'')." value='$i'>".$i++."</option>"; ?>
            </select>
            <select name="birth_year" required="">
                <option selected="selected" disabled="disabled" style="color:#e6e6e6">Year</option>
                <?php $i=1900; while($i<=date('Y')) echo "<option ".((($this->post_back && isset($this->post['birth_year']) && $i==$this->post['birth_year']) || (isset($this->user->userprofile->birth_year) && $this->user->userprofile->birth_year==$i))?'selected="selected"':'')." value='$i'>".$i++."</option>"; ?>
            </select>
        </div>
    </div>
    <div class="clear" ></div>
    <hr />
    <div id="gender" class="clear" style="padding-bottom: 20px;">
        <div class="span6">
            <strong class="span2">Gender</strong>
            <select name="is_male" required="">
                <option <?=$this->post_back && isset($this->post['is_male']) && $this->post['is_male']?'selected="selected"':''?> value="1">Male</option>
                <option <?=$this->post_back  && isset($this->post['is_male']) && !$this->post['is_male']?'selected="selected"':''?> value="0">Female</option>
            </select>
        </div>
    </div>
</div>