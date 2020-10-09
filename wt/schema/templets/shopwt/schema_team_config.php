<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3><?php echo $lang['nc_schema_team_config'];?></h3>
            </div>
            <ul class="tab-base nc-row">
                <li><a href="JavaScript:void(0);" class="current"><?php echo $lang['nc_schema_team_config'];?></a>
            </ul>
        </div>
    </div>
    <form id="add_form" method="post" enctype="multipart/form-data" action="index.php?act=schema_team_config&op=schema_team_config_save">
        <div class="ncap-form-default">
            <!-- 开启团队多级奖 -->
            <dl class="row">
                <dt class="tit"><!--nc_schema_team_open-->
                    <label for="schema_isuse"><?php echo $lang['nc_schema_team_open'];?></label>
                </dt>
                <dd class="opt">
                    <div class="onoff">
                        <label for="schema_team_open_1" class="cb-enable <?php if($output['setting']['schema_team_open'] == '1'){ ?>selected<?php } ?>" ><?php echo $lang['schema_open'];?></label>
                        <input type="radio" id="schema_team_open_1" name="schema_team_open" value="1" <?php echo $output['setting']['schema_team_open']==1?'checked=checked':''; ?>>
                        <label for="schema_team_open_2" class="cb-disable <?php if($output['setting']['schema_team_open'] == '0'){ ?>selected<?php } ?>" ><?php echo $lang['schema_close'];?></label>
                        <input type="radio" id="schema_team_open_2" name="schema_team_open" value="0" <?php echo $output['setting']['schema_team_open']==0?'checked=checked':''; ?>>
                    </div>
                    <p class="notic"><?php echo $lang['notice_team_isuse'];?></p>
                </dd>
            </dl>

            <!-- 等级升级设置 -->
            <dl class="row">
                <dt class="tit">
                    <label for="schema_condition"><?php echo $lang['schema_levelup'];?></label>
                </dt>
                <dd class="opt">
                    <div class="onoff">
                        <label for="schema_team_condition_1" class="cb-enable <?php if($output['setting']['schema_team_condition'] == '1'){ ?>selected<?php } ?>" ><?php echo $lang['schema_team_or'];?></label>
                        <input type="radio" id="schema_team_condition_1" name="schema_team_condition" value="1" <?php echo $output['setting']['schema_team_condition']==1?'checked=checked':''; ?>>
                        <label for="schema_team_condition_0" class="cb-disable <?php if($output['setting']['schema_team_condition'] == '0'){ ?>selected<?php } ?>" ><?php echo $lang['schema_team_and'];?></label>
                        <input type="radio" id="schema_team_condition_0" name="schema_team_condition" value="0" <?php echo $output['setting']['schema_team_condition']==0?'checked=checked':''; ?>>
                    </div>
                    <p class="notic"><?php echo $lang['notice_team_condition_or'];?></p>
                    <p class="notic"><?php echo $lang['notice_team_condition_and'];?></p>
                </dd>
            </dl>
            <!-- 团队奖金设置 -->
            <!--<dl class="row">
                <dt class="tit">
                    <label for="schema_layer_one_ratio"><?php /*echo $lang['schema_team_layer_ratio'];*/?></label>
                </dt>
                <dd class="opt">
                    <input type="text" value="<?php /*echo $output['setting']['schema_team_layer_ratio'];*/?>" name="schema_team_layer_ratio" class="input-txt">
                    <span class="err"></span>
                    <p class="notic"><?php /*echo $lang['schema_team_layer_notice'];*/?></p>
                </dd>
            </dl>-->
            <div class="bot"><a id="submit" href="javascript:void(0)" class="ncap-btn-big ncap-btn-green"><?php echo $lang['nc_submit'];?></a></div>
        </div>

    </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.nyroModal.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
        $("#submit").click(function(){
            $("#add_form").submit();
        });
    });
</script>
