<?php
class DepartmentMdl extends BaseMdl {
    public $g_current_unit_count = null;

    public function __construct ( $id = null ) {
        $this->g_id = $id;
        $this->g_current_unit_count = 0;
        $this->g_entity_name = "department";
        $this->g_sql_table = "tbl_department";
        $this->g_retrieve_query = $this->getRetrieveQuery();
        $this->g_fields = $this->g_invalid_fields = $this->g_errors = array ();
        if ( $id ) {
            $this->retrieve ();
        } else {
            $this->g_id = $this->getMySql()->getUuid();
        }
        $this->getFields();
    }

    protected function getRetrieveQuery () {
        return "select * from $this->g_sql_table where uuid = '$this->g_id'";
    }

    public function getRecordPageTitle() {
        return ( ! is_null ( $this->g_row ) ? $this->g_entity_name . ': ' . $this->g_row['name']  : 'New ' . strtolower( $this->g_entity_name ) );
    }

    public function getFields () {
        if ( $this->g_fields != null ) {
            return $this->g_fields;
        }
        $return = array ();
        $return["name"] = new FieldMdl( 
            "name", "name", "Name", true, EnumFieldDataType::_string, EnumFieldType::_string, $this->g_sql_table, true, "text", $this->g_row
        );
        $return["status_id"] = new FieldMdl( 
            "status_id", "status_id", "Status", true, EnumFieldDataType::_string, EnumFieldType::_string, $this->g_sql_table, true, "text", $this->g_row, null, 2, null, LookupData::getStatusList(), 0, false
        );
        $this->g_fields = $return;
        return $this->g_fields;
    }
}