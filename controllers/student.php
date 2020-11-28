<?php
class StudentController extends BaseController {
    public function __construct () {
    }

    function create() {
        $model = new StudentMdl();
        $this->g_form_fields = $model->getFields();
        $this->g_record_id = $model->g_id;
        $this->g_form_action = WEBROOT . "student/save";
        $this->render( "edit", $model->getRecordPageTitle() );
    }

    function edit( $id ) {
        $this->set( array( $id ) );
        $model = new StudentMdl( $id );
        if( ! $model->g_row ) {
            ( new ErrorController() )->Error404();
            return;
        }
        $this->g_record_id = $model->g_row["uuid"];
        $this->g_form_fields = ( $model )->getFields();
        $this->render( "edit", $model->getRecordPageTitle() );
    }

    function save() {
        $uuid = (
            isset( $_POST['uuid'] ) && !empty( $_POST['uuid'] )
            ? $_POST['uuid']
            : null
        );
        $model = new StudentMdl( $uuid );
        $model->getFields();
        $error_message = "";
        if( ! ( new StudentMgr() )->validName( $_POST['name'], $uuid ) ) {
            $data["success"] = false;
            $data["message"] = "The name you provided is already registered for another student.";
            echo json_encode( $data );
            return;
        }
        $success = $model->set() && $model->pushToBCTime( $error_message );
        if ( $error_message ) {
            $model->g_errors[] = $error_message;
        }
        echo ( new GeneralDisplay() )->deterFeedback( $success, $model->getRecordPageTitle(), implode( ",", $model->g_errors ) );
    }
}