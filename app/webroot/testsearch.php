<pre>
<?php
class Object{};

include("../controllers/components/search_helper.php");
$helper = new OlinHelperComponent();
print_r($helper->buildQuery('bananas foster'));
?>
