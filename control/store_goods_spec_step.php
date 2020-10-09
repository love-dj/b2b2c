<?php
/**
 * 规格阶梯价格
 *
 *
 *
 *

 

 */



defined('ShopWT') or exit ('Access Invalid!');
class store_goods_spec_stepControl{
    /**
     * 临时保存第二步不同规格的阶梯价格
     */
	public function TempSaveSpecStepPriceWt(){
        Tpl::setDir('seller');
        Tpl::setLayout('spec_step_layout');
		//临时存储规格的阶梯价格
		$spec = $_POST['spec'];
		$specFile = $spec;
		if(!file_exists(BASE_UPLOAD_PATH."/spec/"))
		{
			mkdir(BASE_UPLOAD_PATH."/spec/",0777);
		}
		//删除临时文件
		@unlink(BASE_UPLOAD_PATH."/spec/".$specFile);
		for($i=1;$i<=5;$i++)
		{
			if($_POST['step_num'.$i]>0)
			{
				$specText = $spec."_".$i."_".$_POST['step_num'.$i]."_".$_POST['step_price'.$i];
				file_put_contents(BASE_UPLOAD_PATH."/spec/".$specFile,$specText."\r\n",FILE_APPEND);
			}
		}
		echo 1 ;
	}
	
    /**
     * 临时保存第二步不同规格的阶梯价格
     */
	public function TempGetSpecStepPriceWt(){
        Tpl::setDir('seller');
        Tpl::setLayout('spec_step_layout');

		//提取不同规格的阶梯价格		
		
		if(isset($_POST['good_id']))
		{
			$good_id = $_POST['good_id'];
		}
		
		$spec_id = $_POST['spec_id'];
		if(!file_exists(BASE_UPLOAD_PATH."/spec/".$spec_id))
		{
			$model_goods = Model('goods');
			$step_prices = $model_goods->getGoodStepPrice(array('common_id'=>$good_id));
			echo json_encode($step_prices);
		}
		else
		{
			$specTMP=array();
			$fileContent = @fopen(BASE_UPLOAD_PATH."/spec/".$spec_id, "r");
			$i=0;
			$jsonValue="[";
			while(!feof($fileContent))
			{
				 $specTMP[$i]= fgets($fileContent);
				 $i++;
			}
			
			fclose($fileContent);
			$orArr = array();
			for($s=0;$s<count($specTMP);$s++)
			{
				$spArr = explode("_",$specTMP[$s]);
				$orArr[$spArr[2]] = $specTMP[$s];
			}
			$t=count($orArr)+1;
			for($r=1;$r<$t;$r++)
			{
				$r_spArr = explode("_",$orArr[$r]);
				if($r_spArr[2]>0)
				{
					$jsonValue = $jsonValue."{\"step_l_num\":\"".trim($r_spArr[3])."\",\"step_price\":\"".trim($r_spArr[4])."\"},";
				}
			}
			$jsonValue = substr($jsonValue,0,-1);
			$jsonValue=$jsonValue."]";
			echo $jsonValue;
		}
	}
}
?>