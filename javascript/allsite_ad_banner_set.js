//画面サイズを取得
const mediaQuery  = window.matchMedia('(max-width: 768px)');

//現在のURL取得
let hostName      = location.hostname;
let urlPathName   = location.pathname;

//区切り文字を指定しURLを分解
urlPathName       = urlPathName.split('/');
hostName          = hostName.split('.');

//初期値
let image_name          = "";
let targetId            = "";
let bannerDisplayFlg    = "";

//右下フローティングのcss
let cssForFloatingBanner = `
<style type="text/css">
#floating-bnr--advertise {
    display: block;
    max-width: 350px;
    height: auto;
    position: fixed;
    bottom: 30px;
    right: 30px;
    border-radius: 10px;
    z-index: 999;
	padding: 1rem;
	background: #f9f9f9;
    box-shadow: 1px 1px 3px #dedede;
}

#floating-bnr--advertise .ad-bnr--block:hover {
    opacity: .95;
    cursor: pointer
}
#floating-bnr--advertise .ad-bnr--block a:hover {
    opacity: .95;
    cursor: pointer
}
#floating-bnr--advertise .ad-bnr--block a img {
    max-width: 100%;
    width: auto;
    margin: 0
}
#floating-bnr--advertise .close-btn--block {
    position: absolute;
    top: -20px;
    right: -10px
}
#floating-bnr--advertise .close-btn--block a>img {
    width: 40px;
    height: 100%;
    background: 0;
    cursor: pointer
}
.bnr-close {
    display: none!important
}
div#go-top {
    display: none!important
}
.bg-type1 {
	background: #00255c !important;
}
.bg-type2 {
	background: #00255c;
}
@media screen and (max-width:768px) {
    #floating-bnr--advertise {
        width: calc(100% - 50vw)
    }
}
@media screen and (max-width:640px) {
    #floating-bnr--advertise {
        max-width: 100vw;
        width: 95%;
		bottom: 2%;
		right: 0;
		left: 0;
		margin: 0 auto;
    }
    #floating-bnr--advertise .close-btn--block {
        top: -15px;
        right: 0px
    }
    #floating-bnr--advertise .close-btn--block a>img {
        width: 45px;
        height: 100%
    }
}
</style>
`;

// PCとSPでファイル名を設定
function valueSetting(img_name, dir, hname){
	if (hname[0] == 'career' || hname[0] == 'testcareer'){
		if (dir[1] == ''){
			img_name = mediaQuery.matches ? 'side_bnr_aptitude_for_keiri_sp' : 'side_bnr_aptitude_for_keiri_pc' ;
			targetId   = 'floating-bnr--advertise';
			bannerDisplayFlg = false;
		} else if (dir[1] == 'keiri' && dir[2] == 'basic') {
			img_name = mediaQuery.matches ? 'side_bnr_aptitude_for_keiri_sp' : 'side_bnr_aptitude_for_keiri_pc' ;
			targetId   = 'floating-bnr--advertise';
			bannerDisplayFlg = true;
		} else if (dir[1] == 'event'){
			img_name = mediaQuery.matches ? 'banner_sp' : 'banner' ;
			targetId   = 'jc-ad-banner';
			bannerDisplayFlg = true;
		} else if (dir[1] == 'kansai'){
			img_name = mediaQuery.matches ? 'side_bnr_consulting_firm_sp' : 'side_bnr_consulting_firm' ;
			targetId   = 'floating-bnr--advertise';
			bannerDisplayFlg = false;
		} else if (dir[1] == 'resume' && dir[2] == 'sample' && dir[3] == 'foreign_writing'){
			img_name = mediaQuery.matches ? 'side_bnr_poe_cam01_sp' : 'side_bnr_poe_cam01_pc';
			targetId   = 'floating-bnr--advertise';
			bannerDisplayFlg = true;
		} else if (dir[1] == 'rarejob' && dir[2] == 'report150901.php'){
			img_name = mediaQuery.matches ? 'side_bnr_poe_cam01_sp' : 'side_bnr_poe_cam01_pc';
			targetId   = 'floating-bnr--advertise';
			bannerDisplayFlg = true;
		} else {
			img_name = mediaQuery.matches ? 'side_bnr_annual_sp02' : 'side_bnr_annual_pc02' ;
			targetId   = 'floating-bnr--advertise';
			bannerDisplayFlg = false;
		}
		bannerSetting(img_name, dir, targetId, bannerDisplayFlg, hname);
	} else if (hname[0] == 'staff' || hname[0] == 'teststaff'){
		if (dir[1] == ''){
			img_name = mediaQuery.matches ? 'side_bnr_aptitude_for_keiri_sp' : 'side_bnr_aptitude_for_keiri_pc' ;
			targetId   = 'floating-bnr--advertise';
			bannerDisplayFlg = false;
		} else if (dir[1] == 'accounting_lan.php') {
			img_name = mediaQuery.matches ? 'side_bnr_poe_cam01_sp' : 'side_bnr_poe_cam01_pc' ;
			targetId   = 'floating-bnr--advertise';
			bannerDisplayFlg = true;
		} else if (dir[1] == 'voice' && dir[2] == 'interview' && dir[3] == 'english') {
			img_name = mediaQuery.matches ? 'side_bnr_poe_cam01_sp' : 'side_bnr_poe_cam01_pc' ;
			targetId   = 'floating-bnr--advertise';
			bannerDisplayFlg = true;
		} else if (dir[1] == 'knowledge' && (dir[2] == 'uscpa.php' || dir[2] == 'batic.php' || dir[2] == 'batic_skillup.php')) {
			img_name = mediaQuery.matches ? 'side_bnr_poe_cam01_sp' : 'side_bnr_poe_cam01_pc' ;
			targetId   = 'floating-bnr--advertise';
			bannerDisplayFlg = true;
		} else if (dir[1] == 'column2' && dir[2] == 'english-mail-accounting') {
			img_name = mediaQuery.matches ? 'side_bnr_poe_cam01_sp' : 'side_bnr_poe_cam01_pc' ;
			targetId   = 'floating-bnr--advertise';
			bannerDisplayFlg = true;
		}
		bannerSetting(img_name, dir, targetId, bannerDisplayFlg, hname);
	}
}

