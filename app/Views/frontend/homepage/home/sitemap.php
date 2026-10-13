
<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
<!-- created with Free Online Sitemap Generator www.xml-sitemaps.com -->

<url>
   <loc><?php echo BASE_URL; ?></loc>
   <priority>1.00</priority>
</url>
<?php if(isset($articleCatalogue) && is_array($articleCatalogue) && count($articleCatalogue)){ ?>
<?php foreach($articleCatalogue as $keyMain => $valMain){ ?>
<?php
$title = htmlspecialchars($valMain['title']);
$url = BASE_URL.write_url($valMain['canonical'])
?>
<url>
   <loc><?php echo $url;?></loc>
   <priority>0.80</priority>
</url>
<?php } ?>
<?php } ?>
<?php if(isset($article) && is_array($article) && count($article)){ ?>
<?php foreach($article as $keyMain => $valMain){ ?>
<?php
$title = htmlspecialchars($valMain['title']);
$url = BASE_URL.write_url($valMain['canonical'])
?>
<url>
   <loc><?php echo $url;?></loc>
   <priority>0.80</priority>
</url>
<?php }} ?>
<?php if(isset($projectCatalogue) && is_array($projectCatalogue) && count($projectCatalogue)){ ?>
<?php foreach($projectCatalogue as $keyMain => $valMain){ ?>
<?php
$title = htmlspecialchars($valMain['title']);
$url = BASE_URL.write_url($valMain['canonical'])
?>
<url>
   <loc><?php echo $url;?></loc>
   <priority>0.80</priority>
</url>
<?php }} ?>
<?php if(isset($project) && is_array($project) && count($project)){ ?>
<?php foreach($project as $keyMain => $valMain){ ?>
<?php
$title = htmlspecialchars($valMain['title']);
$url = BASE_URL.write_url($valMain['canonical'])
?>
<url>
   <loc><?php echo $url;?></loc>
   <priority>0.80</priority>
</url>
<?php }} ?>
<?php if(isset($productCatalogue) && is_array($productCatalogue) && count($productCatalogue)){ ?>
<?php foreach($productCatalogue as $keyMain => $valMain){ ?>
<?php
$title = htmlspecialchars($valMain['title']);
$url = BASE_URL.write_url($valMain['canonical'])
?>
<url>
   <loc><?php echo $url;?></loc>
   <priority>0.80</priority>
</url>
<?php }} ?>
<?php if(isset($product) && is_array($product) && count($product)){ ?>
<?php foreach($product as $keyMain => $valMain){ ?>
<?php
$title = htmlspecialchars($valMain['title']);
$url = BASE_URL.write_url($valMain['canonical'])
?>
<url>
   <loc><?php echo $url;?></loc>
   <priority>0.80</priority>
</url>
<?php }} ?>
</urlset>
