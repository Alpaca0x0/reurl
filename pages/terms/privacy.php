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
            <div class="ts-header is-large is-center-aligned is-heavy">本站隱私政策</div>
            一旦使用本服務，即表示您同意遵守以下條款及規章。
        </div>
        <div class="ts-image">
            <img src="<?=htmlentities(Uri::img('terms/privacy.png'))?>">
        </div>
        <div class="ts-content is-padded is-center-aligned">
            歡迎使用我們的短網址服務！<br>
            為了保護您的隱私和個人資訊，請在使用本服務之前詳細閱讀以下隱私政策。<br>
            本政策描述了我們收集、使用和保護您的個人資訊的相關事項。
            <div class="ts-space is-large"></div>
            <div class="ts-divider is-section"></div>

            <div class="ts-space is-large"></div>
            <div class="ts-row">
                <div class="column is-start-aligned">
                    <div class="ts-notice is-outlined is-dense ts-segment is-positive is-top-indicated">
                        <div class="ts-header is-start-icon is-heavy"><span class="ts-icon is-cookie-bite-icon"></span> 資訊收集</div>
                        <div class="title">1.1</div>
                        <div class="content">我們可能會收集和使用您的IP位址、瀏覽器類型、操作系統和其他訪問本服務時自動產生的技術資訊，以提供和改進本服務的功能和性能。</div><br>
                        <div class="title">1.2</div>
                        <div class="content">當您使用本服務時，我們可能會使用Cookie或類似技術來收集和存儲有關您的信息，以提供個人化的使用體驗和方便您的訪問。您可以根據自己的瀏覽器設置選擇接受或拒絕Cookie。</div><br>
                    </div>
                </div>
            </div>

            <div class="ts-space is-large"></div>
            <div class="ts-row">
                <div class="column is-fluid is-start-aligned">
                    <div class="ts-notice is-outlined is-dense ts-segment is-positive is-top-indicated">
                        <div class="ts-header is-start-icon is-heavy"><span class="ts-icon is-chart-pie-icon"></span> 資訊使用</div>
                        <div class="title">2.1</div>
                        <div class="content">我們使用收集的資訊來提供、維護和改進本服務的功能和性能，並根據您的個人設定為您提供個人化的內容和廣告。</div><br>
                        <div class="title">2.2</div>
                        <div class="content">我們不會將您的個人資訊出售、租借或轉讓給第三方，除非經過您的明確同意或遵守法律法規的要求。</div><br>
                        <div class="title">2.3</div>
                        <div class="content">在必要時，我們可能會根據適用的法律法規要求，將您的個人資訊提交給執法單位或相關當局，以協助調查、審查或防止違法活動。</div><br>
                    </div>
                </div>
            </div>

            <div class="ts-space is-large"></div>
            <div class="ts-row">
                <div class="column is-fluid is-start-aligned">
                    <div class="ts-notice is-outlined is-dense ts-segment is-positive is-top-indicated">
                        <div class="ts-header is-start-icon is-heavy"><span class="ts-icon is-shield-halved-icon"></span> 資訊安全</div>
                        <div class="title">3.1</div>
                        <div class="content">我們致力於保護您的個人資訊安全。我們採取合理的技術措施和安全措施來保護您的個人資訊免於未經授權的訪問、使用或披露。</div><br>
                        <div class="title">3.2</div>
                        <div class="content">儘管我們努力保護您的個人資訊安全，但請注意，在互聯網上傳輸的資訊並非完全安全，因此我們無法保證您的資訊在傳輸過程中的絕對安全。</div><br>
                    </div>
                </div>
            </div>

            <div class="ts-space is-large"></div>
            <div class="ts-row">
                <div class="column is-fluid is-start-aligned">
                    <div class="ts-notice is-outlined is-dense ts-segment is-positive is-top-indicated">
                        <div class="ts-header is-start-icon is-heavy"><span class="ts-icon is-link-icon"></span> 第三方網站連結</div>
                        <div class="title">4.1</div>
                        <div class="content">本服務可能包含指向其他網站或資源的連結。請注意，當您點擊這些連結並離開本服務時，本隱私政策將不再適用於您所訪問的其他網站或資源。我們建議您在訪問其他網站之前查閱其隱私政策，以了解其資訊收集和使用方式。</div><br>
                    </div>
                </div>
            </div>
            
            <div class="ts-space is-large"></div>
            <div class="ts-row">
                <div class="column is-fluid is-start-aligned">
                    <div class="ts-notice is-outlined is-dense ts-segment is-positive is-top-indicated">
                        <div class="ts-header is-start-icon is-heavy"><span class="ts-icon is-pencil-icon"></span> 隱私權政策的變更</div>
                        <div class="title">5.1</div>
                        <div class="content">我們保留隨時修改或更新本隱私政策的權利。對於重大變更，我們將提供合理的通知方式，例如在本服務上發布通知或向您發送電子郵件。請定期查看本隱私政策以獲取最新信息。</div><br>
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

<script>document.title="隱私政策";</script>

<?php Inc::component('footer'); ?>