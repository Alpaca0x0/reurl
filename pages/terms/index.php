<?php Inc::component('header'); ?>


<div class="ts-space is-large"></div>

<div class="ts-container is-very-narrow">
<div class="ts-breadcrumb is-divided">
    <a class="item" href="<?=htmlentities(Root)?>">
        <span class="ts-icon is-house-icon"></span> 返回首頁
    </a>
</div>
</div>

<div class="ts-space is-large"></div>

<div class="ts-container is-very-narrow">
    <div class="ts-box">
        <div class="ts-content is-vertically-padded is-center-aligned">
            <div class="ts-header is-large is-center-aligned is-heavy">本站服務條款</div>
            一旦使用本服務，即表示您同意遵守以下條款及規章。
        </div>
        <div class="ts-image">
            <img src="<?=htmlentities(Uri::img('terms/index.png'))?>">
        </div>
        <div class="ts-content is-padded is-center-aligned">
            歡迎使用我們的短網址服務！<br>
            在使用本服務之前，請詳細閱讀以下使用條款。<br>
            這些使用條款將規範您與本短網址服務之間的關係，並確保我們的服務運作順利且安全。
            <div class="ts-space is-large"></div>
            <div class="ts-divider is-section"></div>

            <div class="ts-space is-large"></div>
            <div class="ts-row">
                <div class="column is-start-aligned">
                    <div class="ts-notice is-outlined is-dense ts-segment is-positive is-top-indicated">
                        <div class="ts-header is-start-icon is-heavy"><span class="ts-icon is-seedling-icon"></span> 服務使用</div>
                        <div class="title">1.1</div>
                        <div class="content">您同意遵守所有適用的法律和法規，並不會使用本服務進行任何非法、欺詐、侵犯他人權益或違反道德的活動。</div><br>
                        <div class="title">1.2</div>
                        <div class="content">您不得使用本服務將包含有宣傳、詆毀、淫穢、恐怖主義、仇恨言論、色情、暴力、騷擾或其他不適當內容的網站或資源進行包裝。</div><br>
                        <div class="title">1.3</div>
                        <div class="content">您應保證提供給本服務的所有網址和內容均為合法且無違反第三方權益的。</div><br>
                    </div>
                </div>
            </div>

            <div class="ts-space is-large"></div>
            <div class="ts-row">
                <div class="column is-fluid is-start-aligned">
                    <div class="ts-notice is-outlined is-dense ts-segment is-positive is-top-indicated">
                        <div class="ts-header is-start-icon is-heavy"><span class="ts-icon is-person-circle-exclamation-icon"></span> 責任限制</div>
                        <div class="title">2.1</div>
                        <div class="content">本服務僅提供短網址轉換的功能，我們對使用者將該短網址轉換至的目標網站或資源的合法性、安全性或真實性不負任何責任。</div><br>
                        <div class="title">2.2</div>
                        <div class="content">您同意自行承擔使用本服務的風險，包括但不限於您使用或依賴短網址所造成的任何損失或損害。</div><br>
                        <div class="title">2.3</div>
                        <div class="content">我們不對因您使用本服務或無法使用本服務而導致的任何直接、間接、特殊或衍生的損失或損害負責。</div><br>
                    </div>
                </div>
            </div>

            <div class="ts-space is-large"></div>
            <div class="ts-row">
                <div class="column is-fluid is-start-aligned">
                    <div class="ts-notice is-outlined is-dense ts-segment is-positive is-top-indicated">
                        <div class="ts-header is-start-icon is-heavy"><span class="ts-icon is-user-lock-icon"></span> 隱私保護</div>
                        <div class="title">3.1</div>
                        <div class="content">您同意我們根據我們的隱私政策處理您的個人資料。請閱讀並瞭解我們的<a class="ts-text is-link is-external-link" href="<?=htmlentities(Uri::page('terms/privacy'))?>" target="_blank">隱私政策</a>，以瞭解我們如何處理和保護您的資料。</div><br>
                        <div class="title">3.2</div>
                        <div class="content">您同意我們可以根據法律要求或合法權限，將您的個人資料提供給相關當局或第三方。</div><br>
                    </div>
                </div>
            </div>

            <div class="ts-space is-large"></div>
            <div class="ts-row">
                <div class="column is-fluid is-start-aligned">
                    <div class="ts-notice is-outlined is-dense ts-segment is-positive is-top-indicated">
                        <div class="ts-header is-start-icon is-heavy"><span class="ts-icon is-plug-circle-xmark-icon"></span> 技術問題和賠償免責</div>
                        <div class="title">4.1</div>
                        <div class="content">由於本服務是在私人主機上運行，可能會遇到無預期的斷線、斷網或其他技術問題。在這種情況下，我們不保證本服務的可用性、連續性或可靠性。</div><br>
                        <div class="title">4.2</div>
                        <div class="content">您同意在使用本服務時，承擔由於技術問題所引起的任何損失或損害，包括但不限於短網址無法正常轉換、資料丟失或其他因技術問題造成的問題。</div><br>
                        <div class="title">4.3</div>
                        <div class="content">您同意不對我們要求任何形式的賠償，包括但不限於經濟損失、利潤損失、商譽損失或其他因技術問題而導致的任何損失或損害。</div><br>
                    </div>
                </div>
            </div>
            
            <div class="ts-space is-large"></div>
            <div class="ts-row">
                <div class="column is-fluid is-start-aligned">
                    <div class="ts-notice is-outlined is-dense ts-segment is-positive is-top-indicated">
                        <div class="ts-header is-start-icon is-heavy"><span class="ts-icon is-pencil-icon"></span> 服務變更和終止</div>
                        <div class="title">5.1</div>
                        <div class="content">我們保留隨時修改、暫停或終止本服務的權利，並無需提前通知。</div><br>
                    </div>
                </div>
            </div>

            <div class="ts-space is-large"></div>
            <div class="ts-divider is-section"></div>
            <!-- <div class="ts-space"></div>
            <div>有什麼問題嗎？在這聯繫我們！</div>
            <a href="#!">support@example.com</a>
            <div class="ts-space"></div> -->
        </div>
    </div>
</div>

<script>document.title="服務條款"</script>

<?php Inc::component('footer'); ?>