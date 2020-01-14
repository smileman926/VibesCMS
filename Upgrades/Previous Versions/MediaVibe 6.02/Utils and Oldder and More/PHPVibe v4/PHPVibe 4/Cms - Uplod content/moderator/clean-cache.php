<?php

$debug = $db->clean_cache();
foreach ($debug as $d) {
echo $d;
}
$debug2 = $db->clean_cache(true);
foreach ($debug2 as $d2) {
echo $d2;
}
echo '<div class="msg-win">Cache cleared</div>';

?>