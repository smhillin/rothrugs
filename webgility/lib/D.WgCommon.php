<?php
/*
===================================
© Copyright 2007-2015 © webgility Inc. all rights reserved.
----------------------------------------
This file and the source code contained herein are the property of Webgility LLC
and are protected by United States copyright law. All usage is restricted as per 
the terms & conditions of Webgility License Agreement. You may not alter or remove 
any trademark, copyright or other notice from copies of the content.

The code contained herein may not be reproduced, copied, modified or redistributed in any form
without the express written consent by an officer of Webgility LLC.


===================================
*/
class WgCommon
{
	public function parseRequest()
	{

		$request ='';
		$request = 	$this->getRequestData($_POST['request']);

		if($request)
		{

			$request = json_decode($request,true);
			foreach($request as $k=>$v)
			{
				$$k = $v;
			}
		}

		$others = isset($others) ? $others:"";
		$itemid = isset($itemid) ? $itemid:"";
		if(!empty($method))
		{
			switch ($method)
			{
				case 'checkAccessInfo':
				case 'getStores':
				case 'isAuthorized':
				case 'getVersions':
				echo $str = $this->$method($username,$password,$others);
				break;
				
				case 'UpgradeVersions':
				echo $str = $this->$method($username,$password,$others,$url);
				break;

				case 'getItems':					
				echo $str =$this->$method($username,$password,$DownloadType,$UpdatedDate,$start_item_no,$limit,$datefrom,$OptionsBoost,$others);
				break;

				case 'getAttributesets':
				case 'getShippingMethods':
				case 'getManufacturers':
				case 'getTaxes':
				case 'getOrderStatusForOrder':
				case 'getShippingMethods':
				case 'getPaymentMethods':
				case 'getCompanyInfo':
				case 'getOrderStatus':
				case 'getCategory':
				case 'getCustomerGroup':
				echo $str = $this->$method($username,$password,$storeid,$others);
				break;

				case 'getOrders':
				case 'getSelectedOrders':
				$method ='getOrders';	
				echo $str = $this->$method($username,$password,$datefrom,$start_order_no,$ecc_excl_list,$order_per_response,$LastModifiedDate,$storeid,$SelectedOrders,$others['CCDetails'],$others['MaxOrderNoInBatch'],$OptionsBoost);
				break;
				
				
				case 'synchronizeItems':
				echo $str = $this->$method($username,$password,$data,$storeid,$OptionsBoost,$Other['SyncType']);
				break;

				case 'UpdateOrdersShippingStatus':
				echo $str = $this->$method($username,$password,$data,$statustype,$storeid,$others);
				break;
				
				case 'AutoSyncOrder':
				echo $str = $this->$method($username,$password,$data,$statustype,$storeid,$others);
				break;

				case 'UpdateOrdersStatusAcknowledge':
				echo $str = $this->$method($username,$password,$data,$others) ;
				break;

				case 'addProduct':
				case 'addCustomers':
				echo $str = $this->$method($username,$password,$data,$storeid,$others) ;
				break;

				# NOT BASE
				case 'getItemsByName':
				echo $str =$this->$method($username,$password,$start_item_no,$limit,$itemname ,$storeid,$others);
				break;

				case 'getItemsQuantity':
				echo $str = $this->$method($username,$password,$itemid,$storeid,$others);
				break;

				case 'getCustomers':
				echo $str = $this->$method($username,$password,$datefrom,$customerid,$limit,$storeid,$others);
				break;

				case 'getVisibilityStatus':
				echo $str = $method($username,$password,$storeid,$others);
				break;
				
				case 'GetImage':
				echo $str = $this->$method($username,$password,$data,$storeid,$others);
				break;
				
				case 'getCustomersNew':
				echo $str = $this->$method($username,$password,$datefrom,$customerid,$limit,$storeid=1,$others);
				break;
			}
		}
	}

	function getRequestData($s1)
    {        
        if(isset($_POST["iscompress"]) && $_POST["iscompress"]=='false')
        {
            return $s1;
        }
        $cipher_alg = MCRYPT_RIJNDAEL_128;
        $key = "d994e5503a58e025";
        $hexiv="d994e5503a58e02525a8fc5eef45223e";
        $enc_string = mcrypt_decrypt($cipher_alg, $key,base64_decode($s1) , MCRYPT_MODE_CBC, trim($this->hexToString(trim($hexiv))));
        return $comp_string = @gzinflate(base64_decode($enc_string));
        
    }
    function response($responseArray) {
    
    		foreach($responseArray as $key=>$value)
		{
			if(is_array($value))
			{
				foreach($value as $k=>$v)
				{
					if(is_array($v))
					{
						foreach($v as $arrk=>$arrv)
						{
							if(is_array($arrv))
								{
									foreach($arrv as $lastk=>$lastv)
									{
										if(is_array($lastv))
										{
											foreach($lastv as $finalk=>$finalv)
											{
												if(is_array($finalv))
												{
													foreach($finalv as $finallastk=>$finallastv)
													{
														if(is_array($finallastv))
														{
															foreach($finallastv as $finalkey=>$finalval)
															{
																if(is_array($finalval))
																{
																	$finalval=utf8_encode(iconv("UTF-8", "ISO-8859-1//TRANSLIT", $finalval));  
																}
																else
																{
																	$finalval=utf8_encode(iconv("UTF-8", "ISO-8859-1//TRANSLIT", $finalval));  
																}
																$finallastv[$finalkey]=$finalval;
															}
															
														}
														else
														{
															$finallastv=utf8_encode(iconv("UTF-8", "ISO-8859-1//TRANSLIT", $finallastv)); 
														}
														$finalv[$finallastk]=$finallastv;
													}
													
												}
												else
												{
													$finalv=utf8_encode(iconv("UTF-8", "ISO-8859-1//TRANSLIT", $finalv)); 
												}
												$lastv[$finalk]=$finalv;
											}
											
										}
										else
										{
											$lastv=utf8_encode(iconv("UTF-8", "ISO-8859-1//TRANSLIT", $lastv)); 
	
										}
										$arrv[$lastk]=$lastv;
									}					
								}
								else
								{
									$arrv=utf8_encode(iconv("UTF-8", "ISO-8859-1//TRANSLIT", $arrv));
									
								}
							$v[$arrk]=$arrv;
						}					
					}
					else
					{
					
						$v=utf8_encode(iconv("UTF-8", "ISO-8859-1//TRANSLIT", $v)); 
					}
					$value[$k]=$v;
				}					
			}
			else
			{
				$value=utf8_encode(iconv("UTF-8", "ISO-8859-1//TRANSLIT", $value)); 
			}
			$responseArray[$key]=$value;
		}
        $str = json_encode($responseArray);
        if(isset($_POST["iscompress"]) && $_POST["iscompress"]=='false')
        {
            return $str;
        }        
        $cipher_alg = MCRYPT_RIJNDAEL_128;
        $key = "d994e5503a58e025";
        $hexiv="d994e5503a58e02525a8fc5eef45223e";
        $comp_string = base64_encode(gzdeflate($str,9));
        $enc_string = mcrypt_encrypt($cipher_alg, $key,$comp_string , MCRYPT_MODE_CBC, trim($this->hexToString(trim($hexiv))));
        return base64_encode($enc_string);
            
        
    } 
	 function stringToHex($str) {
		$hex="";
		$zeros = "";
		$len = 2 * strlen($str);
		for ($i = 0; $i < strlen($str); $i++){
			$val = dechex(ord($str{$i}));
			if( strlen($val)< 2 ) $val="0".$val;
			$hex.=$val;
		}
		for ($i = 0; $i < $len - strlen($hex); $i++){
			$zeros .= '0';
		}
		return $hex.$zeros;
	}

