<?php
require_once "lib/class_DetectarNavegacio.php";
$navegacio=new DetectarNavegacio();
echo $navegacio->info_txt;

?>
<script>
    alert(window.innerWidth + 'x'  + window.innerHeight);
</script>
<?php
die();
?>