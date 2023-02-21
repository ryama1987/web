<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class FullTextIndexController extends Controller
{
    public function getjobData(Request $request) {
        //検索ワードを調整
        $param = $this->optimizeQueryWord($request);

        $results = DB::table('api_jobinfo')
        ->select('Id','Name','Catch__c','Prefecture1__c','City1__c')
        ->whereFulltext(['Catch__c', 'BusinessContents__c', 'Qualification__c', 'ApplyRequirement__c', 'Requirement__c', 'Prefecture1__c', 'City1__c'],
        $param ,['mode' => 'boolean'])
        ->get();

        return response()->json([$results], 200, [], JSON_UNESCAPED_UNICODE);
      }

      public function optimizeQueryWord($keyword){
        //全角スペースを半角に置き換え
        $replacedKeyword = Str::replace('　', ' ', $keyword->kw);

        //キーワード数をカウント
        $keywordCount = Str::of($replacedKeyword)->explode(' ');

        if(count($keywordCount) > 1){
            //「 」(半角スペース)を「 +」（プラス半角スペース）に置き換え
            $optimizeKeyword = Str::replace(' ', ' +', $replacedKeyword);

            //先頭に「+」を加えて戻す
            return Str::start($optimizeKeyword, '+');
        } else {
            return Str::start($keyword->kw, '+');
        }        
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
