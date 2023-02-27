<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class mypageResumeDataSetting
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $data = $request->all();

		$history_lines = 25;
		$license_lines = 6;

		// ラジオボタンに表示する要素を配列で宣言する
		$with_without['1'] = '有';
		$with_without['2'] = '無';

		// 年齢計算
		$birth_date = $data["basic_birth_year"].sprintf("%02d",$data["basic_birth_month"]).sprintf("%02d",$data["basic_birth_date"]);
		$data["basic_age"] = (int)((date('Ymd')-$birth_date)/10000);

		// 現住所以外の連絡先
		if (isset($data["contact_toggle_chk"]) && $data["contact_toggle_chk"] == 1) {
			$data["contact_postal_1"] = ""; $data["contact_postal_2"] = ""; $data["contact_address"] = ""; $data["contact_address_kana"] = "";
			$data["contact_phone1_1"] = ""; $data["contact_phone1_2"] = ""; $data["contact_phone1_3"] = "";
			$data["contact_phone2_1"] = ""; $data["contact_phone2_2"] = ""; $data["contact_phone2_3"] = "";
		}

		// 学歴情報をセット
		$data["history_list"] = array();
		array_push($data["history_list"], array("", "", "学歴", "1"));

		for($j=0; $j<count($data["edu_history_toggle"]); $j++){
			$order = $data["edu_history_order"][$j];
			$eh_str = "";
			if ($data["edu_history_category"][$order] == 1) {
				$eh_str = "入学";
			} else if ($data["edu_history_category"][$order] == 2) {
				$eh_str = "卒業";
			} else if ($data["edu_history_category"][$order] == 3) {
				$eh_str = "中退";
			}
			if ($data["edu_history_toggle"][$order] == 1) {
				array_push($data["history_list"], array($data["edu_history_year"][$order], $data["edu_history_month"][$order], $data["edu_history_school"][$order]." ".$eh_str, "0"));
			}
		}

		// 職歴情報をセット
		//array_push($data["history_list"], array("", "", "", ""));
		array_push($data["history_list"], array("", "", "職歴", "1"));

		for($j=0; $j<count($data["career_history_toggle"]); $j++){
				$order = $data["career_history_order"][$j];
				$eh_str = "";
			if ($data["career_history_category"][$order] == 1) {
				$eh_str = "入社";
			} elseif ($data["career_history_category"][$order] == 2) {
				$eh_str = "退職";
			}
			if ($data["career_history_toggle"][$order] == 1) {
				if ($data["career_history_category"][$order] == 4) {
					array_push($data["history_list"], array("", "", "　　現在に至る", "0"));
				} else {
					array_push($data["history_list"], array($data["career_history_year"][$order], $data["career_history_month"][$order], $data["career_history_company"][$order]." ".$eh_str, "0"));
				}
			}
		}
		array_push($data["history_list"], array("", "", "以上", "2"));

		// 残り行は空行をセット
		for($j=count($data["history_list"]); $j<$history_lines; $j++){
			array_push($data["history_list"], array("", "", ""));
		}
		//echo var_dump($data["history_list"]);
		//die();

		// 資格情報をセット
		$data["license_list"] = array();
		for($i=0; $i<count($data["licence_history_toggle"]); $i++){
			$order = $data["licence_history_order"][$i];
			$eh_str = "";
			if ($data["licence_history_category"][$order] == 1) {
				$eh_str = "合格";
			} else if ($data["licence_history_category"][$order] == 2) {
				$eh_str = "取得";
			}
			if ($data["licence_history_toggle"][$order] == 1) {
				array_push($data["license_list"], array($data["licence_history_year"][$order], $data["licence_history_month"][$order], $data["licence_history_name"][$order]." ".$eh_str));
			} else {
				array_push($data["license_list"], array("", "", ""));
			}
		}

		if(isset($data['spouse_exist']) && $data['spouse_exist'] == 1){
			$data['spouse_exist_display'] = "有";
		} else {
			$data['spouse_exist_display'] = "無";
		}

		if(isset($data['spouse_support']) && $data['spouse_support'] == 1){
			$data['spouse_support_display'] = "有";
		} else {
			$data['spouse_support_display'] = "無";
		}
        
        $request->merge($data);

        return $next($request);
    }
}
