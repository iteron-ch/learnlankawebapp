<?php
namespace Darwin\Helpers;
class encrypt {

		public function encrypt($param) {
		    try {
		        if (isset($param)) {
		            return $encryptedData = base64_encode(serialize($param));
		        }
		    } catch (Exception $exc) {
		        return $exc->getTraceAsString();
		    }
    }
}
?>