	 function hexToString($hex) {
		$str="";
		for($i=0; $i<strlen($hex); $i=$i+2 ) {
			$temp = hexdec(substr($hex, $i, 2));
			if (!$temp) continue;
			$str .= chr($temp);
		}
		return $str;
	}
}

class WgBaseResponse extends WgCommon
{
	private $responseArray = array();
	public function setStatusCode($StatusCode)
	{
		$this->responseArray['StatusCode'] = $StatusCode?$StatusCode:0;
	}
	public function setVersion($StatusCode)
	{
		$this->responseArray['Version'] = $StatusCode?$StatusCode:0;
	}
	public function setStatusMessage($StatusMessage)
	{
		$this->responseArray['StatusMessage'] =$StatusMessage?$StatusMessage:'';
	}
	public function getBaseResponse()
	{
		return $this->responseArray;
	}
}
class WG_CompanyInfo
{
	private $responseArray = array();
	public function setStatusCode($StatusCode)
	{
		$this->responseArray['StatusCode'] = $StatusCode?$StatusCode:0;
	}
	public function setStatusMessage($StatusMessage)
	{
		$this->responseArray['StatusMessage'] = $StatusMessage?$StatusMessage:'';
	}

	public function setStoreID($StoreID)
	{
		$this->responseArray['StoreID'] =$StoreID ? $StoreID :"" ;
	}
	public function setStoreName($StoreName)
	{
		$this->responseArray['StoreName'] =$StoreName?$StoreName:"";
	}

	public function setAddress($Address)
	{
		$this->responseArray['Address'] =$Address ? $Address :"";
	}
	
	public function setAddress2($Address2)
	{
		$this->responseArray['Address2'] =$Address2;
	}
	
	public function setcity($city)
	{
		$this->responseArray['city'] = $city ? $city :"";
	}

	public function setState($State)
	{
		$this->responseArray['State'] =$State ? $State : "";
	}

	public function setCountry($Country)
	{
		$this->responseArray['Country'] = $Country ? $Country : "";
	}

	public function setZipcode($Zipcode)
	{
		$this->responseArray['Zipcode'] = $Zipcode ? $Zipcode : "";
	}

	public function setPhone($Phone)
	{
		$this->responseArray['Phone'] =$Phone ? $Phone :"";
	}

	public function setFax($Fax)
	{
		$this->responseArray['Fax'] =$Fax ? $Fax : "";
	}
	
	public function setEmail($Email)
	{
		$this->responseArray['Email'] =$Email ? $Email : "";
	}

	public function setWebsite($Website)
	{
		$this->responseArray['Website'] =$Website ? $Website : "";
	}

	public function getCompanyInfo()
	{
		return $this->responseArray;
	}

}

class Wg_StoresInfo
{
	private $responseArray = array();
	private $stores = array();

	public function setStatusCode($StatusCode)
	{
		$this->responseArray['StatusCode'] = $StatusCode?$StatusCode:0;
	}
	public function setStatusMessage($StatusMessage)
	{
		$this->responseArray['StatusMessage'] = $StatusMessage?$StatusMessage:'';
	}

	public function setstores($Stores)
	{
		$this->stores[] = $Stores?$Stores:'';
	}

	public function getStoresInfo()
	{
		$this->responseArray['Stores'] = $this->stores?$this->stores:'';
		return $this->responseArray;
	}


}

class WG_Store
{
	private $Store = array();

	public function setStoreID($StoreID)
	{
		$this->Store['StoreID'] = $StoreID ? $StoreID :"";
	}
	public function setStoreName($StoreName)
	{
		$this->Store['StoreName'] = $StoreName ? $StoreName : "";
	}
	public function setStoreWebsiteId($StoreWebsiteId)
	{
		$this->Store['StoreWebsiteId'] = $StoreWebsiteId ? $StoreWebsiteId : "";
	}
	public function setStoreWebsiteName($StoreWebsiteName)
	{
		$this->Store['StoreWebsiteName'] = $StoreWebsiteName ? $StoreWebsiteName : "";
	}
	public function setStoreRootCategoryId($StoreRootCategoryId)
	{
		$this->Store['StoreRootCategoryId'] = $StoreRootCategoryId ? $StoreRootCategoryId : "";
	}
	public function setStoreDefaultStoreId($StoreDefaultStoreId)
	{
		$this->Store['StoreDefaultStoreId'] = $StoreDefaultStoreId ? $StoreDefaultStoreId : "";
	}
	public function getStore()
	{
		return $this->Store;
	}

}

