<?php
 
define('MAGENTO', realpath(dirname(__FILE__)));
require_once MAGENTO . '/app/Mage.php';
 
umask(0);
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
$count = 0;
 
$file = fopen('./var/import/scatlist.csv', 'r');
while (($line = fgetcsv($file)) !== FALSE) { $count++;
//$line is an array ovaf the csv elements
/*print('<pre>');
 var_dump($line);
 print('</pre>');

*/
if ($count==1) {continue;}
if (!empty($line[0]) && !empty($line[1])) {
 
$data['general']['path'] = $line[0];
$data['general']['name'] = $line[1];
$data['general']['meta_title'] = $line[2];
$data['general']['meta_description'] = $line[3];
$data['general']['is_active'] =$line[4];
$data['general']['url_key'] = $line[5];
$data['general']['display_mode'] = "PRODUCTS";
$data['general']['is_anchor'] = $line[6];
 
$data['category']['parent'] = $line[0]; // 3 top level
$storeId = 0;
 
createCategory($data,$storeId);
sleep(0.5);
unset($data);
}
 
}
 
function createCategory($data,$storeId) {
 
echo "Starting {$data['general']['name']} [{$data['category']['parent']}] ...";
 
$category = Mage::getModel('catalog/category');
$category->setStoreId($storeId);
 
if (is_array($data)) {
$category->addData($data['general']);
 
if (!$category->getId()) {
 
$parentId = $data['category']['parent'];
if (!$parentId) {
if ($storeId) {
$parentId = Mage::app()->getStore($storeId)->getRootCategoryId();
}
else {
$parentId = Mage_Catalog_Model_Category::TREE_ROOT_ID;
}
}
$parentCategory = Mage::getModel('catalog/category')->load($parentId);
$category->setPath($parentCategory->getPath());
 
}
 
if ($useDefaults = $data['use_default']) {
foreach ($useDefaults as $attributeCode) {
$category->setData($attributeCode, null);
}
}
 
$category->setAttributeSetId($category->getDefaultAttributeSetId());
 
if (isset($data['category_products']) &&
!$category->getProductsReadonly()) {
$products = array();
parse_str($data['category_products'], $products);
$category->setPostedProducts($products);
}
 
try {
$category->save();
echo "Suceeded <br /> ";
}
catch (Exception $e){
echo "Failed <br />";
 
}
}
 
}
