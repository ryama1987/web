<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>履歴書 作成</title>
		<link href="/resume/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="/resume/jquery-ui/jquery-ui.min.css">
		<link rel="stylesheet" href="/resume/jquery-ui/jquery-ui.structure.min.css">
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

		{{-- 後で別ファイルに移行 --}}
		<style type="text/css">

			input[type=number]::-webkit-inner-spin-button,
			input[type=number]::-webkit-outer-spin-button {
				-webkit-appearance: none;
				margin: 0;
			}

			input[type="number"] {
				-moz-appearance:textfield;
			}

			.edu_alert, .career_alert, .licence_alert {
				color: red;
			}
			.edu_history_span, .career_history_span, .licence_history_span {
				display: flex;
				justify-content: space-between;
				aligh-itmes: center;
			}

			.edu_history_display, .career_history_display, .licence_history_display {
				margin-left: 25px;
				margin-right: 20px;
				cursor: text;
				text-align: left;
				text-decoration: underline;
				flex-grow: 1;
			}

			.sort-handle {
				font-size:18px;
				cursor: move;
			}

			@keyframes Flash{
				50%{
					box-shadow: 0 1px 15px 1px gray;
				}
			}

			.del-edu-history, .del-career-history, .del-licence-history {
				font-size:18px;
				cursor: pointer;
			}

			.add-history {
				cursor: pointer;
			}

			.dialog-btn-delete {
				color: red;
			}
		</style>
	</head>
	<body>

		{{-- li削除用ダイアログ --}}
		<div id="del-history-dialog" title="削除" style="display:none">
		</div>

		<div class="container">
			<img src="https://img.jusnet.co.jp/corporate/entry/assets/jusnet_logo.gif" >
			<hr>
			<h1 class="text-center">履歴書 作成ツール</h1>
			<div class="alert alert-info" role="alert">
				<ul>
					<li>
						本ツールはInternet ExplorerやEdge等のマイクロソフト製のブラウザには対応しておりません。<br>ご利用の際はGoogle Chrome, Firefox, Safari等のブラウザをお使いください。
					</li>
					<br>
					<li>
						<span class="glyphicon glyphicon-alert" ></span>は未入力の項目に表示されます。<br>
						無視してWordファイルを出力することはできますが、空白になります。
					</li>
					<br>
					<li>
						住所や学歴、職歴などの最初に表示される情報は、弊社にご登録いただいているものが表示されます。<br>
						これらの登録情報を変更する場合は、こちらの<a href="https://www.jusnet.co.jp/authenticate/mypage/login.php" class="alert-link" target="_blank">マイページ プロフィール更新ページ</a>から変更をお願いいたします。
					</li>
				</ul>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading"><b>基本情報</b></div>
				<div class="panel-body">
					<div class="form-group">
						<label>履歴書作成日<br><span class="glyphicon glyphicon-info-sign" ></span> 日付を未入力にすると空白になります</label>
						<div class="form-inline">
							<div class="input-group">
								<input name="basic_edit_year" id="basic_edit-year" form="main-form" type="number" class="form-control input-lg" placeholder="" maxlength="5" value="{{ $data['basic_edit_year'] }}" >
								<span class="input-group-addon"><b>年</b></span>
							</div>
							<div class="input-group">
								<input name="basic_edit_month" id="basic_edit-month" form="main-form" type="number" class="form-control input-lg" placeholder="" maxlength="5" value="{{ $data['basic_edit_month'] }}" >
								<span class="input-group-addon"><b>月</b></span>
							</div>
							<div class="input-group">
								<input name="basic_edit_date" id="basic_edit-date" form="main-form" type="number" class="form-control input-lg" placeholder="" maxlength="5" value="{{ $data['basic_edit_date'] }}" >
								<span class="input-group-addon"><b>日</b></span>
							</div>
						</div>
					</div>
					<div class="input-group">
						<label>氏名</label>
						<input name="basic_full_name" id="basic_full_name" form="main-form" type="text" class="form-control input-lg" placeholder="" value="{{ $data['basic_full_name'] }}" readonly>
					</div>
					<br>
					<div class="input-group">
						<label>氏名（ふりがな）</label>
						<input name="basic_name_kana" id="basic_name_kana" form="main-form" type="text" class="form-control input-lg" placeholder="" value="{{ $data['basic_name_kana'] }}" readonly>
					</div>
					<br>
					<div class="input-group">
						<label>性別</label>
						<input name="basic_gender" id="basic_gender" form="main-form" type="text" class="form-control input-lg" placeholder="" value="{{ $data['basic_gender'] }}" readonly>
					</div>
					<br>
					<div class="input-group">
						<label>生年月日</label>
						<input name="basic_display_birthday" id="basic_display_birthday" type="text" class="form-control input-lg" placeholder="" value="{{ $data['basic_display_birthday'] }}" readonly>
						<input name="basic_birth_year" id="basic_birth_year" form="main-form" type="hidden" value="{{ $data['basic_birth_year'] }}">
						<input name="basic_birth_month" id="basic_birth_month" form="main-form" type="hidden" value="{{ $data['basic_birth_month'] }}">
						<input name="basic_birth_date" id="basic_birth_date" form="main-form" type="hidden" value="{{ $data['basic_birth_date'] }}">
					</div>
					<br>
					<div class="input-group">
						<label>電話番号 1</label>
						<input name="basic_phone1" id="basic_phone1" form="main-form" type="text" class="form-control input-lg" placeholder="" value="{{ $data['basic_phone1'] }}" readonly>
					</div>
					<br>
					<div class="form-group">
						<label>電話番号 2</label>
						<div class="form-inline">
							<div class="input-group">
								<input name="basic_phone2_1" id="basic_phone2_1" form="main-form" type="tel" class="form-control input-lg" placeholder="" maxlength="5" value="" >
								<span class="input-group-addon"><b>-</b></span>
							</div>
							<div class="input-group">
								<input name="basic_phone2_2" id="basic_phone2_2" form="main-form" type="tel" class="form-control input-lg" placeholder="" maxlength="5" value="" >
								<span class="input-group-addon"><b>-</b></span>
							</div>
							<div class="input-group">
								<input name="basic_phone2_3" id="basic_phone2_3" form="main-form" type="tel" class="form-control input-lg" placeholder="" maxlength="5" value="" >
							</div>
						</div>
					</div>
					<div class="input-group">
						<label>E-mailアドレス</label>
						<input name="basic_email" id="basic_email" form="main-form" type="text" class="form-control input-lg" placeholder="" value="{{ $data['basic_email'] }}" readonly>
					</div>
					<br>
					<div class="input-group">
						<label>現住所 郵便番号</label>
						<input name="basic_postal" id="basic_postal" form="main-form" type="text" class="form-control input-lg" placeholder="" value="@if(isset($data['basic_postal'])) {{ $data['basic_postal'] }} @endif" readonly>
					</div>
					<br>
					<div class="input-group">
						<label>現住所</label>
						<input name="basic_address" id="basic_address" form="main-form" type="text" class="form-control input-lg" placeholder="" value="{{ $data['basic_address'] }}" readonly>
					</div>
					<br>
					<div class="input-group">
						<label>現住所（ふりがな）</label>
						<input name="basic_address_kana" id="basic_address_kana" form="main-form" type="text" class="form-control input-lg" max-length="100" placeholder="" value="" >
					</div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">
					<b>連絡先</b><br>
					<input name="contact_toggle_chk" id="contact_toggle_chk" type="checkbox" value="" class="contact-toggle-chk" form="main-form" checked="checked">
					<label>現住所と同じ</label>
				</div>
				<div id="contact-acordion" class="panel-collapse collapse">
					<div class="panel-body">
						<div class="form-group">
							<label class="control-label">連絡先 郵便番号</label>
							<div class="form-inline">
							<input name="contact_postal_1" id="contact_postal_1" form="main-form" type="number" class="form-control input-lg" placeholder="" maxlength="3" value="" >
							<label>-</label>
							<input name="contact_postal_2" id="contact_postal_2" form="main-form" type="number" class="form-control input-lg" placeholder="" maxlength="4" value="" >
							</div>
						</div>
						<div class="input-group">
							<label>連絡先 住所</label>
							<input name="contact_address" id="contact_address" form="main-form" type="text" class="form-control input-lg" placeholder="" maxlength="100" value="" >
						</div>
						<br>
						<div class="input-group">
							<label>連絡先 住所（ふりがな）</label>
							<input name="contact_address_kana" id="contact_address_kana" form="main-form" type="text" class="form-control input-lg" placeholder="" maxlength="100" value="" >
						</div>
						<br>
						<div class="form-group">
							<label>連絡先 電話番号 1</label>
							<div class="form-inline">
								<div class="input-group">
									<input name="contact_phone1_1" id="contact_phone1_1" form="main-form" type="tel" class="form-control input-lg" placeholder="" maxlength="5" value="" >
									<span class="input-group-addon"><b>-</b></span>
								</div>
								<div class="input-group">
									<input name="contact_phone1_2" id="contact_phone1_2" form="main-form" type="tel" class="form-control input-lg" placeholder="" maxlength="5" value="" >
									<span class="input-group-addon"><b>-</b></span>
								</div>
								<div class="input-group">
									<input name="contact_phone1_3" id="contact_phone1_3" form="main-form" type="tel" class="form-control input-lg" placeholder="" maxlength="5" value="" >
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>連絡先 電話番号 2</label>
							<div class="form-inline">
								<div class="input-group">
									<input name="contact_phone2_1" id="contact_phone2_1" form="main-form" type="tel" class="form-control input-lg" placeholder="" maxlength="5" value="" >
									<span class="input-group-addon"><b>-</b></span>
								</div>
								<div class="input-group">
									<input name="contact_phone2_2" id="contact_phone2_2" form="main-form" type="tel" class="form-control input-lg" placeholder="" maxlength="5" value="" >
									<span class="input-group-addon"><b>-</b></span>
								</div>
								<div class="input-group">
									<input name="contact_phone2_3" id="contact_phone2_3" form="main-form" type="tel" class="form-control input-lg" placeholder="" maxlength="5" value="" >
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading"><b>学歴</b><br> <span class="glyphicon glyphicon-info-sign" ></span> 学歴・職歴はあわせて22行まで入力できます <br> <span class="glyphicon glyphicon-info-sign" ></span> <span class="glyphicon glyphicon-menu-hamburger"></span>をドラッグすると並べ替えができます</div>
				<div class="panel-body">
					<ul name="edu_history_ul" id="edu_history_ul" class="ui-sortable list-group edu_history_ul">
                        @for ($i=0; $i<21; $i++)
						<li id="edu_history_li[0]" name="edu_history_li[0]" class="list-group-item well-lg edu_history_li" style="display:@if($data['edu_history_toggle'][$i] == '1') block @else none @endif">
							<span class="edu_history_span">
								<span class="del-edu-history glyphicon glyphicon-remove pull-left" aria-hidden="true"></span>
								<span class="@if($data['edu_history_alert'][$i]) edu_alert glyphicon glyphicon-alert @endif edu_history_display">{{ $data['edu_history_display'][$i] }}</span>
								<span class="sort-handle glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span>
								<input name="edu_history_order[]" id="edu_history_order[]" class="edu_history_order" form="main-form" type="hidden" value="{{ $i }}" >
								<input name="edu_history_year[{{$i}}]" id="edu_history_year[{{$i}}]" class="edu_history_year" form="main-form" type="hidden" value="{{ $data['edu_history_year'][$i] }}" >
								<input name="edu_history_month[{{$i}}]" id="edu_history_month[{{$i}}]" class="edu_history_month" form="main-form" type="hidden" value="{{ $data['edu_history_month'][$i] }}" >
								<input name="edu_history_school[{{$i}}]" id="edu_history_school[{{$i}}]" class="edu_history_school" form="main-form" type="hidden" value="{{ $data['edu_history_school'][$i] }}" >
								<input name="edu_history_category[{{$i}}]" id="edu_history_category[{{$i}}]" class="edu_history_category" form="main-form" type="hidden" value="{{ $data['edu_history_category'][$i] }}" >
								<input name="edu_history_toggle[{{$i}}]" id="edu_history_toggle[{{$i}}]" class="edu_history_toggle" form="main-form" type="hidden" value="{{ $data['edu_history_toggle'][$i] }}" >
							</span>
							<span class="list-group-item edu_history_edit" style="display:none;margin-top:20px;">
								<form name="edu_history_form[{{$i}}]" id="edu_history_form[{{$i}}]" class="form-horizontal edu_history_form" method="" action="" >
									<input name="edu_history_id[{{$i}}]" id="edu_history_id[{{$i}}]" type="hidden" value="{{$i}}">
									<div class="form-group" style="margin-top:10px">
										<label class="col-sm-2 control-label" >年</label>
										<div class="col-sm-10">
											<select name="edu_history_year_edit[{{$i}}]" id="edu_history_year_edit[{{$i}}]" form="edu_history_form[{{$i}}]" class="form-control edu_history_year_edit" >

											<option value="" @if ($data['edu_history_year'][$i]== "") selected @endif>未選択</option>
											@foreach ($mtb_data['year'] as $year)
											<option value="{{ $year }}"@if ($data['edu_history_year'][$i] == $year) selected @endif>{{ $year }}年
												{{-- @if ($_mtb_birth_year_wareki[$i]tem){$_mtb_birth_year_wareki'][$item]) @endif --}}</option>
											@endforeach
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label" >月</label>
										<div class="col-sm-10">
											<select name="edu_history_month_edit[{{$i}}]" id="edu_history_month_edit[{{$i}}]" form="edu_history_form[{{$i}}]" class="form-control edu_history_month_edit">
											<option value="" @if(!empty($data['edu_history_month'][$i])) selected @endif>未選択</option>
											@foreach ($mtb_data['mon'] as $mon)
											<option value="{{ $mon }}"@if($data['edu_history_month'][$i] == $mon) selected @endif>{{ $mon }}月</option>
											@endforeach
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">学校名</label>
										<div class="col-sm-10">
											<input name="edu_history_school_edit[{{$i}}]" id="edu_history_school_edit[{{$i}}]" form="edu_history_form[{{$i}}]" class="form-control edu_history_school_edit" type="text" maxlength="50"  value="{{ $data['edu_history_school'][0] }}" >
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-2 control-label" >入学・卒業</label>
										<div class="col-sm-10 btn-group" data-toggle="buttons">
											<label class="btn btn-default @if($data['edu_history_category'][$i]== 1) active @endif">
												<input name="edu_history_category[{{$i}}]" id="edu_history_category[{{$i}}]" form="edu_history_form[{{$i}}]" class="edu_history_category_edit" autocomplete="off" type="radio" value="1" @if ($data['edu_history_category'][$i] == 1) checked @endif>入学
											</label>
											<label class="btn btn-default @if($data['edu_history_category'][$i]== 2) active @endif" >
												<input name="edu_history_category[{{$i}}]" id="edu_history_category[{{$i}}]" form="edu_history_form[{{$i}}]" class="edu_history_category_edit" autocomplete="off" type="radio" value="2" @if ($data['edu_history_category'][$i] == 2) checked @endif>卒業
											</label>
											<label class="btn btn-default @if($data['edu_history_category'][$i]== 3) active @endif" >
												<input name="edu_history_category[{{$i}}]" id="edu_history_category[{{$i}}]" form="edu_history_form[{{$i}}]" class="edu_history_category_edit" autocomplete="off" type="radio" value="3" @if ($data['edu_history_category'][$i] == 3) checked @endif>中退
											</label>
											<label class="btn btn-default @if($data['edu_history_category'][$i]== 4) active @endif" >
												<input name="edu_history_category[{{$i}}]" id="edu_history_category[{{$i}}]" form="edu_history_form[{{$i}}]" class="edu_history_category_edit" autocomplete="off" type="radio" value="4" @if($data['edu_history_category'][$i] == 4) checked @endif>なし
											</label>
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-offset-2 col-sm-10">
											<button form="edu_history_form[{{$i}}]" type="submit" class="btn btn-primary">保存</button>
										</div>
									</div>
								</form>
							</span>
						</li>
						@endfor
					</ul>
				</div>
				<div class="panel-footer">
					<span id="add-edu-history" class="add-history glyphicon glyphicon-plus" aria-hidden="true"> 学歴を追加する</span>
				</div>
			</div>

			<!--  tagcareer  -->
			<div class="panel panel-default">
				<div class="panel-heading"><b>職歴</b><br> <span class="glyphicon glyphicon-info-sign" ></span> 学歴・職歴はあわせて22行まで入力できます <br> <span class="glyphicon glyphicon-info-sign" ></span> <span class="glyphicon glyphicon-menu-hamburger"></span>をドラッグすると並べ替えができます</div>
				<div class="panel-body">
					<ul name="career_history_ul" id="career_history_ul" class="ui-sortable list-group career_history_ul">
						@for ($i=0; $i<22; $i++)
						<li id="career_history_li[{{$i}}]" name="career_history_li[{{$i}}]" class="list-group-item well-lg career_history_li" style="display:@if($data['career_history_toggle'][$i] == 1) block @else none @endif" >
							<span class="career_history_span">
								<span class="del-career-history glyphicon glyphicon-remove pull-left" aria-hidden="true"></span>
								<span class="@if($data['career_history_alert'][$i]) career_alert glyphicon glyphicon-alert @endif career_history_display">{{ $data['career_history_display'][$i] }}</span>
								<span class="sort-handle glyphicon glyphicon-menu-hamburger pull-right" aria-hidden="true"></span>
								<input name="career_history_order[]" id="career_history_order[]" class="career_history_order" form="main-form" type="hidden" value="{{$i}}" >
								<input name="career_history_year[{{$i}}]" id="career_history_year[{{$i}}]" class="career_history_year" form="main-form" type="hidden" value="{{ $data['career_history_year'][$i] }}" >
								<input name="career_history_month[{{$i}}]" id="career_history_month[{{$i}}]" class="career_history_month" form="main-form" type="hidden" value="{{ $data['career_history_month'][$i] }}" >
								<input name="career_history_company[{{$i}}]" id="career_history_company[{{$i}}]" class="career_history_company" form="main-form" type="hidden" value="{{ $data['career_history_company'][$i] }}" >
								<input name="career_history_category[{{$i}}]" id="career_history_category[{{$i}}]" class="career_history_category" form="main-form" type="hidden" value="{{ $data['career_history_category'][$i] }}" >
								<input name="career_history_toggle[{{$i}}]" id="career_history_toggle[{{$i}}]" class="career_history_toggle" form="main-form" type="hidden" value="{{ $data['career_history_toggle'][$i] }}" >
							</span>
							<span class="list-group-item career_history_edit" style="display:none;margin-top:20px;">
								<form name="career_history_form[{{$i}}]" id="career_history_form[{{$i}}]" class="form-horizontal career_history_form" method="" action="" >
									<input name="career_history_id[{{$i}}]" id="career_history_id[{{$i}}]" type="hidden" value="0">
									<div class="form-group" style="margin-top:10px">
										<label class="col-sm-2 control-label" >年</label>
										<div class="col-sm-10">
											<select name="career_history_year_edit[{{$i}}]" id="career_history_year_edit[{{$i}}]" form="career_history_form[{{$i}}]" class="form-control career_history_year_edit" >
											<option value=""@if ($data['career_history_year'][$i] == "") selected @endif>未選択</option>
											@foreach ($mtb_data['year'] as $year)
											<option value="{{ $year }}" @if($data['career_history_year'][$i] == $year) selected @endif>{{ $year }}年
											{{-- @if $_mtb_birth_year_wareki'][$item]}({{ $_mtb_birth_year_wareki'][$item] }}) @endif --}}</option>
											@endforeach
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label" >月</label>
										<div class="col-sm-10">
											<select name="career_history_month_edit[{{$i}}]" id="career_history_month_edit[{{$i}}]" form="career_history_form[{{$i}}]" class="form-control career_history_month_edit">
											<option value="" @if ($data['career_history_month'][$i] == "") selected @endif>未選択</option>
											@foreach ($mtb_data['mon'] as $mon)
											<option value="{{ $mon }}" @if($data['career_history_month'][$i]== $mon) selected @endif>{{ $mon }}月</option>
											@endforeach
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">会社名</label>
										<div class="col-sm-10">
											<input name="career_history_company_edit[{{$i}}]" id="career_history_company_edit[{{$i}}]" form="career_history_form[{{$i}}]" class="form-control career_history_company_edit" type="text" maxlength="50" value="{{ $data['career_history_company'][$i] }}" >
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label" >入社・退職</label>
										<div class="col-sm-10 btn-group" data-toggle="buttons">
											<label class="btn btn-default @if($data['career_history_category'][$i] == 1) active @endif">
												<input name="career_history_category[{{$i}}]" id="career_history_category[{{$i}}]" form="career_history_form[{{$i}}]" class="career_history_category_edit" autocomplete="off" type="radio" value="1" @if ($data['career_history_category'][$i] == 1) checked @endif>入社
											</label>
											<label class="btn btn-default @if($data['career_history_category'][$i] == 2) active @endif">
												<input name="career_history_category[{{$i}}]" id="career_history_category[{{$i}}]" form="career_history_form[{{$i}}]" class="career_history_category_edit" autocomplete="off" type="radio" value="2" @if ($data['career_history_category'][$i] == 2) checked @endif>退職
											</label>
											<label class="btn btn-default @if($data['career_history_category'][$i] == 3) active @endif" >
												<input name="career_history_category[{{$i}}]" id="careecareeristory_category[{{$i}}]" form="career_history_form[{{$i}}]" class="career_history_category_edit" autocomplete="off" type="radio" value="3" @if ($data['career_history_category'][$i] == 3) checked @endif>なし
											</label>
											<label class="btn btn-default @if($data['career_history_category'][$i] == 4) active @endif" >
												<input name="career_history_category[{{$i}}]" id="careecareeristory_category[{{$i}}]" form="career_history_form[{{$i}}]" class="career_history_category_edit" autocomplete="off" type="radio" value="4" @if ($data['career_history_category'][$i] == 3) checked @endif>在職中
											</label>
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-offset-2 col-sm-10">
											<button form="career_history_form[{{$i}}]" type="submit" class="btn btn-primary">保存</button>
										</div>
									</div>
								</form>
							</span>
						</li>
						@endfor
					</ul>
				</div>
				<div class="panel-footer">
					<span id="add-career-history" class="add-history glyphicon glyphicon-plus" aria-hidden="true"> 職歴を追加する</span>
				</div>
			</div>
			<!-- tagcareer -->

			<!--  taglicence  -->
			<div class="panel panel-default">
				<div class="panel-heading"><b>免許・資格</b><br> <span class="glyphicon glyphicon-info-sign" ></span> 免許・資格はあわせて7行まで入力できます <br> <span class="glyphicon glyphicon-info-sign" ></span> <span class="glyphicon glyphicon-menu-hamburger"></span>をドラッグすると並べ替えができます</div>
				<div class="panel-body">
					<ul name="licence_history_ul" id="licence_history_ul" class="ui-sortable list-group licence_history_ul">
						@for ($i=0; $i<7; $i++)
						<li id="licence_history_li[{{$i}}]" name="licence_history_li[{{$i}}]" class="list-group-item well-lg licence_history_li" style="display:@if($data['licence_history_toggle'][$i] == 1) display @else none @endif" >
							<span class="licence_history_span">
								<span class="del-licence-history glyphicon glyphicon-remove pull-left" aria-hidden="true"></span>
								<span class="@if ($data['licence_history_alert'][$i]) licence_alert glyphicon glyphicon-alert @endif licence_history_display">{{ $data['licence_history_display'][$i] }}</span>
								<span class="sort-handle glyphicon glyphicon-menu-hamburger pull-right" aria-hidden="true"></span>
								<input name="licence_history_order[]" id="licence_history_order[]" class="licence_history_order" form="main-form" type="hidden" value="{{$i}}" >
								<input name="licence_history_year[{{$i}}]" id="licence_history_year[{{$i}}]" class="licence_history_year" form="main-form" type="hidden" value="{{ $data['licence_history_year'][$i] }}" >
								<input name="licence_history_month[{{$i}}]" id="licence_history_month[{{$i}}]" class="licence_history_month" form="main-form" type="hidden" value="{{ $data['licence_history_month'][$i] }}" >
								<input name="licence_history_name[{{$i}}]" id="licence_history_name[{{$i}}]" class="licence_history_name" form="main-form" type="hidden" value="{{ $data['licence_history_name'][$i] }}" >
								<input name="licence_history_category[{{$i}}]" id="licence_history_category[{{$i}}]" class="licence_history_category" form="main-form" type="hidden" value="{{ $data['licence_history_category'][$i] }}" >
								<input name="licence_history_toggle[{{$i}}]" id="licence_history_toggle[{{$i}}]" class="licence_history_toggle" form="main-form" type="hidden" value="{{ $data['licence_history_toggle'][$i] }}" >
							</span>
							<span class="list-group-item licence_history_edit" style="display:none;margin-top:20px;">
								<form name="licence_history_form[{{$i}}]" id="licence_history_form[{{$i}}]" class="form-horizontal licence_history_form" method="" action="" >
									<input name="licence_history_id[{{$i}}]" id="licence_history_id[{{$i}}]" type="hidden" value="{{$i}}">
									<div class="form-group" style="margin-top:10px">
										<label class="col-sm-2 control-label" >年</label>
										<div class="col-sm-10">
											<select name="licence_history_year_edit[{{$i}}]" id="licence_history_year_edit[{{$i}}]" form="licence_history_form[{{$i}}]" class="form-control licence_history_year_edit" >
											<option value="" @if ($data['licence_history_year'][$i]== "") selected @endif>未選択</option>
											@foreach ($mtb_data['year'] as $year)
											<option value="{{ $year }}" @if($data['career_history_year'][$i] == $year) selected @endif>{{ $year }}年 {{-- @if $_mtb_birth_year_wareki'][$i]tem}({$_mtb_birth_year_wareki'][$item]})@endif --}}</option>
											@endforeach
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label" >月</label>
										<div class="col-sm-10">
											<select name="licence_history_month_edit[{{$i}}]" id="licence_history_month_edit[{{$i}}]" form="licence_history_form[{{$i}}]" class="form-control licence_history_month_edit">
											<option value=""@if ($data['licence_history_month'][$i]== "") selected @endif>未選択</option>
											@foreach ($mtb_data['mon'] as $mon)
											<option value="{{ $mon }}" @if($data['licence_history_month'][$i]== $mon) selected @endif>{{$mon}}月</option>
											@endforeach
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">資格名</label>
										<div class="col-sm-10">
											<input name="licence_history_name_edit[{{$i}}]" id="licence_history_name_edit[{{$i}}]" form="licence_history_form[{{$i}}]" class="form-control licence_history_name_edit" type="text" maxlength="50" value="{{ $data['licence_history_name'][$i] }}" >
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label" >合格・取得</label>
										<div class="col-sm-10 btn-group" data-toggle="buttons">
											<label class="btn btn-default @if($data['licence_history_category'][$i] == 1) active @endif">
												<input name="licence_history_category[{{$i}}]" id="licence_history_category[{{$i}}]" form="licence_history_form[{{$i}}]" class="licence_history_category_edit" autocomplete="off" type="radio" value="1" @if($data['licence_history_category'][$i]== 1) checked @endif>合格
											</label>
											<label class="btn btn-default @if($data['licence_history_category'][$i]== 2) active @endif" >
												<input name="licence_history_category[{{$i}}]" id="licence_history_category[{{$i}}]" form="licence_history_form[{{$i}}]" class="licence_history_category_edit" autocomplete="off" type="radio" value="2" @if($data['licence_history_category'][$i]== 2) checked @endif>取得
											</label>
											<label class="btn btn-default @if($data['licence_history_category'][$i]== 3) active @endif" >
												<input name="licence_history_category[{{$i}}]" id="careelicenceistory_category[{{$i}}]" form="licence_history_form[{{$i}}]" class="licence_history_category_edit" autocomplete="off" type="radio" value="3" @if($data['licence_history_category'][$i] == 3) checked @endif>なし
											</label>
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-offset-2 col-sm-10">
											<button form="licence_history_form[{{$i}}]" type="submit" class="btn btn-primary">保存</button>
										</div>
									</div>
								</form>
							</span>
						</li>
						@endfor
					</ul>
				</div>
				<div class="panel-footer">
					<span id="add-licence-history" class="add-history glyphicon glyphicon-plus" aria-hidden="true"> 免許・資格を追加する</span>
				</div>
			</div>
			<!-- taglicence -->

			<div class="panel panel-default">
				<div class="panel-heading"><b>通勤時間・扶養等</b></div>
				<div class="panel-body">
					<div>
						<label>通勤時間</label>
					</div>
					<div class="input-group col-xs-4">
						<input name="commuting_hour" id="commuting_hour" class="form-control input-lg" form="main-form" type="number" value="0" min="0">
						<span class="input-group-addon">時間</span>
					</div>
					<br>
					<div class="input-group col-xs-4">
						<input name="commuting_minute" id="commuting_minute" class="form-control input-lg" form="main-form" type="number" value="0" min="0" max="59">
						<span class="input-group-addon">分</span>
					</div>
					<br>
					<div>
						<label>扶養家族数（配偶者を除く）</label>
					</div>
					<div class="input-group col-xs-4">
						<input name="dependents_number" id="dependents_number" class="form-control input-lg" form="main-form" type="number" value="0" min="0">
						<span class="input-group-addon">人</span>
					</div>
					<br>
					<div>
						<label>配偶者の有無</label>
					</div>
					<div class="btn-group" data-toggle="buttons">
						<label class="btn btn-default btn-lg">
							<input name="spouse_exist" id="spouse_exist" form="main-form" autocomplete="off" type="radio" value="1" >有
						</label>
						<label class="btn btn-default btn-lg active">
							<input name="spouse_exist" id="spouse_exist" form="main-form" autocomplete="off" type="radio" value="0" >無
						</label>
					</div>
					<br>
					<div>
						<label>配偶者の扶養義務</label>
					</div>
					<div class="btn-group" data-toggle="buttons">
						<label class="btn btn-default btn-lg">
							<input name="spouse_support" id="spouse_support" form="main-form" autocomplete="off" type="radio" value="1" >有
						</label>
						<label class="btn btn-default btn-lg active">
							<input name="spouse_support" id="spouse_support" form="main-form" autocomplete="off" type="radio" value="0" >無
						</label>
					</div>
					<br>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading"><b>志望の動機、特技、好きな学科など</b><br>現在 <span name="motivation_count" id="motivation_count"  class="motivation_count">0</span>文字 / 150文字</div>
				<div class="panel-body">
					<div class="form-group col-xs-12">
						<textarea name="motivation" id="motivation" form="main-form" class="form-control" rows="10"  maxlength="150"></textarea>
					</div>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading"><b>本人希望記入欄</b><br>（特に給料・職種・勤務時間・勤務地・その他についての希望などがあれば記入）<br>現在 <span name="treatment_count" id="treatment_count" class="treatment_count">0</span>文字 / 300文字</div>
				<div class="panel-body">
					<div class="form-group col-xs-12">
						<textarea name="treatment" id="treatment" form="main-form" class="form-control" rows="10" maxlength="300"></textarea>
					</div>
				</div>
			</div>
			{{-- メインフォーム 入力内容をWord生成プログラムへPOSTする --}}
			<div style="margin-top:30px;margin-bottom:100px;">
			<form id="main-form" action="/mypage/resume/create" class="form-horizontal" method="POST">
				@csrf
				<input form="main-form" type="hidden" name="mode" value="debug">
				<input form="main-form" type="submit" name="submit" class="btn btn-primary btn-lg btn-block" value="Wordファイルで出力する">
			</form>
			</div>
		</div>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script src="/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="/resume/jquery-ui/jquery-ui.min.js"></script>
		<script type="text/javascript" src="/resume/jquery-ui/jquery.ui.touch-punch.min.js"></script>
		<script>
			//$(document).ready(function(){
			$(function(){
				//指定したidを閉じたり開いたり
				$('.contact-toggle-chk').click(function(){
					if($('.contact-toggle-chk').is(':checked')){
						$('.panel-collapse').collapse('hide');
						$('.contact-toggle-chk').val(1);
					} else{
						$('.panel-collapse').collapse('show');
						$('.contact-toggle-chk').val(0);
					}
				});

				$('#add-edu-history').click(function(){
					var visible_line = $('#edu_history_ul li:visible').length+$('#career_history_ul li:visible').length;
					if(visible_line > 21){
						alert('学歴・職歴は合計22行までです。');
						return false;
					}
					var first_hide = $('#edu_history_ul li:hidden').first();
					var last_visible = $('#edu_history_ul li:visible').last();
					if(first_hide.attr('id') == undefined){
						alert('学歴・職歴は合計22行までです。');
						return false;
					}else{
						first_hide.find('.edu_history_toggle').val('1');
						first_hide.insertAfter(last_visible);
						first_hide.show('slide', { direction: 'up' });
						first_hide.find('.edu_history_edit').show('slide', { direction: 'up' });
						return true;
					}
				});

				$('#add-career-history').click(function(){
					var visible_line = $('#edu_history_ul li:visible').length+$('#career_history_ul li:visible').length;
					if(visible_line > 21){
						alert('学歴・職歴は合計22行までです。');
						return false;
					}
					var first_hide = $('#career_history_ul li:hidden').first();
					var last_visible = $('#career_history_ul li:visible').last();
					if(first_hide.attr('id') == undefined){
						alert('学歴・職歴は合計22行までです。');
						return false;
					}else{
						first_hide.find('.career_history_toggle').val('1');
						first_hide.insertAfter(last_visible);
						first_hide.show('slide', { direction: 'up' });
						first_hide.find('.career_history_edit').show('slide', { direction: 'up' });
						return true;
					}
				});

				$('#add-licence-history').click(function(){
					if($('#licence_history_ul li:visible').length > 8){
						alert('資格・免許は7行までです。');
						return false;
					}
					var first_hide = $('#licence_history_ul li:hidden').first();
					var last_visible = $('#licence_history_ul li:visible').last();
					if(first_hide.attr('id') == undefined){
						alert('資格・免許は7行までです。');
						return false;
					}else{
						first_hide.find('.licence_history_toggle').val('1');
						first_hide.insertAfter(last_visible);
						first_hide.show('slide', { direction: 'up' });
						first_hide.find('.licence_history_edit').show('slide', { direction: 'up' });
						return true;
					}
				});

				$('.edu_history_display').click(function(){
					$(this).parent().parent().css('opacity', '.4').animate({'opacity': '1'}, 'slow');
					$(this).parent().next('.edu_history_edit').toggle('slide', { direction: 'up' });
				});

				$('.career_history_display').click(function(){
					$(this).parent().parent().css('opacity', '.4').animate({'opacity': '1'}, 'slow');
					$(this).parent().next('.career_history_edit').toggle('slide', { direction: 'up' });
				});

				$('.licence_history_display').click(function(){
					$(this).parent().parent().css('opacity', '.4').animate({'opacity': '1'}, 'slow');
					$(this).parent().next('.licence_history_edit').toggle('slide', { direction: 'up' });
				});

				$('.del-edu-history').click(function(){
					var parent_history = $(this).parent().parent();
					var history_line = $(this).parent().find('.edu_history_display').text();
					var dialog_txt = 'この項目を削除しますか？<br>「'+history_line+'」';
					$('#del-history-dialog').html(dialog_txt);
					$('#del-history-dialog').dialog({
						title: '確認',
						modal: true,
						buttons:[
							{
								text:'キャンセル',
								click: function(){
									$(this).dialog("close");
								}
							},
							{
								text: '削除',
								class: 'dialog-btn-delete',
								click: function(){
									parent_history.hide('slide', { direction: 'right' });
									parent_history.find('.edu_history_display').text('入力してください');
									parent_history.find('.edu_history_toggle').val('0');

									parent_history.find('.edu_history_year').val('2001');
									parent_history.find('.edu_history_month').val('1');
									parent_history.find('.edu_history_school').val('');
									parent_history.find('.edu_history_category').val('1');

									parent_history.find('.edu_history_year_edit').val('2001');
									parent_history.find('.edu_history_month_edit').val('1');
									parent_history.find('.edu_history_school_edit').val('');
									parent_history.find('.edu_history_category_editr').val('1');
									$(this).dialog("close");
								}
							}
						]
					});
				});

				$('.del-career-history').click(function(){
					var parent_history = $(this).parent().parent();
					var history_line = $(this).parent().find('.career_history_display').text();
					var dialog_txt = 'この項目を削除しますか？<br>「'+history_line+'」';
					$('#del-history-dialog').html(dialog_txt);
					$('#del-history-dialog').dialog({
						title: '確認',
						modal: true,
						buttons:[
							{
								text:'キャンセル',
								click: function(){
									$(this).dialog("close");
								}
							},
							{
								text: '削除',
								class: 'dialog-btn-delete',
								click: function(){
									parent_history.hide('slide', { direction: 'right' });
									parent_history.find('.career_history_display').text('入力してください');
									parent_history.find('.career_history_toggle').val('0');

									parent_history.find('.career_history_year').val('2001');
									parent_history.find('.career_history_month').val('1');
									parent_history.find('.career_history_company').val('');
									parent_history.find('.career_history_category').val('1');

									parent_history.find('.career_history_year_edit').val('2001');
									parent_history.find('.career_history_month_edit').val('1');
									parent_history.find('.career_history_company_edit').val('');
									parent_history.find('.career_history_category_editr').val('1');
									$(this).dialog("close");
								}
							}
						]
					});
				});

				$('.del-licence-history').click(function(){
					var parent_history = $(this).parent().parent();
					var history_line = $(this).parent().find('.licence_history_display').text();
					var dialog_txt = 'この項目を削除しますか？<br>「'+history_line+'」';
					$('#del-history-dialog').html(dialog_txt);
					$('#del-history-dialog').dialog({
						title: '確認',
						modal: true,
						buttons:[
							{
								text:'キャンセル',
								click: function(){
									$(this).dialog("close");
								}
							},
							{
								text: '削除',
								class: 'dialog-btn-delete',
								click: function(){
									parent_history.hide('slide', { direction: 'right' });
									parent_history.find('.licence_history_display').text('入力してください');
									parent_history.find('.licence_history_toggle').val('0');

									parent_history.find('.licence_history_year').val('2001');
									parent_history.find('.licence_history_month').val('1');
									parent_history.find('.licence_history_name').val('');
									parent_history.find('.licence_history_category').val('1');

									parent_history.find('.licence_history_year_edit').val('2001');
									parent_history.find('.licence_history_month_edit').val('1');
									parent_history.find('.licence_history_name_edit').val('');
									parent_history.find('.licence_history_category_editr').val('1');
									$(this).dialog("close");
								}
							}
						]
					});
				});

				$('#edu_history_ul').sortable({
					handle:'.sort-handle',
					containment:'.edu_history_ul',
					tolerance: 'pointer',
				});

				$('#career_history_ul').sortable({
					handle:'.sort-handle',
					containment:'.career_history_ul',
					tolerance: 'pointer',
				});

				$('#licence_history_ul').sortable({
					handle:'.sort-handle',
					containment:'.licence_history_ul',
					tolerance: 'pointer',
				});

				$('.sort-handle').hover(
					function(){
						$(this).parent().parent().css('animation', 'Flash 2s infinite');
					}, function(){
						$(this).parent().parent().css('animation', '');
					}
				);

				$('.edu_history_form').submit(function(){
					var display_obj = $(this).closest('.edu_history_li').find('.edu_history_display');
					var alert_obj = $(this).closest('.edu_history_li').find('.edu_alert');
					var input_obj = $(this).closest('.edu_history_li').find('.edu_history_span');
					var edit_obj = $(this).closest('.edu_history_li').find('.edu_history_edit');
					var parent_obj = $(this).closest('.edu_history_li');

					input_obj.find('.edu_history_year').val($(this).find('.edu_history_year_edit').val());
					input_obj.find('.edu_history_month').val($(this).find('.edu_history_month_edit').val());
					input_obj.find('.edu_history_school').val($(this).find('.edu_history_school_edit').val());
					input_obj.find('.edu_history_category').val($(this).find('.edu_history_category_edit:checked').val());
					input_obj.find('.edu_history_toggle').val('1');

					var arr_cat = {};
					arr_cat['1'] ='入学';
					arr_cat['2'] ='卒業';
					arr_cat['3'] ='中退';
					arr_cat['4'] ='';

					if($(this).find('.edu_history_year_edit').val() == ""){
						var year_edit = "○年";
					}else{
						var year_edit = $(this).find('.edu_history_year_edit').val()+'年';
					}
					
					if($(this).find('.edu_history_month_edit').val() == ""){
						var month_edit = "○月 ";
					}else{
						var month_edit = $(this).find('.edu_history_month_edit').val()+'月 ';
					}

					if($(this).find('.edu_history_school_edit').val() == ""){
						var school_edit = "○○";
					}else{
						var school_edit = $(this).find('.edu_history_school_edit').val()+' ';
					}

					var display_line =
						year_edit + month_edit + school_edit +
						arr_cat[$(this).find('.edu_history_category_edit:checked').val()];

					if(
						$(this).find('.edu_history_year_edit').val() == "" ||
						$(this).find('.edu_history_month_edit').val() == "" ||
						$(this).find('.edu_history_school_edit').val() == ""
					){
						display_obj.addClass("glyphicon glyphicon-alert edu_alert");
					}else{
						display_obj.removeClass("glyphicon glyphicon-alert edu_alert");
					}
					//$(window).scrollTop(parent_obj.offset().top)
					edit_obj.toggle('slide', { direction: 'up' });
					display_obj.html(display_line);
					parent_obj.css('opacity', '.4').animate({'opacity': '1'}, 'slow');

					return false;
				});

				$('.career_history_form').submit(function(){
					var display_obj = $(this).closest('.career_history_li').find('.career_history_display');
					var input_obj = $(this).closest('.career_history_li').find('.career_history_span');
					var edit_obj = $(this).closest('.career_history_li').find('.career_history_edit');
					var parent_obj = $(this).closest('.career_history_li');

					input_obj.find('.career_history_year').val($(this).find('.career_history_year_edit').val());
					input_obj.find('.career_history_month').val($(this).find('.career_history_month_edit').val());
					input_obj.find('.career_history_company').val($(this).find('.career_history_company_edit').val());
					input_obj.find('.career_history_category').val($(this).find('.career_history_category_edit:checked').val());
					input_obj.find('.career_history_toggle').val('1');

					var arr_cat = {};
					arr_cat['1'] ='入社';
					arr_cat['2'] ='退職';
					arr_cat['3'] ='';
					arr_cat['4'] ='在職中（現在に至る）';

					if($(this).find('.career_history_year_edit').val() == ""){
						var year_edit = "○年";
					}else{
						var year_edit = $(this).find('.career_history_year_edit').val()+'年';
					}
					
					if($(this).find('.career_history_month_edit').val() == ""){
						var month_edit = "○月 ";
					}else{
						var month_edit = $(this).find('.career_history_month_edit').val()+'月 ';
					}

					if($(this).find('.career_history_company_edit').val() == ""){
						var company_edit = "○○";
					}else{
						var company_edit = $(this).find('.career_history_company_edit').val()+' ';
					}

					var display_line =
						year_edit + month_edit + company_edit +
						arr_cat[$(this).find('.career_history_category_edit:checked').val()];


					if($(this).find('.career_history_category_edit:checked').val() == 4){
						display_line = arr_cat[$(this).find('.career_history_category_edit:checked').val()];
					}

					if(
						($(this).find('.career_history_year_edit').val() == "" ||
						$(this).find('.career_history_month_edit').val() == "" ||
						$(this).find('.career_history_company_edit').val() == "" ) &&
						$(this).find('.career_history_category_edit:checked').val() != 4

					){
						display_obj.addClass("glyphicon glyphicon-alert career_alert");
					}else{
						display_obj.removeClass("glyphicon glyphicon-alert career_alert");
					}

					edit_obj.toggle('slide', { direction: 'up' });
					display_obj.html(display_line);
					parent_obj.css('opacity', '.4').animate({'opacity': '1'}, 'slow');

					return false;
				});

				$('.licence_history_form').submit(function(){
					var display_obj = $(this).closest('.licence_history_li').find('.licence_history_display');
					var input_obj = $(this).closest('.licence_history_li').find('.licence_history_span');
					var edit_obj = $(this).closest('.licence_history_li').find('.licence_history_edit');
					var parent_obj = $(this).closest('.licence_history_li');

					input_obj.find('.licence_history_year').val($(this).find('.licence_history_year_edit').val());
					input_obj.find('.licence_history_month').val($(this).find('.licence_history_month_edit').val());
					input_obj.find('.licence_history_name').val($(this).find('.licence_history_name_edit').val());
					input_obj.find('.licence_history_category').val($(this).find('.licence_history_category_edit:checked').val());
					input_obj.find('.licence_history_toggle').val('1');

					var arr_cat = {};
					arr_cat['1'] ='合格';
					arr_cat['2'] ='取得';
					arr_cat['3'] ='';

					if($(this).find('.licence_history_year_edit').val() == ""){
						var year_edit = "○年";
					}else{
						var year_edit = $(this).find('.licence_history_year_edit').val()+'年';
					}
					
					if($(this).find('.licence_history_month_edit').val() == ""){
						var month_edit = "○月 ";
					}else{
						var month_edit = $(this).find('.licence_history_month_edit').val()+'月 ';
					}

					if($(this).find('.licence_history_name_edit').val() == ""){
						var name_edit = "○○";
					}else{
						var name_edit = $(this).find('.licence_history_name_edit').val()+' ';
					}

					var display_line =
						year_edit + month_edit + name_edit +
						arr_cat[$(this).find('.licence_history_category_edit:checked').val()];

					/*
					var display_line =
						$(this).find('.licence_history_year_edit').val() + '年' +
						$(this).find('.licence_history_month_edit').val() + '月 ' +
						$(this).find('.licence_history_name_edit').val() + ' ' +
						arr_cat[$(this).find('.licence_history_category_edit:checked').val()];
					*/

					if(
						$(this).find('.licence_history_year_edit').val() == "" ||
						$(this).find('.licence_history_month_edit').val() == "" ||
						$(this).find('.licence_history_name_edit').val() == ""

					){
						display_obj.addClass("glyphicon glyphicon-alert licence_alert");
					}else{
						display_obj.removeClass("glyphicon glyphicon-alert licence_alert");
					}

					edit_obj.toggle('slide', { direction: 'up' });
					display_obj.html(display_line);
					parent_obj.css('opacity', '.4').animate({'opacity': '1'}, 'slow');

					return false;
				});

				$('#motivation').bind('keydown keyup keypress change',function(){
					var thisValueLength = $(this).val().length;
					$('.motivation_count').html(thisValueLength);
				});

				$('#treatment').bind('keydown keyup keypress change',function(){
					var thisValueLength = $(this).val().length;
					$('.treatment_count').html(thisValueLength);
				});

			});
		</script>
	</body>
</html>