class WG_PaymentMethods
{
	private $responseArray = array();
	private $paymentMethods = array();

	public function setStatusCode($StatusCode)
	{
		$this->responseArray['StatusCode'] = $StatusCode?$StatusCode:0;
	}
	public function setStatusMessage($StatusMessage)
	{
		$this->responseArray['StatusMessage'] = $StatusMessage?$StatusMessage:'';
	}

	public function setPaymentMethods($paymentMethods)
	{
		$this->paymentMethods[] = $paymentMethods?$paymentMethods:'';
	}

	public function getPaymentMethods()
	{
		$this->responseArray['PaymentMethods'] = $this->paymentMethods?$this->paymentMethods:'';
		return $this->responseArray;
	}

}

class WG_PaymentMethod
{
	private $paymentMethod = array();


	public function setMethodId($MethodId)
	{
		$this->paymentMethod['MethodId'] = $MethodId?$MethodId:'';
	}
	public function setMethod($Method)
	{
		$this->paymentMethod['Method'] = $Method?$Method:'';
	}
	public function setDetail($Detail)
	{
		$this->paymentMethod['Detail'] = $Detail?$Detail:'';
	}

	public function getPaymentMethod()
	{
		return $this->paymentMethod;
	}

}

class WG_ShippingMethods
{
	private $responseArray = array();
	private $shippingMethods = array();

	public function setStatusCode($StatusCode)
	{
		$this->responseArray['StatusCode'] = $StatusCode?$StatusCode:0;
	}
	public function setStatusMessage($StatusMessage)
	{
		$this->responseArray['StatusMessage'] = $StatusMessage?$StatusMessage:'';
	}

	public function setShippingMethods($shippingMethods)
	{
		$this->shippingMethods[] = $shippingMethods?$shippingMethods:'';
	}

	public function getShippingMethods()
	{
		$this->responseArray['ShippingMethods'] = $this->shippingMethods?$this->shippingMethods:'';
		return $this->responseArray;
	}

}

class WG_ShippingMethod
{
	private $ShippingMethod = array();
	public function setCarrier($Carrier)
	{
		$this->ShippingMethod['Carrier'] = $Carrier?$Carrier:'';
	}
	public function setMethods($Methods)
	{
		$this->ShippingMethod['Methods'][] = $Methods?$Methods:'';
	}

	public function getShippingMethod()
	{
		return $this->ShippingMethod;
	}

}

class WG_Categories
{
	private $responseArray = array();
	private $Categories = array();

	public function setStatusCode($StatusCode)
	{
		$this->responseArray['StatusCode'] = $StatusCode?$StatusCode:0;
	}
	public function setStatusMessage($StatusMessage)
	{
		$this->responseArray['StatusMessage'] = $StatusMessage?$StatusMessage:'';
	}

	public function setCategories($Category)
	{
		$this->Categories[] = $Category?$Category:'';
	}

	public function getCategories()
	{
		$this->responseArray['Categories'] = $this->Categories;
		return $this->responseArray;
	}

}

class WG_Category
{
	private $Category = array();

	public function setCategoryID($CategoryID)
	{
		$this->Category['CategoryID'] = $CategoryID ? $CategoryID :"";
	}
	public function setCategoryName($CategoryName)
	{
		$this->Category['CategoryName'] = $CategoryName ? $CategoryName :"";
	}
	public function setParentID($ParentID)
	{
		$this->Category['ParentID'] = $ParentID ? $ParentID : "";
	}

	public function getCategory()
	{
		return $this->Category;
	}

}

class WG_Taxes
{
	private $responseArray = array();
	private $Taxes = array();

	public function setStatusCode($StatusCode)
	{
		$this->responseArray['StatusCode'] = $StatusCode?$StatusCode:0;
	}
	public function setStatusMessage($StatusMessage)
	{
		$this->responseArray['StatusMessage'] =$StatusMessage?$StatusMessage:'';
	}

	public function setTaxes($Tax)
	{
		$this->Taxes[] = $Tax?$Tax:'';
	}

	public function getTaxes()
	{
		$this->responseArray['Taxes'] = $this->Taxes;
		return $this->responseArray;
	}
}

class WG_Tax
{
	private $Tax = array();

	public function setTaxID($TaxID)
	{
		$this->Tax['TaxID'] = $TaxID ? $TaxID :"";
	}

	public function setTaxName($TaxName)
	{
		$this->Tax['TaxName'] = $TaxName ? $TaxName :"";
	}

	public function getTax()
	{
		return $this->Tax;
	}
}

class WG_Manufacturers
{
	private $responseArray = array();
	private $Manufacturers = array();

	public function setStatusCode($StatusCode)
	{
		$this->responseArray['StatusCode'] = $StatusCode?$StatusCode:0;
	}
	public function setStatusMessage($StatusMessage)
	{
		$this->responseArray['StatusMessage'] = $StatusMessage?$StatusMessage:'';
	}

	public function setManufacturers($Manufacturer)
	{
		$this->Manufacturers[] = $Manufacturer?$Manufacturer:'';
	}

	public function getManufacturers()
	{
		$this->responseArray['Manufacturers'] = $this->Manufacturers;
		return $this->responseArray;
	}
}

class WG_Manufacturer
{
	private $Manufacturer = array();

	public function setManufacturerID($ManufacturerID)
	{
		$this->Manufacturer['ManufacturerID'] = $ManufacturerID ? $ManufacturerID :"";
	}

	public function setManufacturerName($ManufacturerName)
	{
		$this->Manufacturer['ManufacturerName'] = $ManufacturerName ? $ManufacturerName :"";
	}