//バナーを表示する関数
function bannerSetting(img_name, dir, htmlid, dflg, dname) {
	let adBannerHtml= document.getElementById(`${htmlid}`) || "";
	if (dflg && (dname[0] == 'career' || dname[0] == 'testcareer')){
		if (adBannerHtml !== "" && dir[1] == '' ){
			adBannerHtml.innerHTML = cssForFloatingBanner + `
			<div class="ad-bnr--block">
				<a id="ad-link-main-cc" href="/keiri/aptitude/" data-btn-id="経理の働き方診断サイドバナー" target="_blank">
					<img src="https://img.jusnet.co.jp/career/banner/${img_name}.jpg" style="width:100%;" loading="lazy" alt="働き方診断">
				</a>
			</div>
			<div id="close-btn--block" class="close-btn--block">
				<a id="closeBtn" data-btn-id="バナークローズ（経理の働き方診断）">
					<img src="https://img.jusnet.co.jp/career/banner/floatingbnr_close_btn.png" alt="">
				</a>
			</div>
			`;
			ifBannerCloseClick();
		} else if (adBannerHtml !== "" && dir[1] == 'keiri' && dir[2] == 'basic'){
			adBannerHtml.innerHTML = cssForFloatingBanner + `
			<div class="ad-bnr--block">
				<a id="ad-link-main-cc" href="/keiri/aptitude/" data-btn-id="経理の働き方診断サイドバナー" target="_blank">
					<img src="https://img.jusnet.co.jp/career/banner/${img_name}.jpg" style="width:100%;" loading="lazy" alt="働き方診断">
				</a>
			</div>
			<div id="close-btn--block" class="close-btn--block">
				<a id="closeBtn" data-btn-id="バナークローズ（経理の働き方診断）">
					<img src="https://img.jusnet.co.jp/career/banner/floatingbnr_close_btn.png" alt="">
				</a>
			</div>
			`;
			ifBannerCloseClick();
		} else if (adBannerHtml !== "" && dir[1] == 'event' ){
			adBannerHtml.innerHTML = `
				<a id="seminar_click_ad01" href="/event/finance/" target="_blank" data-btn-id="セミナー一覧クリック_バナー画像_20代ビジネスパーソンのための会計・ファイナンス講座">
					<figure class="mt50 mb50">
						<img src="/event/finance/images/${img_name}.jpg" alt="20代ビジネスパーソンのための会計・ファイナンス講座" style="width:100%;">
					</figure>
				</a>
			`;
		} else if (adBannerHtml !== "" && dir[1] == 'kansai' ){
			adBannerHtml.innerHTML = cssForFloatingBanner + `
			<div class="ad-bnr--block">
				<a id="ad-link-main-cc" href="/entry/consulting_firm/" data-btn-id="コンサルティングファーム_サイドバナー" target="_blank">
					<img src="https://img.jusnet.co.jp/career/banner/${img_name}.jpg" style="width:100%;" loading="lazy" alt="20代ビジネスパーソンのための会計・ファイナンス講座">
				</a>
			</div>
			<div id="close-btn--block" class="close-btn--block">
				<a id="closeBtn" data-btn-id="バナークローズ（コンサルティングファーム）">
					<img src="https://img.jusnet.co.jp/career/banner/floatingbnr_close_btn.png" alt="">
				</a>
			</div>
			`;
			ifBannerCloseClick();
		} else if (adBannerHtml !== "" && dir[1] == 'resume' && dir[2] == 'sample' && dir[3] == 'foreign_writing' ){
			adBannerHtml.innerHTML = cssForFloatingBanner + `
			<div class="ad-bnr--block">
				<a id="ad-link-main-cc" href="/entry/poe_cp/" data-btn-id="Pearson Online Englishキャンペーンバナー" target="_blank">
					<img src="https://img.jusnet.co.jp/career/banner/${img_name}.jpg" style="width:100%;" loading="lazy" alt="世界400万人が受講する「オンライン英語プログラム」の受講料が3カ月無料になるチャンス！応募期間：2021年12月16日～2022年1月16日 まで">
				</a>
			</div>
			<div id="close-btn--block" class="close-btn--block">
				<a id="closeBtn" data-btn-id="バナークローズ（経理の働き方診断）">
					<img src="https://img.jusnet.co.jp/career/banner/floatingbnr_close_btn.png" alt="">
				</a>
			</div>
			`;
			ifBannerCloseClick();
		} else if (adBannerHtml !== "" && dir[1] == 'rarejob' && dir[2] == 'report150901.php'){
			adBannerHtml.innerHTML = cssForFloatingBanner + `
			<div class="ad-bnr--block">
				<a id="ad-link-main-cc" href="/entry/poe_cp/" data-btn-id="Pearson Online Englishキャンペーンバナー" target="_blank">
					<img src="https://img.jusnet.co.jp/career/banner/${img_name}.jpg" style="width:100%;" loading="lazy" alt="世界400万人が受講する「オンライン英語プログラム」の受講料が3カ月無料になるチャンス！応募期間：2021年12月16日～2022年1月16日 まで">
				</a>
			</div>
			<div id="close-btn--block" class="close-btn--block">
				<a id="closeBtn" data-btn-id="バナークローズ（経理の働き方診断）">
					<img src="https://img.jusnet.co.jp/career/banner/floatingbnr_close_btn.png" alt="">
				</a>
			</div>
			`;
			ifBannerCloseClick();
		} else {
			adBannerHtml.innerHTML = cssForFloatingBanner + `
			<div class="ad-bnr--block">
				<a id="ad-link-main-cc" href="/annual/" data-btn-id="年収診断サイドバナー" target="_blank">
					<img src="https://img.jusnet.co.jp/career/banner/${img_name}.png" loading="lazy" alt="年収診断">
				</a>
			</div>
			<div id="close-btn--block" class="close-btn--block">
				<a id="closeBtn" data-btn-id="バナークローズ（年収診断）">
					<img src="https://img.jusnet.co.jp/career/banner/floatingbnr_close_btn.png" loading="lazy" alt="">
				</a>
			</div>
			`;
			ifBannerCloseClick();
		}
	} else if (dflg && (dname[0] == 'staff' || dname[0] == 'teststaff')){
		if (adBannerHtml !== "" && dir[1] == ''){
			adBannerHtml.innerHTML = cssForFloatingBanner + `
			<div class="ad-bnr--block">
				<a id="ad-link-main-sa" href="https://career.jusnet.co.jp/keiri/aptitude/" data-btn-id="経理の働き方診断サイドバナー" target="_blank">
					<img src="https://img.jusnet.co.jp/career/banner/${img_name}.jpg" style="width:100%;" loading="lazy" alt="働き方診断">
				</a>
			</div>
			<div id="close-btn--block" class="close-btn--block">
				<a id="closeBtn" data-btn-id="バナークローズ（経理の働き方診断）">
					<img src="https://img.jusnet.co.jp/career/banner/floatingbnr_close_btn.png" alt="">
				</a>
			</div>
			`;
			ifBannerCloseClick();
		} else if (adBannerHtml !== "" && dir[1] == 'accounting_lan.php'){
			adBannerHtml.innerHTML = cssForFloatingBanner + `
			<div class="ad-bnr--block">
				<a id="ad-link-main-sa" href="https://career.jusnet.co.jp/entry/poe_cp/" data-btn-id="Pearson Online Englishキャンペーンバナー" target="_blank">
					<img src="https://img.jusnet.co.jp/career/banner/${img_name}.jpg" style="width:100%;" loading="lazy" alt="世界400万人が受講する「オンライン英語プログラム」の受講料が3カ月無料になるチャンス！応募期間：2021年12月16日～2022年1月16日 まで">
				</a>
			</div>
			<div id="close-btn--block" class="close-btn--block">
				<a id="closeBtn" data-btn-id="バナークローズ（経理の働き方診断）">
					<img src="https://img.jusnet.co.jp/career/banner/floatingbnr_close_btn.png" alt="">
				</a>
			</div>
			`;
			ifBannerCloseClick();
		} else if (adBannerHtml !== "" && dir[1] == 'voice' && dir[2] == 'interview' && dir[3] == 'english'){
			adBannerHtml.innerHTML = cssForFloatingBanner + `
			<div class="ad-bnr--block">
				<a id="ad-link-main-sa" href="https://career.jusnet.co.jp/entry/poe_cp/" data-btn-id="Pearson Online Englishキャンペーンバナー" target="_blank">
					<img src="https://img.jusnet.co.jp/career/banner/${img_name}.jpg" style="width:100%;" loading="lazy" alt="世界400万人が受講する「オンライン英語プログラム」の受講料が3カ月無料になるチャンス！応募期間：2021年12月16日～2022年1月16日 まで">
				</a>
			</div>
			<div id="close-btn--block" class="close-btn--block">
				<a id="closeBtn" data-btn-id="バナークローズ（経理の働き方診断）">
					<img src="https://img.jusnet.co.jp/career/banner/floatingbnr_close_btn.png" alt="">
				</a>
			</div>
			`;
			ifBannerCloseClick();
		} else if (adBannerHtml !== "" && dir[1] == 'knowledge' && (dir[2] == 'uscpa.php' || dir[2] == 'batic.php' || dir[2] == 'batic_skillup.php')){
			adBannerHtml.innerHTML = cssForFloatingBanner + `
			<div class="ad-bnr--block">
				<a id="ad-link-main-sa" href="https://career.jusnet.co.jp/entry/poe_cp/" data-btn-id="Pearson Online Englishキャンペーンバナー" target="_blank">
					<img src="https://img.jusnet.co.jp/career/banner/${img_name}.jpg" style="width:100%;" loading="lazy" alt="世界400万人が受講する「オンライン英語プログラム」の受講料が3カ月無料になるチャンス！応募期間：2021年12月16日～2022年1月16日 まで">
				</a>
			</div>
			<div id="close-btn--block" class="close-btn--block">
				<a id="closeBtn" data-btn-id="バナークローズ（経理の働き方診断）">
					<img src="https://img.jusnet.co.jp/career/banner/floatingbnr_close_btn.png" alt="">
				</a>
			</div>
			`;
			ifBannerCloseClick();
		} else if (adBannerHtml !== "" && dir[1] == 'column2' && dir[2] == 'english-mail-accounting'){
			adBannerHtml.innerHTML = cssForFloatingBanner + `
			<div class="ad-bnr--block">
				<a id="ad-link-main-sa" href="https://career.jusnet.co.jp/entry/poe_cp/" data-btn-id="Pearson Online Englishキャンペーンバナー" target="_blank">
					<img src="https://img.jusnet.co.jp/career/banner/${img_name}.jpg" style="width:100%;" loading="lazy" alt="世界400万人が受講する「オンライン英語プログラム」の受講料が3カ月無料になるチャンス！応募期間：2021年12月16日～2022年1月16日 まで">
				</a>
			</div>
			<div id="close-btn--block" class="close-btn--block">
				<a id="closeBtn" data-btn-id="バナークローズ（経理の働き方診断）">
					<img src="https://img.jusnet.co.jp/career/banner/floatingbnr_close_btn.png" alt="">
				</a>
			</div>
			`;
			ifBannerCloseClick();
		} else {
		}
	}
}

//バナークローズの関数
function ifBannerCloseClick() {
	let ad_closebtn = document.getElementById('close-btn--block');
	ad_closebtn.addEventListener('click',function(){
		ad_closebtn.parentNode.classList.add('bnr-close');
	},false);
}

//ページ読み込み時に発動
window.addEventListener('DOMContentLoaded', function(){
	valueSetting(image_name, urlPathName, hostName);
},false);

//画面サイズが変わった場合の発動
mediaQuery.addEventListener('change', function(){
	valueSetting(image_name, urlPathName, hostName);
},false);

let ad_SideBanner = document.getElementById('floating-bnr--advertise');
ad_SideBanner.animate([{opacity: '0'}, {opacity: '1'}], 1000);

