<script type="text/javascript">
    function onDrawTable(){
        $('.btn-delete').confirmation({
            //rootSelector: '[data-toggle=confirmation]',
            title: "<?=lang('Hapus data ini ?')?>",
            rootSelector: '.btn-delete',
            // other options
        });	
    }        
</script>