	public function getManufacturer()
	{
		return $this->Manufacturer;
	}
}

class WG_OrderStatuses
{
	private $responseArray = array();
	private $OrderStatuses = array();

	public function setStatusCode($StatusCode)
	{
		$this->responseArray['StatusCode'] = $StatusCode?$StatusCode:0;
	}
	public function setStatusMessage($StatusMessage)
	{
		$this->responseArray['StatusMessage'] = $StatusMessage?$StatusMessage:'';
	}

	public function setOrderStatuses($OrderStatus)
	{
		$this->OrderStatuses[] = $OrderStatus?$OrderStatus:'';
	}

	public function getOrderStatuses()
	{
		$this->responseArray['OrderStatuses'] = $this->OrderStatuses; 
		return $this->responseArray;
	}
}

class WG_OrderStatus
{
	private $OrderStatus = array();

	public function setOrderStatusID($OrderStatusID)
	{
		$this->OrderStatus['OrderStatusID'] = $OrderStatusID ? $OrderStatusID :"";
	}

	public function setOrderStatusName($OrderStatusName)
	{
		$this->OrderStatus['OrderStatusName'] = $OrderStatusName ? $OrderStatusName :"";
	}

	public function getOrderStatus()
	{
		return $this->OrderStatus;
	}
}



class WG_Items
{
	private $responseArray = array();
	private $Items = array();

	public function setStatusCode($StatusCode)
	{
		$this->responseArray['StatusCode'] = $StatusCode?$StatusCode:0;
	}
	public function setStatusMessage($StatusMessage)
	{
		$this->responseArray['StatusMessage'] = $StatusMessage?$StatusMessage:'';
	}
	public function setTotalRecordFound($TotalRecordFound)
	{
		$this->responseArray['TotalRecordFound'] =$TotalRecordFound?$TotalRecordFound:0;;
	}
	public function setTotalRecordSent($TotalRecordSent)
	{
		$this->responseArray['TotalRecordSent'] = $TotalRecordSent?$TotalRecordSent:0;
	}
		public function setServertime($date)
	{

		$this->responseArray['Servertime'] = $date?$date:'';
	}
	public function setItems($Items1)
	{

		$this->Items[] = $Items1?$Items1:'';
	}

	public function getItems()
	{
		$this->responseArray['Items'] = $this->Items;
		return $this->responseArray;
	}


}
class WG_Item
{
	private $Item = array();

	public function setItemID($ItemID)
	{
		$this->Item['ItemID'] = $ItemID ? $ItemID :"";
	}
	public function setItemCode($ItemCode)
	{
		$this->Item['ItemCode'] = $ItemCode ? $ItemCode :"";
	}
	public function setItemDescription($ItemDescription)
	{
		$this->Item['ItemDescription'] = $ItemDescription ? $ItemDescription : "";
	}
	public function setItemShortDescr($ItemShortDescr)
	{
		$this->Item['ItemShortDescr'] = $ItemShortDescr ? $ItemShortDescr : "";
	}
	public function setCategories($Categories)
	{
		$this->Item['Categories'][] = $Categories ? $Categories : "";
	}
	public function setManufacturer($Manufacturer)
	{
		$this->Item['manufacturer'] = $Manufacturer ? $Manufacturer : "";
	}
	public function setQuantity($Quantity)
	{
		$this->Item['Quantity'] = $Quantity ? $Quantity : 0;
	}
	public function setUnitPrice($UnitPrice)
	{
		$this->Item['UnitPrice'] = $UnitPrice ? $UnitPrice : 0;
	}
	public function setListPrice($ListPrice)
	{
		$this->Item['ListPrice'] = $ListPrice ? $ListPrice : 0;
	}
	public function setWeight($Weight)
	{
		$this->Item['Weight'] = $Weight ? $Weight : 0;
	}
	public function setLowQtyLimit($LowQtyLimit)
	{
		$this->Item['LowQtyLimit'] = $LowQtyLimit ? $LowQtyLimit : 0;
	}
	public function setFreeShipping($FreeShipping)
	{
		$this->Item['FreeShipping'] = $FreeShipping ? $FreeShipping : 0;
	}
	public function setDiscounted($Discounted)
	{
		$this->Item['Discounted'] = $Discounted ? $Discounted : 0;
	}
	public function setShippingFreight($ShippingFreight)
	{
		$this->Item['ShippingFreight'] = $ShippingFreight ? $ShippingFreight : 0;
	}
	public function setWeight_Symbol($Weight_Symbol)
	{
		$this->Item['Weight_Symbol'] = $Weight_Symbol ? $Weight_Symbol : 0;
	}

	public function setWeight_Symbol_Grams($Weight_Symbol_Grams)
	{
		$this->Item['Weight_Symbol_Grams'] = $Weight_Symbol_Grams ? $Weight_Symbol_Grams : 0;
	}
	public function setTaxExempt($setTaxExempt)
	{
		$this->Item['setTaxExempt'] = $setTaxExempt ? $setTaxExempt : 0;
	}

	public function setUpdatedAt($UpdatedAt)
	{
		$this->Item['UpdatedAt'] = $UpdatedAt ? $UpdatedAt : 0;
	}
	public function setCreatedAt($CreatedAt)
	{
		$this->Item['CreatedAt'] =$CreatedAt;
	}
	public function setImageUrl($ImageUrl)
	{
		$this->Item['ImageUrl'] = $ImageUrl ? $ImageUrl : '';
	}

	public function setItemVariants($ItemVariants)
	{
		$this->Item['ItemVariants'][] = $ItemVariants ? $ItemVariants : '';
	}

	public function setItemOptions($ItemOptions)
	{
		$this->Item['ItemOptions'][] = $ItemOptions ? $ItemOptions : '';
	}

	#Extra node fro Orders

