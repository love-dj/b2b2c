<?php $i = 1; $areas = $output['areas']; foreach ($areas['region'] as $region => $provinceIds) { ?>
<li<?php if ($i % 2 == 0) echo ' class="even"'; ?>>
  <dl class="wtsc-region">
    <dt class="wtsc-region-title">
      <span>
      <input type="checkbox" id="J_Group_<?php echo $i; ?>" class="J_Group" value=""/>
      <label for="J_Group_<?php echo $i; ?>"><?php echo $region; ?></label>
      </span>
    </dt>
    <dd class="wtsc-province-list">
<?php foreach ($provinceIds as $provinceId) { ?>
      <div class="wtsc-province"><span class="wtsc-province-tab">
        <input type="checkbox" class="J_Province" id="J_Province_<?php echo $provinceId; ?>" value="<?php echo $provinceId; ?>"/>
        <label for="J_Province_<?php echo $provinceId; ?>"><?php echo $areas['name'][$provinceId]; ?></label>
        <span class="check_num"/> </span><i class="icon-angle-down trigger"></i>
        <div class="wtsc-citys-sub">
<?php foreach ($areas['children'][$provinceId] as $cityId) { ?>
          <span class="areas">
          <input type="checkbox" class="J_City" id="J_City_<?php echo $cityId; ?>" value="<?php echo $cityId; ?>"/>
          <label for="J_City_<?php echo $cityId; ?>"><?php echo $areas['name'][$cityId]; ?></label>
          </span>
<?php } ?>
          <p class="tr hr8"><a href="javascript:void(0);" class="wtbtn-mini wtbtn-bittersweet close_button">关闭</a></p>
        </div>
        </span>
      </div>
<?php } ?>

    </dd>
  </dl>
</li>
<?php $i++; } ?>
