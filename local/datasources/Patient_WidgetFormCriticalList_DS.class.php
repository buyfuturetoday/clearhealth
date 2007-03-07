<?php

require_once CELINI_ROOT . '/includes/Datasource_sql.class.php';

class Patient_WidgetFormCriticalList_DS extends Datasource_sql  {
	/**
	 * Stores the case-sensative class name for this ds and should be considered
	 * read-only.
	 *
	 * This is being used so that the internal name matches the filesystem
	 * name.  Once BC for PHP 4 is no longer required, this can be dropped in
	 * favor of using get_class($ds) where ever this property is referenced.
	 *
	 * @var string
	 */
	var $_internalName = 'Patient_WidgetFormCriticalList_DS';

	/**
	 * The form type to show
	 *
	 * @var string
	 */
	var $patient_id = '';
	var $form_id = '';
	var $_fields = array();
	var $case_sql = '';

	function Patient_WidgetFormCriticalList_DS($patient_id) {
		return true;
	}

	function buildquery($patient_id, $form_id) {
		$this->patient_id = intval($patient_id);

		$this->setup(Celini::dbInstance(),
			array(
				'cols'    => "form_data_id $this->case_sql ",
				'from'    => "widget_form AS wf " .
							 "INNER JOIN form AS f USING(form_id) ".
							 "INNER JOIN form_data AS fd using (form_id) ".
							 "LEFT JOIN storage_int ON storage_int.foreign_key = fd.form_data_id ".
							 "LEFT JOIN storage_string ON storage_string.foreign_key = fd.form_data_id ".
							 "LEFT JOIN storage_text ON storage_text.foreign_key = fd.form_data_id ".
							 "LEFT JOIN storage_date ON storage_date.foreign_key = fd.form_data_id ",
				'where'   => "fd.external_id = '" . $this->patient_id . "' and f.form_id = '" . $this->form_id . "'",
				'groupby'   => "fd.form_data_id"
			),
			false);
	}
	
	function set_form_type($form_id) {
		$labels = array();
		$this->form_id = $form_id;
		
		$db = Celini::dbInstance();
		
		$sql = "select name, pretty_name, table_name from summary_columns where form_id = '$form_id'";
//echo $sql ."<br>";

		$res = $db->query($sql);
		while($res && !$res->EOF) {
			$labels[$res->fields["name"]] = $res->fields["pretty_name"];
			$fields[$res->fields["name"]] = array('name'=>$res->fields['name'], 'table_name'=>$res->fields['table_name']);
			$this->_labels = $labels;
			$this->_fields = $fields;
			$res->MoveNext();	
		}

	}
	
	function _build_case_sql($form_id) {

	    	$case_sql = "";
	    	if (count($this->_fields > 0)) $case_sql = " ,";
	        foreach ($this->_fields as $ar) {
	        	$table = $ar['table_name'];

	        	$value_name = $ar['name'];
			if ($table) {
			        $case_sql .= " MAX(CASE WHEN $table.value_key = '$value_name' THEN $table.value END)  as '$value_name', ";
			}
	        }
	        $case_sql = substr($case_sql,0,-2);             
	        $this->case_sql = $case_sql;
	}
}

