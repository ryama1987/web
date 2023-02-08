	//フォームタイプの設定
	const formTypeList     = ['1_1','1_2','2_1','2_1a','2_2'];
	const birth_year_Elem  = document.getElementById('birth_year');
	const birth_month_Elem = document.getElementById('birth_month');
	const birth_day_Elem   = document.getElementById('birth_day');
	const lic1_Elem        = document.getElementById('licence1');
	const exs1_Elem        = document.getElementById('ex_syokusyu1');
	const targetFormArea   = document.getElementById('form_change_area');
	const targetFormResume = document.getElementById('form_resume_area');
	
	//値があれば設定する項目
	let by_num             = (birth_year_Elem.value !== "") ? birth_year_Elem.value : "";
	let bm_num             = (birth_month_Elem.value !== "") ? birth_month_Elem.value : "";
	let bd_num             = (birth_day_Elem.value !== "") ? birth_day_Elem.value : "";
	let lic1_num           = (lic1_Elem.value !== "") ? lic1_Elem.value : "";
	let exs1_num           = (exs1_Elem.value !== "") ? exs1_Elem.value : "";
	let formExChangeFlg1   = (by_num !== "" && bm_num !== "" && bd_num !== "") ? true : false;
	let formExChangeFlg2   = (lic1_num !== "") ? true : false;

	//変数定義（初期値）
	let formItems     = ""; 
	let formType      = "";
	let lic1_txt      = "";
	let exs1_txt      = "";
	let calcResultAge = "";
	
	if(by_num !== "" && bm_num !== "" && bd_num !== "") {
		calcResultAge = calcForAge(by_num, bm_num, bd_num);
	}
	
	//任意項目の表示・非表示を制御する関数
	function optionAccrodion(elem){
		elem.parentElement.classList.toggle('open');
	}

	//月日の桁揃えをする関数
	function covert2digit(num, dig) {
		let numLength = String(num).length;
		if (dig > numLength) {
			return (new Array((dig - numLength) + 1).join('0')) + num;
		} else {
			return num;
		}
	}

	//年齢算出する関数
	function calcForAge(year,month,day) {
		if(year && month && day){
			let dateNow  = new Date();
			let today    = parseInt('' + dateNow.getFullYear() + covert2digit(dateNow.getMonth() + 1, 2) + covert2digit(dateNow.getDate(), 2));
			let birth    = parseInt('' + year + covert2digit(month, 2) + covert2digit(day, 2));
			return parseInt((today - birth) / 10000);
		} else {
		}
	}
	
	//入力項目の判定に使うformtype値をinputタグのvalueにセットする関数
	function formSendInputDataSet(getFormType){
		let formTargetTypeId = document.getElementById('form_target_type_id');
		formTargetTypeId.value = getFormType;
	}
	
	/*////　NOTE  ///////////////////////////////////////////////
	確認ボタンと同時に削除するとき、
	remove()は、NodeList（静的）で取得しないと機能しない。
	※ HTMLcollection（動的）だと複数回クリックしないとすべて消えなかった。
	//////////////////////////////////////////////////////////*/
	
	//確認画面に遷移する際、非表示となったエリアを削除する関数(NodeList版)
	function deleteForNoSendItem(){
		//以下でまずは全フォーム情報を取得する
		let deleteformItems = document.querySelectorAll('.target_form_object');
		for(let i=0; i<deleteformItems.length; i++){
			//data-formtypeが、空ならば削除対象なのでremove()処理
			if (deleteformItems[i].getAttribute('data-formtype') == ""){
				deleteformItems[i].remove();
			}
		}
	}
	
	//入力項目の出し分け後に、data-formtypeが空欄の箇所を非表示にする関数
	function formDataDisplaySetting(targetItems) {
		/*for(let i=0; i<targetItems.length; i++){
			if(targetItems[i].getAttribute('data-formtype') !== ""){
				targetItems[i].setAttribute('style','display: table;');
			} else {
				targetItems[i].setAttribute('style','display: none;');
			}
		}*/
		for(let i=0; i<targetItems.length; i++){
			if(targetItems[i].getAttribute('data-formtype') !== ""){
				targetItems[i].classList.remove('no_selected_form');
				targetItems[i].classList.add('selected_form');
			} else {
				targetItems[i].classList.remove('selected_form');
				targetItems[i].classList.add('no_selected_form');
			}
		}
	}
	
	//資格と生年月日より、入力項目の出し分けを設定する関数
	function formDataAttrSet(fitems,ftype) {
		for(let i=0; i<fitems.length; i++){
			//新たな変更も考慮して、最初は空にする
			fitems[i].setAttribute('data-formtype','');
			targetFormResume.setAttribute('data-formtype','');
			
			if (ftype == formTypeList[1] || ftype == formTypeList[2] || ftype == formTypeList[3] || ftype == formTypeList[4]){
				if(fitems[i].getAttribute('id') == 'essential_00N10000001D8G5'){
					//学歴
					fitems[i].setAttribute('data-formtype',`${ftype}`);
				}
				if(fitems[i].getAttribute('id') == 'essential_wish_syoku'){
					//希望職種
					fitems[i].setAttribute('data-formtype',`${ftype}`);
				}
				if(fitems[i].getAttribute('id') == 'essential_ex_syokusyu1'){
					//経験職種
				 	fitems[i].setAttribute('data-formtype',`${ftype}`);
				}
			}
			
			if (ftype == formTypeList[0] || ftype == formTypeList[1] || ftype == formTypeList[2] || ftype == formTypeList[3] || ftype == formTypeList[4]){
				if(fitems[i].getAttribute('id') == 'essential_00N10000001D8LZ'){
					//勤務地
				 	fitems[i].setAttribute('data-formtype',`${ftype}`);
				}
			}
			
			if (ftype == formTypeList[0] || ftype == formTypeList[2] || (ftype == formTypeList[4] && (exs1_num == 2 || exs1_num == 3))){
				if(fitems[i].getAttribute('id') == 'essential_interview_location'){
					//面談拠点のご希望
				 	fitems[i].setAttribute('data-formtype',`${ftype}`);
				}
				if(fitems[i].getAttribute('id') == 'essential_interview_type'){
					//面談形式のご希望
				 	fitems[i].setAttribute('data-formtype',`${ftype}`);
				}
				if(fitems[i].getAttribute('id') == 'option_interview_date_1'){
					//第一希望 面談日
				 	fitems[i].setAttribute('data-formtype',`${ftype}`);
				}
				if(fitems[i].getAttribute('id') == 'option_interview_date_2'){
					//第二希望 面談日
				 	fitems[i].setAttribute('data-formtype',`${ftype}`);
				}
				if(fitems[i].getAttribute('id') == 'option_interview_date_3'){
					//第三希望 面談日
				 	fitems[i].setAttribute('data-formtype',`${ftype}`);
				}
				if(fitems[i].getAttribute('id') == 'option_interview_free_comment'){
					//面談に関してのご希望・ご質問
				 	fitems[i].setAttribute('data-formtype',`${ftype}`);
				}
			}
			
			if (ftype == formTypeList[1] || ftype == formTypeList[3] || ftype == formTypeList[4]){
				if(fitems[i].getAttribute('id') == 'essential_00N10000001D8Ge'){
					//経験年数
					fitems[i].setAttribute('data-formtype',`${ftype}`);
				}
				if(fitems[i].getAttribute('id') == 'essential_00N10000001D8Eo'){
					//経験社数
					fitems[i].setAttribute('data-formtype',`${ftype}`);
				}
				if(fitems[i].getAttribute('id') == 'option_00N10000001Dyrl'){
					//英語力
					fitems[i].setAttribute('data-formtype',`${ftype}`);
				}
				if(fitems[i].getAttribute('id') == 'essential_ExperienceManagement__c'){
					//マネジメント経験
					fitems[i].setAttribute('data-formtype',`${ftype}`);
				}
				if(targetFormResume.getAttribute('style') == 'display: block;'){
					//職歴項目
					targetFormResume.setAttribute('data-formtype',`${ftype}`);
					let resumeElements = targetFormResume.getElementsByClassName('target_form_object');
					for(let j=0; j<resumeElements.length; j++){
						resumeElements[j].setAttribute('data-formtype',`${ftype}`);
					}
				}
			}
			
			if (ftype == formTypeList[2]){
				if(fitems[i].getAttribute('id') == 'essential_00N10000001D8Eo'){
					//経験社数
					fitems[i].setAttribute('data-formtype',`${ftype}`);
				}
			}
			
			if (ftype == formTypeList[2] || (ftype == formTypeList[4] && (exs1_num == 2 || exs1_num == 3))){
				if(fitems[i].getAttribute('id') == 'essential_AnnualAccounts__c'){
					//年次決算業務の経験
					fitems[i].setAttribute('data-formtype',`${ftype}`);
				}
			}
		}
		formDataDisplaySetting(fitems);
	}
	
	//年齢と資格でformTypeを判定する関数
	function formJudge(age,lic,exs){
		formItems = document.getElementsByClassName('target_form_object');
		if (age !== "" && lic !== ""){
			let lic_type = "";
			if (lic == 1 || lic == 2 || lic == 4 || lic == 5 || lic == 6 || lic == 11){
				lic_type = 'mt1';
			} else {
				if (lic < 29 || lic == 46){
					lic_type = 'mt2';
				} else {
					lic_type = 'nt';
				}
			}
			
			if (age < 50 && lic_type == 'mt1'){
				formType = formTypeList[0];
				targetFormResume.style.display = 'none';
				formDataAttrSet(formItems,formType);
			} else if (age >= 50 && lic_type == 'mt1') {
				formType = formTypeList[1];
				targetFormResume.style.display = 'block';
				formDataAttrSet(formItems,formType);
			} else if (age < 30 && (lic_type == 'mt2' || exs == 2 || exs == 3) ) {
				formType = formTypeList[2];
				targetFormResume.style.display = 'none';
				formDataAttrSet(formItems,formType);
			} else if (age < 30 && lic_type == 'nt' && (exs !== 2 || exs !== 3) ) {
				formType = formTypeList[3];
				targetFormResume.style.display = 'block';
				formDataAttrSet(formItems,formType);
			} else if (age >= 30 && lic_type == 'nt' || lic_type == 'mt2') {
				formType = formTypeList[4];
				targetFormResume.style.display = 'block';
				formDataAttrSet(formItems,formType);
			}
			targetFormArea.style.display = 'block';
			formSendInputDataSet(formType);
		} else {
		}
	}

	//西暦 year
	birth_year_Elem.addEventListener('change', function(e) {
		by_num = e.target.value;
		formExChangeFlg1 = (by_num !== "" && bm_num !== "" && bd_num !== "") ? true : false;
		calcResultAge = calcForAge(by_num, bm_num, bd_num);
		if (formExChangeFlg1 && formExChangeFlg2){
			formJudge(calcResultAge,lic1_num,exs1_num);
		}
	},false);

	//月 month
	birth_month.addEventListener('change', function(e) {
		bm_num = e.target.value;
		formExChangeFlg1 = (by_num !== "" && bm_num !== "" && bd_num !== "") ? true : false;
		calcResultAge = calcForAge(by_num, bm_num, bd_num);
		if (formExChangeFlg1 && formExChangeFlg2){
			formJudge(calcResultAge,lic1_num,exs1_num);
		}
	},false);

	//日 day
	birth_day_Elem.addEventListener('change', function(e) {
		bd_num = e.target.value;
		formExChangeFlg1 = (by_num !== "" && bm_num !== "" && bd_num !== "") ? true : false;
		calcResultAge = calcForAge(by_num, bm_num, bd_num);
		if (formExChangeFlg1 && formExChangeFlg2){
			formJudge(calcResultAge,lic1_num,exs1_num);
		}
	},false);

	//Licence1　値取得
	lic1_Elem.addEventListener('change', function(e) {
		const lic1_options = this.getElementsByTagName('option');
		lic1_num = e.target.value;
		if (lic1_num == "なし") {
			lic1_num = 999;
		}
		const lic1_index = e.target.selectedIndex;
		lic1_txt = lic1_options[lic1_index].innerText;
		
		formExChangeFlg2 = true;
		
		calcResultAge = calcForAge(by_num, bm_num, bd_num);
		
		if (formExChangeFlg1 && formExChangeFlg2){
			formJudge(calcResultAge,lic1_num,exs1_num);
		}
	},false);
	
	//経験職種が「経理・財務」を選択した場合の処理
	exs1_Elem.addEventListener('change', function(e) {
		
		const exs1_options = this.getElementsByTagName('option');
		exs1_num = e.target.value;
		
		const exs1_index = e.target.selectedIndex;
		exs1_txt = exs1_options[exs1_index].innerText;
		
		if (formExChangeFlg1 && formExChangeFlg2){
			formJudge(calcResultAge,lic1_num,exs1_num);
		}
	},false);
	
	//ページ読み込み時にフォームの切り替え判定をするフラグの値を設定する
	window.addEventListener('DOMContentLoaded',function(){
		by_num             = (birth_year_Elem.value !== "") ? birth_year_Elem.value : "";
		bm_num             = (birth_month_Elem.value !== "") ? birth_month_Elem.value : "";
		bd_num             = (birth_day_Elem.value !== "") ? birth_day_Elem.value : "";
		lic1_num           = (lic1_Elem.value !== "") ? lic1_Elem.value : "";
		exs1_num           = (exs1_Elem.value !== "") ? exs1_Elem.value : "";
		formExChangeFlg1   = (by_num !== "" && bm_num !== "" && bd_num !== "") ? true : false;
		formExChangeFlg2   = (lic1_num !== "") ? true : false;
		
		if (formExChangeFlg1 && formExChangeFlg2){
			formJudge(calcResultAge,lic1_num,exs1_num);
		}
	},false);
    