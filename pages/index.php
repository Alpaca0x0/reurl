<?php Inc::component('header'); ?>

<div class="ts-container is-narrow">
    <div class="ts-space is-big"></div>
    <div class="ts-header is-huge is-center-aligned is-heavy"><?=htmlentities(NAME)?></div>
    <div class="ts-space is-big"></div>

    <form id="Reurl">
        <div class="ts-row is-compact">
            <div class="column is-fluid">
                <div class="ts-input is-large is-circular is-fluid is-solid is-start-icon">
                    <span class="ts-icon is-seedling-icon"></span>
                    <input type="text" id="originUrl" placeholder="<?=htmlentities(Protocol.'://'.Domain.Root)?>">
                </div>
            </div>
            <div class="column">
                <button type="submit" class="ts-button is-circular is-icon is-ghost">
                    <span class="ts-icon is-paper-plane-icon"></span>
                </button>
            </div>
            <div class="column">
                <button type="button" id="clear" class="ts-button is-circular is-icon is-ghost">
                    <span class="ts-icon is-negative is-trash-can-icon"></span>
                </button>
            </div>
        </div>
    </form>
    <div class="ts-space"></div>

    <div class="ts-wrap">
        <span>相關連結：</span>
        <a class="ts-text is-undecorated" href="<?=htmlentities(Uri::page('terms/'))?>">
            <span class="ts-icon is-scroll-icon"></span> 服務條款
        </a>
        <a class="ts-text is-undecorated" href="https://github.com/Alpaca0x0/reurl/" target="_blank">
            <span class="ts-icon is-github-icon"></span> 原始碼
        </a>
    </div>

    <div id="Count" class="ts-divider is-section is-center-text">
        <span class="ts-text is-disabled">
            本服務累積共縮了 <span id="value"><div class="ts-loading is-small"></div></span> 次網址
        </span>
    </div>

    <div id="Resp" class="ts-snackbar animate__animated animate__faster animate__fadeIn">
        <div id="message" class="content">貼上網址後 Enter 即可縮網址！</div>
        <div id="newUrl" class="content"></div>
        <button id="copy" class="action" style="display: none">複製</button>
    </div>
    <div class="ts-space is-large"></div>
</div>

<style>@import url('<?=Uri::css('animate')?>');</style>
<script type="module">
    import '<?=Uri::js('ajax')?>';
    import * as Resp from '<?=Uri::js('resp')?>';
    // 
    let info = {
        type: null,
        title: null,
        msg: null,
    };
    let is = {
        submitting: false,
    };
    let protocol = '<?=htmlentities(Protocol)?>';
    let domain = '<?=htmlentities(Domain)?>';
    let root = '<?=htmlentities(Root)?>';
    let newUrl = "";
    // 
    let el = {};
    el.form = document.querySelector('form#Reurl');
    el.originUrl = el.form.querySelector('input#originUrl');
    el.clear = el.form.querySelector('button#clear');

    el.resp = document.querySelector('div#Resp');
    el.message = el.resp.querySelector('div#message');
    el.newUrl = el.resp.querySelector('div#newUrl');
    el.copyBtn = el.resp.querySelector('button#copy');

    el.count = document.querySelector('div#Count');
    el.countValue = el.count.querySelector('span#value');
    // 
    el.form.addEventListener('submit', event => {
        event.preventDefault();
        el.originUrl.value = el.originUrl.value.trim();
        reurl(el.originUrl.value);
    });
    document.addEventListener("DOMContentLoaded", () => {
        setTimeout(function(){ el.resp.classList.remove('animate__fadeIn'); }, 500);
        getCountTotal();
    });
    el.clear.addEventListener('click', () => { el.originUrl.value = ''; });
    el.copyBtn.addEventListener('click', () => {
        navigator.clipboard.writeText(newUrl)
        .then(() => {
            Swal.fire({
                position: 'bottom-start',
                icon: 'success',
                title: '複製成功',
                toast: true,
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
        });
    });
    // 
    const isSubmitting = (status=null) => {
        if(status === null){ return is.submitting; }
        is.submitting = status;
    }
    // 
    const reurl = (url) => {
        if(isSubmitting()){ return; }
        el.newUrl.innerHTML = '';
        el.message.innerHTML = '生成中... ';
        el.copyBtn.style.display = 'none';
        // 
        isSubmitting(true);
        // info
        info.type = null;
        info.title = 'Info';
        info.msg = 'Submitting... Please wait...';

        // animation
        el.resp.classList.remove('animate__pulse');
        
        // request
        $.ajax({
            type: "POST",
            url: '<?=htmlentities(Uri::auth('reurl'))?>',
            data: { url: url, },
            dataType: 'json',
        }).always(()=>{
            info.type = 'error';
            info.title = 'Error';
            info.msg = 'Unexpected Error';
        }).fail((xhr, status, error) => {
            console.error(xhr.responseText);
        }).done((resp) => {
            console.log(resp);
            if(!Resp.object(resp)){ return false; }
            info.type = resp.type;
            info.title = resp.type[0].toUpperCase() + resp.type.slice(1);
            info.msg = resp.message;
            // 
            if(resp.type==='success'){
                getCountTotal();
                newUrl = `${protocol}://${domain}${root}${resp.data}`;
                el.originUrl.value = '';
                el.message.innerHTML = "短網址：";
                el.newUrl.innerHTML = `<a class="ts-text is-link is-external-link" href="${newUrl}" target="_blank">${newUrl}</a>`;
                el.copyBtn.style.display = "inline";
            }else{
                el.message.innerHTML = resp.message;
                if(resp.status === 'domain_may_not_exists'){
                    el.message.innerHTML += '：<span class="ts-text is-heavy is-large is-negative">' + resp.data + '</span>';
                }else if(resp.status === 'port_out_range'){
                    el.message.innerHTML += '：<span class="ts-text is-heavy is-large is-negative">' + resp.data + '</span>';
                }else if(resp.status === 'black_domain'){
                    el.message.innerHTML += '：<span class="ts-text is-heavy is-large is-negative">' + resp.data + '</span>';
                }
            }
        }).always((resp) => {
            el.resp.classList.add("animate__pulse");
            Swal.fire({
                position: 'bottom-start',
                icon: info.type,
                title: info.msg,
                toast: true,
                showConfirmButton: false,
                timer: info.type==='success' ? 2000 : false,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
            setTimeout(function(){ isSubmitting(false); }, 1500);
        });
    };
    // counter
    const getCountTotal = () => {
        $.ajax({
            type: "GET",
            url: '<?=htmlentities(Uri::api('count/'))?>',
            dataType: 'json',
        }).fail((xhr, status, error) => {
            console.error(xhr.responseText);
        }).done((resp) => {
            console.log(resp);
            if(!Resp.object(resp)){ return false; }
            // 
            if(resp.type==='success'){
                el.countValue.innerHTML = resp.data.toLocaleString('en-US');
            }else{
                el.countValue.innerHTML = '<div class="ts-icon is-question-icon"></div>';
            }
        });
    }; 
</script>

<?php  Inc::component('footer'); ?>