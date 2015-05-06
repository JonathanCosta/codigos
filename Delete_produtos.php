<?php
 require_once  'app/Mage.php';
Varien_Profiler::enable();
Mage::setIsDeveloperMode(true);
ini_set('display_errors', 1);
umask(0);
$currentStore = Mage::app()->getStore()->getId();
Mage::app()->setUpdateMode(0);
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

//You code to Do what you Want

Mage::app()->getStore()->setId($currentStore);
Mage::app()->setUpdateMode(1);
Mage::register('isSecureArea', 1);
set_time_limit(0);

function deleteAllProducts()
{

 

//$collection = Mage::getResourceModel('catalog/product_collection')
  //  ->addIdFilter(array(1,2,3,4,5,6,7,8,6312,6313,6314,6315,6316,6317,6318,6319),true);
	
	$_productCollection = Mage::getResourceModel('catalog/product_collection')->addIdFilter(array(1,2,3,4,5,6,7,8,6312,6313,6314,6315,6316,6317,6318,6319),true)->addAttributeToSelect('*')->load();
	
foreach ($_productCollection as $product) {
	
	$_product = Mage::getModel('catalog/product')->load($product->getId());
	
echo $_product->getId() . " - " . $_product->getName() . "<br>";
try
{
	$product->delete();
	echo "Produto ID: ". $product->getId() ." Deletado com sucesso<br /><hr /><br />";



}
catch (Exception $e)
{
	echo  $e;
echo "Ocorreu um erro ao apagar o produto ID: ". $product->getId() ."<br /><hr /><br />";
}
}
}
deleteAllProducts();
?> <!--more-->
