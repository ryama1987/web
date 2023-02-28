<!doctype html>
<html>
  <?php include( $_SERVER['DOCUMENT_ROOT'] . "/include/inc-common.php"); ?>
  <body id="news" class="index">
    <div id="bg_video">
      <video muted autoplay loop controlslist="nodownload" oncontextmenu="return false;">
        <source class="switch" src="/assets/movie/bg_video_pc.mp4" type="video/mp4">
        <p>videoタグをサポートした主要ブラウザで視聴ください。</p>
      </video>
    </div><!-- /#bg_video -->
    <div id="wrapper">
      <?php include( $_SERVER['DOCUMENT_ROOT'] . "/include/inc-header.php"); ?>
      <div id="bread">
        <div class="main_width">
          <ol class="breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList">
            <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href=""><span itemprop="name">HOME</span></a><meta itemprop="position" content="1" /></li>
            <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name">お知らせ</span><meta itemprop="position" content="2"></li>
          </ol>
        </div><!-- /.main_width -->
      </div><!-- bread -->
      <div id="main_area" role="main">
        <div class="main_title move_up">
          <div class="main_width">
            <h1 class="title">お知らせ</h1>
            <p class="panchang">news</p>
          </div><!-- /.main_width -->
        </div><!-- /.main_title -->
        <div class="main_width">
          <div class="cont_width">
            <div class="news_list move_up">
              <ul>
                <li><a href=""><span class="date">2022.07.25</span><span class="text">「港湾労働者不足対策アクションプラン」がまとまり公表されました。</span></a></li>
                <li><a href=""><span class="date">2022.07.25</span><span class="text">ESG・SDGs対策委員会の取り組みに関するページを作成しました。</span></a></li>
                <li><a href=""><span class="date">2022.07.25</span><span class="text">今後ますます便利になる「マイナンバーカード」の積極的な取得にご協力をお願いし今後ますます便利になる「マイナンバーカード」の積極的な取得にご協力をお願いし</span></a></li>
                <li><a href=""><span class="date">2022.07.25</span><span class="text">今後ますます便利になる「マイナンバーカード」の積極的な取得にご協力をお願いし今後ますます便利になる「マイナンバーカード」の積極的な取得にご協力をお願いし</span></a></li>
                <li><a href=""><span class="date">2022.07.25</span><span class="text">今後ますます便利になる「マイナンバーカード」の積極的な取得にご協力をお願いし今後ますます便利になる「マイナンバーカード」の積極的な取得にご協力をお願いし</span></a></li>
              </ul>
            </div><!-- /.news_list -->
            <div class="pagenation">
              <ul>
                <li class="first"><a href="?pg=1">1</a></li>
                <li><span class="dot">...</span></li>
                <li><span class="current">5</span></li>
                <li><a href="?pg=2">6</a></li>
                <li><a href="?pg=3">7</a></li>
                <li><span class="dot">...</span></li>
                <li class="last"><a href="?pg=99">99</a></li>
              </ul>
            </div>
          </div><!-- /.cont_width -->
        </div><!-- /.main_width -->

      </div><!-- main_area -->
      <?php include( $_SERVER['DOCUMENT_ROOT'] . "/include/inc-footer.php"); ?>
    </div><!-- wrapper -->
    <?php include( $_SERVER['DOCUMENT_ROOT'] . "/include/inc-js.php"); ?>
    <script>
    </script>
  </body>
</html>