	public function setShippedQuantity($ShippedQuantity)
	{
		$this->Item['ShippedQuantity'] = $ShippedQuantity ? $ShippedQuantity : 0;
	}
	public function setOneTimeCharge($OneTimeCharge)
	{
		$this->Item['OneTimeCharge'] = $OneTimeCharge ? $OneTimeCharge : 0;
	}
	public function setItemTaxAmount($ItemTaxAmount)
	{
		$this->Item['ItemTaxAmount'] = $ItemTaxAmount ? $ItemTaxAmount : 0;
	}


	#Nodes used for add product
	public function setStatus($Status)
	{
		$this->Item['Status'] = $Status ? $Status : '';
	}
	public function setProductID($ProductID)
	{
		$this->Item['ProductID'] = $ProductID ? $ProductID : '';
	}
	public function setSku($Sku)
	{
		$this->Item['Sku'] = $Sku ? $Sku : '';
	}

	public function setProductName($ProductName)
	{
		$this->Item['ProductName'] = $ProductName ? $ProductName : '';
	}

    
	public function setDimention($Dimention)
	{
		if($Dimention)
		$this->Item['Dimention']=$Dimention;
		else 
		$this->Item['Dimention']="";
	}
	

	public function getItem()
	{

		return $this->Item;
	}
}
class WG_Attributesets
{
	private $responseArray = array();
	private $Attributesets = array();

	public function setStatusCode($StatusCode)
	{
		$this->responseArray['StatusCode'] = $StatusCode?$StatusCode:0;
	}
	public function setStatusMessage($StatusMessage)
	{
		$this->responseArray['StatusMessage'] = $StatusMessage?$StatusMessage:'';
	}

	public function setAttributesets($Attributeset)
	{
		$this->Attributesets[] = $Attributeset?$Attributeset:'';
	}

	public function getAttributesets()
	{
		$this->responseArray['Attributesets'] = $this->Attributesets;
		return $this->responseArray;
	}
}

class WG_Attributeset
{
	private $Attributeset = array();

	public function setAttributesetID($AttributesetID)
	{
		$this->Attributeset['AttributesetID'] = $AttributesetID ? $AttributesetID :"";
	}

	public function setAttributesetName($AttributesetName)
	{
		$this->Attributeset['AttributesetName'] = $AttributesetName ? $AttributesetName :"";
	}

	public function getAttributeset()
	{
		return $this->Attributeset;
	}
}

class WG_Variants
{
	private $responseArray = array();
	private $ItemVariants = array();
	public function setItemVariants($Variants1)
	{

		$this->ItemVariants[] = $Variants1;
	}

	
	public function getVariants()
	{
		$this->responseArray['ItemVariants'] = $this->ItemVariants;
		return $this->responseArray;
	}
}

class WG_Variant
{

	private $ItemVariant = array();

	
	public function setItemCode($ItemCode)
	{
		$this->ItemVariant['ItemCode'] = $ItemCode ? $ItemCode :"";
	}
	public function setVarientID($VarientID)
	{
		$this->ItemVariant['VarientID'] = $VarientID ? $VarientID :"";
	}
	public function setVariantSku($VarientSku)
	{
		$this->ItemVariant['Sku'] = $VarientSku ? $VarientSku :"";
	}
	public function setQuantity($Quantity)
	{
		$this->ItemVariant['Quantity'] = $Quantity ? $Quantity : "";
	}
	public function setUnitPrice($UnitPrice)
	{
		$this->ItemVariant['UnitPrice'] = $UnitPrice ? $UnitPrice : "";
	}
	public function setWeight($Weight)
	{
		$this->ItemVariant['Weight'] = $Weight ? $Weight : "";
	}
	public function setStatus($Status)
    {
        $this->ItemVariant['Status'] = $Status ? $Status : "";
    }
	public function getVariant()
	{

		return $this->ItemVariant;
	}

}
class WG_Options
{
	private $responseArray = array();
	private $ItemOptions = array();
	public function setItemOptions($Options1)
	{

		$this->ItemOptions[] = $Options1;
	}

	
	public function getOptions()
	{
		$this->responseArray['ItemVariants'] = $this->ItemOptions;
		return $this->responseArray;
	}
}

class WG_Option
{


}
class WG_Orders
{

	private $responseArray = array();
	private $Orders = array();

	public function setStatusCode($StatusCode)
	{
		$this->responseArray['StatusCode'] = $StatusCode?$StatusCode:0;
	}
	public function setStatusMessage($StatusMessage)
	{
		$this->responseArray['StatusMessage'] = $StatusMessage?$StatusMessage:'';
	}
	public function setTotalRecordFound($TotalRecordFound)
	{
		$this->responseArray['TotalRecordFound'] = $TotalRecordFound?$TotalRecordFound:0;
	}
	public function setTotalRecordSent($TotalRecordSent)
	{
		$this->responseArray['TotalRecordSent'] = $TotalRecordSent?$TotalRecordSent:0;
	}
	public function setMaxOrderNoInBatch($MaxOrderNoInBatch)
	{
		$this->responseArray['MaxOrderNoInBatch'] =$MaxOrderNoInBatch;
	}
	
	public function setOrders($Order)
	{
		$this->Orders[] = $Order;
	}

	public function getOrders()
	{
		$this->responseArray['Orders'] = $this->Orders;
		return $this->responseArray;
	}

}
class WG_Order
{
	private $responseArray = array();
	private $Order = array();

	public function setOrderId($OrderId)
	{
		$this->Order['OrderId'] = $OrderId?$OrderId:'';
	}

	public function setTitle($Title)
	{
		$this->Order['Title'] =$Title?$Title:'';
	}

	public function setFirstName($FirstName)
	{
		$this->Order['FirstName'] =$FirstName?$FirstName:'';
	}

	public function setLastName($LastName)
	{
		$this->Order['LastName'] =$LastName?$LastName:'';
	}

	public function setDate($Date)
	{
		$this->Order['Date'] =$Date?$Date:'';
	}
	public function setLastModifiedDate($Date)
	{
		$this->Order['LastModifiedDate'] =$Date;
	}
	public function setTime($Time)
	{
		$this->Order['Time'] =$Time?$Time:'';
	}

