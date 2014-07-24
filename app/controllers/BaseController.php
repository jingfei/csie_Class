<?php

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

	public function array_to_csv($fields, $delimiter = ',', $enclosure = '"')
	{
	    $csv = '';
	
	    foreach ($fields as $field) {
	        $first_element = true;
	
	        foreach ($field as $element) {
	            // 除了第一個欄位外, 於 每個欄位 前面都需加上 欄位分隔符號
	            if (!$first_element)
	               $csv .= $delimiter;
	
	            $first_element = false;
	
	            // CSV 遇到 $enclosure, 需要重複一次, ex: " => ""
	            $element = str_replace($enclosure, $enclosure . $enclosure, $element);
	            $csv .= $enclosure . $element . $enclosure;
	        }
	
	        $csv .= "\n";
	    }
	
	    return $csv;
	}

}
