<?php
class SubjectMgr extends BaseMgr {
    public function __construct ( $search_text = "" ) {
        $this->g_entity_name = "subject(s)";
        $this->g_retrieve_query = $this->getRetrieveQuery( $search_text );
    }

    protected function getRetrieveQuery ( $search_text = "" ) {
         return "select *
                    from tbl_subject
                 where name like '%" . $search_text . "%' or code like '%" . $search_text . "%'
                 order by code";
    }

    function validName( $name, $uuid ) {
        $query = "select * from tbl_subject where name = '$name' and uuid != '$uuid';";
        $data = $this->getMySql()->getQueryResult( $query );
       return ! $data || ! $data->num_rows;
    }
}