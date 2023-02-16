<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>検索フォーム</title>

    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="text-lg">
<div class="grid grid-cols-1 h-screen">
    <div class="w-full lg:m-0 p-6 lg:px-4 lg:px-0 grid grid-cols-1 justify-items-center bg-gray-100 h-auto">
        <div class="w-full p-6 max-w-2xl lg:p-12 lg:mx-6 bg-white">
            <div class="text-center">
				<img src="https://img.jusnet.co.jp/common/jusnet_logo_long.svg" alt=""
						width="300" height="69" class="block w-80 mx-auto">
					<h4 class="text-md font-semibold mt-1 mb-12 pb-1">検索フォーム</h4>
            </div>
			<form method="GET" action="#" class="flex flex-row gap-5" onsubmit="return false;">
				<input id="input_search" type="search" name="kw" value="" class="block w-full p-4 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 sm:text-md bg-white" autocomplete="off" spellcheck="false" role="combobox" placeholder="例：東京都　公認会計士" aria-live="polite" >
				<button id="submit_btn" type="button" class="block w-64 bg-blue-600 text-white rounded-lg">検索する</button>
			</form>
			<div class="my-10 p-5 border">
				<h4 class="my-b">検索結果<span id="job_count_number"> : 0件</span></h4>
				<div id="result_data" data-count="0">
					<p>まだ検索していません。</p>
				</div>
			</div>
        </div>
    </div>
</div>

<script>
 let searchCount = 0;
 let job_count_number = 0;
 let searchInput = document.getElementById('input_search');
 let searchButton = document.getElementById('submit_btn');
 
 searchInput.addEventListener('change', getSearchApiData);
 searchButton.addEventListener('click', getSearchApiData);
 
 //検索APIから値を取得する関数
 async function getSearchApiData(e){
	let data = document.getElementById('input_search').value;
	let targetDom = document.getElementById('result_data');
	fetch(`https://search-api-staging.jusnet.co.jp/search/bool?emp[]=2&emp[]=3&type=json&kw=${data}`)
	.then( response => response.json() )
	.then( data  => createResultHtml(data,targetDom) )
	.then( data  => postSearchData(data) );
 }

  //検索キーワードを保存
  async function postSearchData(){	
	let data = document.getElementById('input_search').value;
	let postData = {
		headers: {
			"Content-type": "application/json; charset=UTF-8",
		},
		method: "POST",
		body: JSON.stringify({
			"keyword": data
		})
	};
	fetch('https://search-api-staging.jusnet.co.jp/api/search/insert', postData)
	//.then( response => response.json() );
	//.then( json  => console.log(json) );
	}

 //検索結果を表示する関数
 async function createResultHtml(json,target){
	
	//検索結果の件数を表示
	job_count_number = json[0].length;
	document.getElementById('job_count_number').innerHTML = " : " + job_count_number + "件";

	//初期化
	initializeHtml();

	//JSONデータをオブジェクト化して展開
	Object.keys(json).forEach((key) => {
		json[key].forEach( obj => {
			let reslut_dom = `
			<div class="bg-gray-50 my-5 p-5">
			<p class="mt-5">${obj.Catch__c}</p>
			<p><a class="block w-auto text-blue-600 hover:opacity-70" href="https://career.jusnet.co.jp/search/detail.php?kno=${obj.Name}">【${obj.Prefecture1__c}/${obj.City1__c}】${obj.Name} の詳細を見る</a></p>
			</div>
			`;
			target.insertAdjacentHTML('beforeend',reslut_dom);
		});
	});
	searchCount += 1;
	target.dataset.count = searchCount;
 }

 //検索結果を初期化する関数
 async function initializeHtml(){
	let resultData = document.getElementById('result_data');
	resultData.innerHTML = '';
 }
</script>

</body>
</html>
