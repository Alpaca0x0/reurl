<?php echo '[Lab Page]<br>'; ?>

<script type="module">
    import '<?=Uri::js('ajax')?>';
    import * as Resp from '<?=Uri::js('resp')?>';
    // 
    let info = {
        type: null,
        title: null,
        msg: null,
    };
    // 
    $.ajax({
        type: "GET",
        url: '<?=htmlentities(Uri::api('count'))?>',
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
            // 
        }else{
            // 
        }
    }).always((resp) => {
        // 
    });
</script>
