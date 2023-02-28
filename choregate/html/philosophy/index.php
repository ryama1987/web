<!doctype html>
<html>
  <?php include( $_SERVER['DOCUMENT_ROOT'] . "/include/inc-common.php"); ?>
  <body id="philosophy" class="index">
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
            <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name">統計データ／BCPマニュアル</span><meta itemprop="position" content="2"></li>
          </ol>
        </div><!-- /.main_width -->
      </div><!-- bread -->
      <div id="main_area" role="main">
        <div class="main_title move_up">
          <div class="main_width">
            <h1 class="title">統計データ／<br class="pc_none">BCPマニュアル</h1>
            <p class="panchang">Data+Manual</p>
          </div><!-- /.main_width -->
        </div><!-- /.main_title -->
        <div class="main_width">
          <div class="cont_width">
            <div class="main_kv move_up">
              <picture>
                <source loading="lazy" srcset="/assets/images/philosophy/kv_pc.jpg" media="(min-width: 901px)" />
                <source loading="lazy" srcset="/assets/images/philosophy/kv_sp.jpg" media="(max-width: 900px)" />
                <img loading="lazy" class="lazy" src="/assets/images/philosophy/kv_pc.jpg" width="1292" height="642" alt="">
              </picture>
            </div><!-- /.main_kv -->
            <div class="mod_area">
              <div class="mod_cont move_up">
                <div class="box_link pdf">
                  <div class="box_set">
                    <a href="" class="set_wrap">
                      <p class="txt">2021年　1000万TEUを超える世界の主要コンテナ港湾</p>
                      <p class="btn">PDFをダウンロード</p>
                    </a>
                  </div><!-- /.box_set -->
                  <div class="box_set">
                    <a href="" class="set_wrap">
                      <p class="txt">2021年　1000万TEUを超える世界の主要コンテナ港湾2021年　1000万TEUを超える世界の主要コンテナ港湾2021年　1000万TEUを超える世界の主要コンテナ港湾</p>
                      <p class="btn">PDFをダウンロード</p>
                    </a>
                  </div><!-- /.box_set -->
                  <div class="box_set">
                    <a href="" class="set_wrap">
                      <p class="txt">2021年　1000万TEUを超える世界の主要コンテナ港湾</p>
                      <p class="btn">PDFをダウンロード</p>
                    </a>
                  </div><!-- /.box_set -->
                  <div class="box_set">
                    <a href="" class="set_wrap">
                      <p class="txt">2021年　1000万TEUを超える世界の主要コンテナ港湾</p>
                      <p class="btn">PDFをダウンロード</p>
                    </a>
                  </div><!-- /.box_set -->
                </div><!-- /.box_link -->
              </div><!-- /.mod_cont -->
            </div><!-- /.mod_area -->
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
