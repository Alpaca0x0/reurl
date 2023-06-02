<?php Inc::component('header'); ?>

<div class="ts-container is-narrow">
    <div class="ts-space is-big"></div>
    <div class="ts-header is-huge is-center-aligned is-heavy"><?=htmlentities(NAME)?></div>
    <div class="ts-space is-big"></div>

    <form id="reurl">
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

    <!-- <div class="ts-wrap">
        <span>熱門話題：</span>
        <a class="ts-text is-undecorated" href="#!">轉型正義</a>
        <a class="ts-text is-undecorated" href="#!">比特幣</a>
        <a class="ts-text is-undecorated" href="#!">空汙法</a>
        <a class="ts-text is-undecorated" href="#!">公投法</a>
        <a class="ts-text is-undecorated" href="#!">勞基法</a>
        <a class="ts-text is-undecorated" href="#!">修法</a>
    </div>
    <div class="ts-space is-big"></div> -->

    <div id="resp" class="ts-snackbar animate__animated animate__faster animate__fadeIn">
        <div id="message" class="content">貼上網址後 Enter 即可縮網址！</div>
        <div id="newUrl" class="content"></div>
        <button id="copy" class="action" style="display: none">複製</button>
    </div>

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
    el.form = document.querySelector('form#reurl');
    el.originUrl = el.form.querySelector('input#originUrl');
    el.clear = el.form.querySelector('button#clear');
    el.resp = document.querySelector('div#resp');
    el.message = el.resp.querySelector('div#message');
    el.newUrl = el.resp.querySelector('div#newUrl');
    el.copyBtn = el.resp.querySelector('button#copy');
    // 
    el.form.addEventListener('submit', event => {
        event.preventDefault();
        el.originUrl.value = el.originUrl.value.trim();
        reurl(el.originUrl.value);
    });
    document.addEventListener("DOMContentLoaded", () => {
        setTimeout(function(){ el.resp.classList.remove('animate__fadeIn'); }, 500);
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
    const isUrl = (url) => {
	  	let urlPattern = new RegExp('^(https?:\\/\\/)?'+ // validate protocol
	    '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // validate domain name
	    '((\\d{1,3}\\.){3}\\d{1,3}))|'+ // validate OR ip (v4) address
	    'localhost'+ // localhost
	    '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // validate port and path
	    '(\\?[;&a-z\\d%_.~+=-]*)?'+ // validate query string
	    '(\\#[-a-z\\d_]*)?$','i'); // validate fragment locator
	    return !!urlPattern.test(url);
	}
    const isSubmitting = (status=null) => {
        if(status === null){ return is.submitting; }
        is.submitting = status;
    }
    // 
    const reurl = (url) => {
        if(isSubmitting()){ return; }
        el.newUrl.innerHTML = '';
        el.message.innerHTML = '生成中... ';
        // if is not url
        url = url.trim();
        if(!isUrl(url)){
            el.message.innerHTML = '這不是一個有效的 HTTP 網址 (可能忘記於開頭添加 <span class="ts-text is-mark">http(s)://</span>)';
            el.copyBtn.style.display = 'none';
            return false;
        }
        // if url is current site
        if(url.startsWith('http://'+domain+root) || url.startsWith('https://'+domain+root)){
            el.message.innerHTML = '請不要嘗試縮短短網址，不然短網址就會變成短網址中的短網址!@#$%^&*()_+...';
            el.copyBtn.style.display = 'none';
            return false;
        }
        // 
        isSubmitting(true);
        console.log(url);
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
                newUrl = `${protocol}://${domain}${root}${resp.data}`;
                el.originUrl.value = '';
                el.message.innerHTML = "短網址：";
                el.newUrl.innerHTML = `<a class="ts-text is-link is-external-link" href="${newUrl}" target="_blank">${newUrl}</a>`;
                el.copyBtn.style.display = "inline";
            }else{
                el.message.innerHTML = resp.message;
                el.copyBtn.style.display = 'none';
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
    }
</script>

<?php  Inc::component('footer'); ?>