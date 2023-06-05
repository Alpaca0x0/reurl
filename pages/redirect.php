<?php
Inc::clas('db');
DB::connect() or respond(
    '資料庫連接失敗', 
    '很抱歉，我們在嘗試連接資料庫時發生致命錯誤...<br>請稍後再回來看看吧 :/',
    true
);

// search
$sql = "SELECT `url` FROM `url` WHERE `code`=:code LIMIT 1;";
DB::query($sql)::execute([
    ':code' => Router::path(),
]);

// search error
if(DB::error()){
    respond(
        'SQL 查詢錯誤', 
        '很抱歉，我們在嘗試查詢資料時發生致命錯誤...<br>請稍後再回來看看吧 :/',
        true
    );
}

// not found
$row = DB::fetch();
if($row === false){
    respond(
        '該短網址並不存在', 
        '似乎... 找不到這個網址呢 :/<br>可能遭到移除或是您的網址輸入錯誤...',
        true
    );
}
$url = trim($row['url']);

// check if in black list
Inc::clas('url');
$info = Url::info($url);
$domain = trim($info[2]);
if(Url::isBlackDomain($domain)){
    respond(
        '注意！正在前往的疑似是<span class="ts-text is-heavy is-large is-negative">惡意連結</span>！', 
        '系統偵測到本短網址所導向的疑似為惡意連結：<br>
        「<span class="ts-text is-bold is-negative">'.htmlentities($url).'</span>」
        <br><br>
        在本服務之<a class="ts-text is-link is-external-link" href="'.htmlentities(Uri::page('terms/')).'" target="_blank">服務條款</a>中提到「<span class="ts-text is-key">本服務僅提供短網址轉換的功能，我們對使用者將該短網址轉換至的目標網站或資源的合法性、安全性或真實性不負任何責任</span>」。<br><br>
        並且您同意「<span class="ts-text is-key">我們不對因您使用本服務或無法使用本服務而導致的任何直接、間接、特殊或衍生的損失或損害負責</span>」。',
        true,
        true,
        '我堅持前往可能不安全的連結！',
        '<span class="ts-text is-negative">我已詳閱且同意以上規範</span>',
        htmlentities($url)
    );
}

if(headers_sent()){ die('Redirect Page Error: Headers already been sent.'); }
header('Location: '.$url);

?>



<?php function respond(
    $title = "發生未知錯誤",
    $content = "Unexpected Error...",
    $button = true,
    $check = false,
    $btnText = '回首頁',
    $chkText = '我同意',
    $btnLink = Root
){ ?>

<?php Inc::component('header'); ?>
<div class="ts-modal is-visible">
    <div class="content" style="min-width: 50%; max-width: 100%;">
        <div class="ts-content is-dense">
            <div class="ts-header"><?=($title)?></div>
        </div>
        <div class="ts-divider"></div>
        <div class="ts-content" style="max-height: 50vh; min-height: 20vh; overflow-y: auto;"><?=($content)?></div>

        <?php if($check){ ?>
            <div class="ts-divider"></div>
            <div class="ts-content">
                <label class="ts-checkbox">
                    <input id="isChecked" type="checkbox" >
                    <span class="ts-text is-required"><?=($chkText)?></span>
                </label>
            </div>
        <?php } ?>

        <?php if($button){ ?>
            <div class="ts-divider"></div>
            <div class="ts-content is-tertiary">
                <div class="ts-grid">
                    <div class="column is-6-wide"><a class="ts-button" href="<?=htmlentities(Root)?>">回首頁</a></div>
                    <div class="column is-10-wide">
                        <button class="ts-button is-fluid<?=($check?' is-negative is-outlined':'')?>" onclick="
                            <?php if($check){ ?>
                                window.location.replace(document.querySelector('input#isChecked').checked ? '<?=($btnLink)?>' : '#!'); 
                                if(!document.querySelector('input#isChecked').checked){
                                    Swal.fire({
                                        position: 'bottom-start',
                                        icon: 'warning',
                                        title: '您尚未同意上述規範',
                                        toast: true,
                                        showConfirmButton: false,
                                        timer: 6400,
                                        timerProgressBar: true,
                                        didOpen: (toast) => {
                                            toast.addEventListener('mouseenter', Swal.stopTimer)
                                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                                        }
                                    });
                                }
                            <?php }else{ ?>
                                window.location.replace(<?=$btnLink?>)
                            <?php } ?>
                        "><?=($btnText)?></button>
                    </div>
                </div>
            </div>
        <?php } ?>

    </div>
</div>
<?php Inc::component('footer'); ?>

<?php die(); } ?>