	public function setStoreID($StoreID)
	{
		$this->Order['StoreID'] =$StoreID?$StoreID:'';
	}
	public function setStoreName($StoreName)
	{
		$this->Order['StoreName'] =$StoreName?$StoreName:'';
	}
	public function setCurrency($Currency)
	{
		$this->Order['Currency'] =$Currency?$Currency:'';
	}
	public function setWeight_Symbol($Weight_Symbol)
	{
		$this->Order['Weight_Symbol'] =$Weight_Symbol?$Weight_Symbol:'';
	}
	public function setWeight_Symbol_Grams($Weight_Symbol_Grams)
	{
		$this->Order['Weight_Symbol_Grams'] =$Weight_Symbol_Grams?$Weight_Symbol_Grams:'';
	}

	public function setCustomerId($CustomerId)
	{
		$this->Order['CustomerId'] =$CustomerId?$CustomerId:'';
	}
	public function setComment($Comment)
	{
		$this->Order['Comment'] =$Comment?$Comment:'';
	}
	public function setStatus($Status)
	{
		$this->Order['Status'] =$Status?$Status:'';
	}
	public function setOrderStatus($Status)
	{
		$this->Order['OrderStatus'] =$Status?$Status:"";
	}
	
	public function setNotes($Notes)
	{
		$this->Order['Notes'] =$Notes;
	}
	public function setOrderNotes($Notes)
	{
		$this->Order['OrderNotes'] =strlen($Notes)!=0?utf8_encode($Notes):"";
	}
	
	public function setFax($Fax)
	{
		$this->Order['Fax'] =$Fax?$Fax:'';
	}
	public function setShippedOn($ShippedOn)
	{
		$this->Order['ShippedOn'] = $ShippedOn?$ShippedOn:'';
	}
	public function setShippedVia($ShippedVia)
	{
		$this->Order['ShippedVia'] = $ShippedVia?$ShippedVia:'';
	}
	public function setOrderItems($OrderItems)
	{
		$this->Order['Items'][] = $OrderItems?$OrderItems:'';
	}
	public function setOrderBillInfo($Bill)
	{
		$this->Order['Bill'] = $Bill?$Bill:'';
	}
	public function setOrderShipInfo($Ship)
	{
		$this->Order['Ship'] = $Ship?$Ship:'';
	}
	public function setOrderChargeInfo($Charges)
	{
		$this->Order['Charges'] = $Charges?$Charges:'';
	}
	public function getOrder()
	{
		return $this->Order;
	}

}
class WG_Itemoption
{

	private $responseArray = array();

	 # Nodes for item options
	public function setOptionID($OptionID)
	{
		$this->responseArray['OptionID'] = $OptionID ? $OptionID : '';
	}
	public function setOptionValue($OptionValue)
	{
		$this->responseArray['OptionValue'] = $OptionValue ? $OptionValue : '';
	}
	public function setOptionName($OptionName)
	{
		$this->responseArray['OptionName'] = $OptionName ? $OptionName : '';
	}
	public function setOptionPrice($OptionPrice)
	{
		$this->responseArray['OptionPrice'] = $OptionPrice ? $OptionPrice : '';
	}
	
	public function setOptionWeight($OptionWeight)
	{
		$this->responseArray['OptionWeight'] = $OptionWeight ? $OptionWeight : '';
	}
	

	public function getItemoption()
	{
		return $this->responseArray;
	}

}

class WG_OrderInfo
{
	private $responseArray = array();

	public function setOrderId($OrderId)
	{
		$this->responseArray['OrderId'] =$OrderId?$OrderId:"";
	}

	public function setTitle($Title)
	{
		$this->responseArray['Title'] =$Title?$Title:"";
	}

	public function setFirstName($FirstName)
	{
		$this->responseArray['FirstName'] =$FirstName?$FirstName:"";
	}

	public function setLastName($LastName)
	{
		$this->responseArray['LastName'] =$LastName?$LastName:"";
	}

	public function setDate($Date)
	{
		$this->responseArray['Date'] =$Date?$Date:"";
	}

	public function setTime($Time)
	{
		$this->responseArray['Time'] =$Time?$Time:"";
	}

	public function setStoreID($StoreID)
	{
		$this->responseArray['StoreID'] =$StoreID?$StoreID:"";
	}
	public function setStoreName($StoreName)
	{
		$this->responseArray['StoreName'] =$StoreName?$StoreName:"";
	}
	public function setCurrency($Currency)
	{
		$this->responseArray['Currency'] =$Currency?$Currency:"";
	}
	public function setWeight_Symbol($Weight_Symbol)
	{
		$this->responseArray['Weight_Symbol'] =$Weight_Symbol?$Weight_Symbol:"";
	}
	public function setWeight_Symbol_Grams($Weight_Symbol_Grams)
	{
		$this->responseArray['Weight_Symbol_Grams'] =$Weight_Symbol_Grams?$Weight_Symbol_Grams:"";
	}

	public function setCustomerId($CustomerId)
	{
		$this->responseArray['CustomerId'] =$CustomerId?$CustomerId:"";
	}
	public function setComment($Comment)
	{
		$this->responseArray['Comment'] =$Comment?$Comment:"";
	}
	public function setStatus($Status)
	{
		$this->responseArray['Status'] =$Status?$Status:"";
	}
	public function setNotes($Notes)
	{
		$this->responseArray['Notes'] =$Notes?$Notes:"";
	}
	
	public function setSalesRep($SalesRep)
	{
		$this->responseArray['SalesRep'] =$SalesRep?$SalesRep:"";
	}
	
		
	public function setFax($Fax)
	{
		$this->responseArray['Fax'] =$Fax?$Fax:"";
	}
	public function getOrderInfo()
	{
		return $this->responseArray;
	}
	
	
	
	
}
class WG_CreditCard
{

	private $responseArray = array();

