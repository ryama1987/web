<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FullTextIndexController extends Controller
{
    //検索対象テーブル名
    public $targetTableName = 'api_job_search';

    //FULLTEXT INDEXの対象カラム設定
    public $fullTextScope = array('FulltextIndex_1');

    //指定雇用形態
    public $empType = [];

    public $selectWord = [
      'Name',
      'Catch__c',
      'PositionInner__c',
      'BusinessContents__c',
      'Qualification__c',
      'ApplyRequirement__c',
      'Prefecture1__c',
      'City1__c',
      'Employment__c',
      'FreeComment__c',
      'CompanyName__c',
      'CompanyName_OpenFlag__c',
      'Town1__c',
      'BusinessNote2__c',
      'JobCharacteristic__c',
      'Occupation1_1__c',
      'Occupation1_2__c',
      'Occupation2_1__c',
      'Occupation2_2__c',
      'Occupation3_1__c',
      'Occupation3_2__c',
      'BusinessContents_zaitaku_A__c',
      'BusinessContents_zaitaku_B__c',
      'Industry1__c',
      'Industry2__c',
      'AnnualincomeLow__c',
      'AnnualincomeUpper__c',
      'HourlyWage1__c',
      'HourlyWage2__c',
      'LaborLawyer_Outsourcingfee__c',
      'OutsourcingfeeZaitaku__c',
      'Photo__c',
      'Photo2__c',
      'SelectedOption_Temp',
      'SelectedOption_TTP',
      'SelectedOption_Part',
      'SelectedOption_JP_Regular',
      'SelectedOption_JP_Part',
      'SelectedOption_JP_Subcontracting'
    ];


    /** 
     * (1)ブーリアン版検索
    **/
    public function getjobDataBoolean(Request $request) {
      //検索ワードを調整
      $param = $this->optimizeQueryWord($request,'+');

      //雇用形態追加
      if(!is_null($request->emp)){
        $this->empType = $this->assignEmploymentType($request->emp);
      }

      //SQL文をDBへ投げてデータ取得
      $results = $this->sqlQueryCreateForBoolean($param,$this->selectWord);
      //dd($results);

      //パラメータに type=json があるかどうか
      if($request->type === 'json'){
        return response()->json([$results], 200, [], JSON_UNESCAPED_UNICODE);
      } else {
        return view('search.form', ['results' => $results]);
      }
    }

    public function sqlQueryCreateForBoolean($parameter, $sel) {
      $count = count($this->empType);
      if(!empty($this->empType)){
        $res = DB::table($this->targetTableName)
        ->select($sel)
        ->whereFulltext($this->fullTextScope, $parameter, ['mode' => 'boolean'])
        ->whereIn('Employment__c', $this->empType)
        //->getBindings();
        //->toSql();
        ->get();
        return $res;
      } else {
        $res = DB::table($this->targetTableName)
        ->select($sel)
        ->whereFulltext($this->fullTextScope,$parameter,['mode' => 'boolean'])
        //->toSql();
        ->get();
        return $res;
      }
    }


    /** 
     * (2)自然検索
    **/
    public function getjobDataNatural(Request $request) {
      //検索ワードを調整
      $param = $this->optimizeQueryWord($request);

      //雇用形態追加
      if(!is_null($request->emp)){
        $this->empType = $this->assignEmploymentType($request->emp);
      }

      //SQL文をDBへ投げてデータ取得
      $results = $this->sqlQueryCreateForNatural($param,$this->selectWord);

      //パラメータに type=json　があるかどうか
      if($request->type === 'json'){
        return response()->json([$results], 200, [], JSON_UNESCAPED_UNICODE);
      } else {
        return view('search.form', ['results' => $results]);
      }
    }

    public function sqlQueryCreateForNatural($parameter, $sel) {
      $count = count($this->empType);
      if(!empty($this->empType)){
        $res = DB::table($this->targetTableName)
        ->select($sel)
        ->whereFulltext($this->fullTextScope, $parameter)
        ->whereIn('Employment__c', $this->empType)
        //->getBindings();
        //->toSql();
        ->get();
        return $res;
      } else {
        $res = DB::table($this->targetTableName)
        ->select($sel)
        ->whereFulltext($this->fullTextScope,$parameter)
        //->toSql();
        ->get();
        return $res;
      }
    }

    //POSTされたユーザークエリーを加工
    public function getUserQuery(Request $request){
      //return response()->json([$request], 200, [], JSON_UNESCAPED_UNICODE);
      $ymd = Carbon::now()->format('Y-m-d H:i:s');
      $ip_data = $request->header('X-Forwarded-For');
      
      $send_data = [
        "Ip_Address" => $ip_data,
        "Keyword"    => $request['keyword'],
        "User_Agent" => $request->header('User-Agent'),
        "Referer" => $request->header('referer'),
        "created" => $ymd,
      ];

      try{
        $res = DB::table("api_keyword_log")->insert($send_data);
      } catch(Exception $e) {
        $res = report($e);
      }
      
      return $send_data;
    }

    //関数テスト用
    public function testdatafunc(Request $request){
      $ip_data = $request->header('X-Forwarded-For');
      
      return var_dump($ip_data);
    }

    /** 
     * その他調整用の関数
    **/

    public function optimizeQueryWord($keyword,$operator=NULL){
      //全角スペースを半角に置き換え
      $replacedKeyword = Str::replace('　', ' ', $keyword->kw);

      //キーワード数をカウント
      $keywordCount = Str::of($replacedKeyword)->explode(' ');

      if(count($keywordCount) > 1){
          if($operator == '*'){
            //「 」(半角スペース)を「* *」（半角アスタリスク半角スペース半角アスタリスク）に置き換え
            $optimizeKeyword = Str::replace(' ', $operator.' '.$operator, $replacedKeyword);
            
            //先頭に「*」を加える
            $optimizeKeyword = Str::start($optimizeKeyword, $operator);

            //末尾に「*」を加えて戻す
            return Str::finish($optimizeKeyword, $operator);
          } else {
            //「 」(半角スペース)を「 +」（プラス半角スペース）に置き換え
            $optimizeKeyword = Str::replace(' ', ' '.$operator, $replacedKeyword);
            //先頭に「+」を加えて戻す
            return Str::start($optimizeKeyword, $operator);
          }
      } else {
          return Str::start($keyword->kw, $operator);
      }        
    }

    public function assignEmploymentType($emp){
      $empWord = [];
      foreach ($emp as $key => $val){
        switch ($val) {
          case "1" :
            $empWord[] = "正社員";
            break;

          case "2" :
            $empWord[] = "一般派遣";
            break;

          case "3" :
            $empWord[] = "紹介予定派遣";
            break;

          case "4" :
            $empWord[] = "パート・アルバイト";
            break;

          case "5" :
            $empWord[] = "業務委託";
            break;

          case "6" :
            $empWord[] = "契約社員";
            break;
        }
      }
      return $empWord;
    }

    public function trialGetjobData() {
      $api_jobinfo = new api_jobinfo;
      
      //where文(1)
      $results = $api_jobinfo->where('Id', 'a071000000cLDxTAAW')->get();

      //全て取得
      $results = $card::all();

      //値をjson形式で返す形
      return response()->json([$results], 200, [], JSON_UNESCAPED_UNICODE);
      
      //Viewに値を渡す形
      return view('test.job', ['results' => $results]);
    }
}
