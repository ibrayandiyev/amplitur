<script>

<?php
    $language = app()->getLocale();
    $file = include_once(app_path()."/../resources/lang/{$language}/javascript.php");
    $language = str_replace("-", "_", $language);

?>
i18next.init({
    lng:'<?=$language?>',
    debug:true,
    resources:{
        "<?=$language?>": {
            translation: 
            <?=json_encode($file)?>
		}
    }
});
</script>    