	public function setCreditCardType($CreditCardType)
	{
		$this->responseArray['CreditCardType'] = $CreditCardType?$CreditCardType:'';
	}
		public function setCreditCardCharge($CreditCardCharge)
	{
		$this->responseArray['CreditCardCharge'] = $CreditCardCharge?$CreditCardCharge:'';
	}
		public function setExpirationDate($ExpirationDate)
	{
		$this->responseArray['ExpirationDate'] = $ExpirationDate?$ExpirationDate:'';
	}
		public function setCreditCardName($CreditCardName)
	{
		$this->responseArray['CreditCardName'] = $CreditCardName?$CreditCardName:'';
	}
		public function setCreditCardNumber($CreditCardNumber)
	{
		$this->responseArray['CreditCardNumber'] = $CreditCardNumber?$CreditCardNumber:'';
	}
		public function setCVV2($CVV2)
	{
		$this->responseArray['CVV2'] =$CVV2?$CVV2:'';
	}
		public function setAdvanceInfo($AdvanceInfo)
	{
		$this->responseArray['AdvanceInfo'] = $AdvanceInfo?$AdvanceInfo:'';
	}
		public function setTransactionId($TransactionId)
	{
		$this->responseArray['TransactionId'] =$TransactionId?$TransactionId:'';
	}
		public function getCreditCard()
	{
		return $this->responseArray;
	}
}
class WG_Bill
{

	private $responseArray = array();

	public function setCreditCardInfo($CreditCard)
	{
		$this->responseArray['CreditCard'] =$CreditCard;
	}

	public function setPayMethod($PayMethod)
	{
		$this->responseArray['PayMethod'] = $PayMethod?$PayMethod:'';
	}
	
	public function setPayStatus($PayStatus)
	{
		$this->responseArray['PayStatus'] =$PayStatus;      
	}
	
	
	
	
	public function setTitle($Title)
	{
		$this->responseArray['Title'] = $Title?$Title:'';
	}
	public function setFirstName($FirstName)
	{
		$this->responseArray['FirstName'] = $FirstName?$FirstName:'';
	}
	public function setLastName($LastName)
	{
		$this->responseArray['LastName'] = $LastName?$LastName:'';
	}
	public function setCompanyName($CompanyName)
	{
		$this->responseArray['CompanyName'] = $CompanyName?$CompanyName:'';
	}


	public function setAddress1($Address1)
	{
		$this->responseArray['Address1'] = $Address1?$Address1:'';
	}
	public function setAddress2($Address2)
	{
		$this->responseArray['Address2'] = $Address2?$Address2:'';
	}
	public function setCity($City)
	{
		$this->responseArray['City'] = $City?$City:'';
	}
	public function setState($State)
	{
		$this->responseArray['State'] = $State?$State:'';
	}
	public function setZip($Zip)
	{
		$this->responseArray['Zip'] = $Zip?$Zip:'';
	}
	public function setCountry($Country)
	{
		$this->responseArray['Country'] = $Country?$Country:'';
	}
	public function setEmail($Email)
	{
		$this->responseArray['Email'] = $Email?$Email:'';
	}
	public function setPhone($Phone)
	{
		$this->responseArray['Phone'] = $Phone?$Phone:'';
	}
	public function setPONumber($PONumber)
	{
		$this->responseArray['PONumber'] = $PONumber?$PONumber:'';
	}
	
	public function setGroupName($CustomerGroup)
	{
		$this->responseArray['CustomerGroup'] =strlen($CustomerGroup)!=0?utf8_encode($CustomerGroup):"";
	}
	
	public function getBill()
	{

		return $this->responseArray;
	}
}

class WG_Ship
{
	private $responseArray = array();

	public function setShipMethod($ShipMethod)
	{
		$this->responseArray['ShipMethod'] = $ShipMethod?$ShipMethod:'';
	}
	public function setCarrier($Carrier)
	{
		$this->responseArray['Carrier'] = $Carrier?$Carrier:'';
	}
	public function setTrackingNumber($TrackingNumber)
	{
		$this->responseArray['TrackingNumber'] = $TrackingNumber?$TrackingNumber:'';
	}
	public function setTitle($Title)
	{
		$this->responseArray['Title'] = $Title?$Title:'';
	}
	public function setFirstName($FirstName)
	{
		$this->responseArray['FirstName'] = $FirstName?$FirstName:'';
	}
	public function setLastName($LastName)
	{
		$this->responseArray['LastName'] = $LastName?$LastName:'';
	}
	public function setCompanyName($CompanyName)
	{
		$this->responseArray['CompanyName'] = $CompanyName?$CompanyName:'';
	}
	public function setAddress1($Address1)
	{
		$this->responseArray['Address1'] = $Address1?$Address1:'';
	}
	public function setAddress2($Address2)
	{
		$this->responseArray['Address2'] = $Address2?$Address2:'';
	}
	public function setCity($City)
	{
		$this->responseArray['City'] = $City?$City:'';
	}
	public function setState($State)
	{
		$this->responseArray['State'] = $State?$State:'';
	}
	public function setZip($Zip)
	{
		$this->responseArray['Zip'] = $Zip?$Zip:'';
	}
	public function setCountry($Country)
	{
		$this->responseArray['Country'] = $Country?$Country:'';
	}
	public function setEmail($Email)
	{
		$this->responseArray['Email'] = $Email?$Email:'';
	}

	public function setPhone($Phone)
	{
		$this->responseArray['Phone'] = $Phone?$Phone:'';
	}
	public function setDiscount($Discount)
	{
		$this->responseArray['Discount'] =$Discount?$Discount:0;
	}
	public function setStoreCredit($StoreCredit)
	{
		$this->responseArray['StoreCredit'] =$StoreCredit?$StoreCredit:0;
	}
	public function setTax($Tax)
	{
		$this->responseArray['Tax'] =$Tax?$Tax:"";
	}
	public function setShipping($Shipping)
	{
		$this->responseArray['Shipping'] =$Shipping?$Shipping:0;
	}
	public function setTotal($Total)
	{
		$this->responseArray['Total'] =$Total?$Total:0;
	}

