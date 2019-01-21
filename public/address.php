<?php 

class CapturePlus_Interactive_Find_v2_10
{

   //Credit: Thanks to Stuart Sillitoe (http://stu.so/me) for the original PHP that these samples are based on.

   private $Key; //The key to use to authenticate to the service.
   private $SearchTerm; //The search term to find. If the LastId is provided, the SearchTerm searches within the results from the LastId.
   private $LastId; //The Id from a previous Find or FindByPosition.
   private $SearchFor; //Filters the search results.
   private $Country; //The name or ISO 2 or 3 character code for the country to search in. Most country names will be recognised but the use of the ISO country code is recommended for clarity.
   private $LanguagePreference; //The 2 or 4 character language preference identifier e.g. (en, en-gb, en-us etc).
   private $MaxSuggestions; //The maximum number of autocomplete suggestions to return.
   private $MaxResults; //The maximum number of retrievable address results to return.
   private $Data; //Holds the results of the query

   function CapturePlus_Interactive_Find_v2_10($Key, $SearchTerm, $LastId, $SearchFor, $Country, $LanguagePreference, $MaxSuggestions, $MaxResults)
   {
      $this->Key = $Key;
      $this->SearchTerm = $SearchTerm;
      $this->LastId = $LastId;
      $this->SearchFor = $SearchFor;
      $this->Country = $Country;
      $this->LanguagePreference = $LanguagePreference;
      $this->MaxSuggestions = $MaxSuggestions;
      $this->MaxResults = $MaxResults;
   }

   function MakeRequest()
   {
      $url = "http://services.postcodeanywhere.co.uk/CapturePlus/Interactive/Find/v2.10/xmla.ws?";
      $url .= "&Key=" . urlencode($this->Key);
      $url .= "&SearchTerm=" . urlencode($this->SearchTerm);
      $url .= "&LastId=" . urlencode($this->LastId);
      $url .= "&SearchFor=" . urlencode($this->SearchFor);
      $url .= "&Country=" . urlencode($this->Country);
      $url .= "&LanguagePreference=" . urlencode($this->LanguagePreference);
      $url .= "&MaxSuggestions=" . urlencode($this->MaxSuggestions);
      $url .= "&MaxResults=" . urlencode($this->MaxResults);

      //Make the request to Postcode Anywhere and parse the XML returned
      $file = simplexml_load_file($url);

      //Check for an error, if there is one then throw an exception
      if ($file->Columns->Column->attributes()->Name == "Error") 
      {
         throw new Exception("[ID] " . $file->Rows->Row->attributes()->Error . " [DESCRIPTION] " . $file->Rows->Row->attributes()->Description . " [CAUSE] " . $file->Rows->Row->attributes()->Cause . " [RESOLUTION] " . $file->Rows->Row->attributes()->Resolution);
      }

      //Copy the data
      if ( !empty($file->Rows) )
      {
         foreach ($file->Rows->Row as $item)
         {
             $this->Data[] = array('Id'=>$item->attributes()->Id,'Text'=>$item->attributes()->Text,'Highlight'=>$item->attributes()->Highlight,'Cursor'=>$item->attributes()->Cursor,'Description'=>$item->attributes()->Description,'Next'=>$item->attributes()->Next);
         }
      }
   }

   function HasData()
   {
      if ( !empty($this->Data) )
      {
         return $this->Data;
      }
      return false;
   }

}
// $response =array('asasx','asxasx','asxasx','sdfdfgbb');
// echo json_encode($response);die;
//Example usage WR5 3DA
//-------------

$pa = new CapturePlus_Interactive_Find_v2_10 ("GK99-WN34-AG36-AZ78",$_REQUEST['postal_code'],"","Everything","GBR","EN","","");
$pa->MakeRequest();
$data = $pa->HasData();
if ($pa->HasData())
{
  $data = $pa->HasData();
  $response = array();
 
  foreach ($data as $item)
  {
     $response[] = $item["Text"];
  }
}
echo json_encode($response);die;
?>