	public function getShip()
	{
		return $this->responseArray;
	}
}

class WG_Charges
{

	private $responseArray = array();

	public function setDiscount($Discount)
	{
		$this->responseArray['Discount'] = $Discount?$Discount:0;
	}
	public function setStoreCredit($StoreCredit)
	{
		$this->responseArray['StoreCredit'] =$StoreCredit;
	}
	public function setTax($Tax)
	{
		$this->responseArray['Tax'] = $Tax?$Tax:0;
	}
	public function setShipping($Shipping)
	{
		$this->responseArray['Shipping'] = $Shipping?$Shipping:0;
	}
	public function setTotal($Total)
	{
		$this->responseArray['Total'] = $Total?$Total:0;
	}
	public function getCharges()
	{
		return $this->responseArray;
	}
}

class WG_Customers
{
	private $responseArray = array();
	private $Customer = array();
	

	public function setStatusCode($StatusCode)
	{
		$this->responseArray['StatusCode'] = $StatusCode?$StatusCode:0;
	}
	public function setStatusMessage($StatusMessage)
	{
		$this->responseArray['StatusMessage'] = $StatusMessage?$StatusMessage:'';
	}
	public function setTotalRecordFound($TotalRecordFound)
	{
		$this->responseArray['TotalRecordFound'] = $TotalRecordFound?$TotalRecordFound:0;
	}
	public function setTotalRecordSent($TotalRecordSent)
	{
		$this->responseArray['TotalRecordSent'] = $TotalRecordSent?$TotalRecordSent:0;
	}
	
	public function setCustomer($Customer)
	{

		$this->Customer[] = $Customer?$Customer:'';
	}

	public function getCustomers()
	{
		$this->responseArray['Customers'] = $this->Customer;
		return $this->responseArray;
	}

}

class WG_Customer
{
	private $responseArray = array();
	public function setCustomerId($CustomerId)
	{
		$this->responseArray['CustomerId'] = $CustomerId?$CustomerId:'';
	}
	public function setFirstName($FirstName)
	{
		$this->responseArray['FirstName'] = $FirstName?$FirstName:'';
	}
	public function setMiddleName($MiddleName)
	{
		$this->responseArray['MiddleName'] = $MiddleName?$MiddleName:'';
	}
	public function setLastName($LastName)
	{
		$this->responseArray['LastName'] = $LastName?$LastName:'';
	}
	public function setCustomerGroup($CustomerGroup)
	{
		$this->responseArray['CustomerGroup'] = $CustomerGroup?$CustomerGroup:'';
	}
	public function setcompany($company)
	{
		$this->responseArray['Company'] = $company?$company:'';
	}
	public function setemail($email)
	{
		$this->responseArray['email'] = $email?$email:'';
	}
	public function setsubscribedToEmail($subscribedToEmail)
	{
			$this->responseArray['subscribedToEmail'] = $subscribedToEmail?$subscribedToEmail:'false';
	}
	public function setAddress1($Address1)
	{
		$this->responseArray['Address1'] = $Address1?$Address1:'';
	}
	public function setAddress2($Address2)
	{
		$this->responseArray['Address2'] = $Address2?$Address2:'';
	}
	public function setCity($City)
	{
		$this->responseArray['City'] = $City?$City:'';
	}
	public function setState($State)
	{
		$this->responseArray['State'] = $State?$State:'';
	}
	public function setStatus($State)
	{
		$this->responseArray['Status'] = $State?$State:'';
	}
	public function setZip($Zip)
	{
		$this->responseArray['Zip'] = $Zip?$Zip:'';
	}
	public function setCountry($Country)
	{
		$this->responseArray['Country'] = $Country?$Country:'';
	}
	public function setPhone($Phone)
	{
		$this->responseArray['Phone'] = $Phone?$Phone:'';
	}public function setCreatedAt($CreatedAt)
	{
		$this->responseArray['CreatedAt'] = $CreatedAt?$CreatedAt:'';
	}public function setUpdatedAt($UpdatedAt)
	{
		$this->responseArray['UpdatedAt'] = $UpdatedAt?$UpdatedAt:'';
	}public function setLifeTimeSale($LifeTimeSale)
	{
		$this->responseArray['LifeTimeSale'] = $LifeTimeSale?$LifeTimeSale:'';
	}public function setAverageSale($AverageSale)
	{
		$this->responseArray['AverageSale'] = $AverageSale?$AverageSale:'';
	}
    
	public function setGroupName($CustomerGroup)
	{
		$this->responseArray['CustomerGroup'] =strlen($CustomerGroup)!=0?utf8_encode($CustomerGroup):"";
	}
	
	
	
	public function getCustomer()
	{
		return $this->responseArray;
	}	
}

class Groupsets
{
	private $responseArray = array();
	private $Attributesets = array();

	public function setStatusCode($StatusCode)
	{
		$this->responseArray['StatusCode'] = $StatusCode?$StatusCode:0;
	}
	public function setStatusMessage($StatusMessage)
	{
		$this->responseArray['StatusMessage'] = $StatusMessage?$StatusMessage:'';
	}

	public function setGroupsets($Attributeset)
	{
		$this->Attributesets[] = $Attributeset?$Attributeset:'';
	}

	public function getGroupsets()
	{
		$this->responseArray['Customergroup'] = $this->Attributesets;
		return $this->responseArray;
	}
}

class Groupset
{
	private $Groupset = array();

	public function setGroupsetID($AttributesetID)
	{
		$this->Groupset['CustomerGroupID'] = $AttributesetID ? $AttributesetID :"";
	}

	public function setGroupsetName($AttributesetName)
	{
		$this->Groupset['CustomerGroupName'] = $AttributesetName ? $AttributesetName :"";
	}

	public function getGroupset()
	{
		return $this->Groupset;
	}
}
?>