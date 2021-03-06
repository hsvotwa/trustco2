<?php
class CourseController extends BaseController {
    public function __construct () {
    }

    function create() {
        $model = new CourseMdl();
        $this->g_form_fields = $model->getFields();
        $this->g_record_id = $model->g_id;
        $this->g_form_action = WEBROOT . "course/save";
        $this->render( "edit", $model->getRecordPageTitle() );
    }

    function edit( $id ) {
        $this->set( array( $id ) );
        $model = new CourseMdl( $id );
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
        $model = new CourseMdl( $uuid );
        $model->getFields();
        $error_message = "";
        if( ! ( new CourseMgr() )->validName( $_POST['name'], $uuid ) ) {
            $data["success"] = false;
            $data["message"] = "The name you provided is already registered for another course.";
            echo json_encode( $data );
            return;
        }
        $success = $model->set();
        if ( $error_message ) {
            $model->g_errors[] = $error_message;
        }
        echo ( new GeneralDisplay() )->deterFeedback( $success, $model->getRecordPageTitle(), implode( ",", $model->g_errors ) );
    }

    function createsubject() {
        $model = new CoursesubjectMdl();
        $this->g_form_fields = $model->getFields();
        $this->g_record_id = $model->g_id;
        $this->g_layout = null;
        $this->g_form_action = WEBROOT . "course/savesubject";
        $this->render( "coursesubject", $model->getRecordPageTitle() );
    }

    function savesubject() {
        $model = new CoursesubjectMdl();
        $model->getFields();
        $error = "";
        $success = $model->save( $error );
        echo ( new GeneralDisplay() )->deterFeedback( $success, "", $error );
    }

    function removesubject() {
        $model = new CoursesubjectMdl();
        $model->getFields();
        $message = "";
        $success = $model->remove( $message );
        echo ( new GeneralDisplay() )->deterFeedback( $success, "", $message );
    }

    function subjectlist( $course_uuid ) {
        $this->g_can_edit = true;
        $model = new CourseMdl( $course_uuid );
        $this->g_layout = null;
        $error_message = "";
        $this->g_records = $model->getSubjects();
        $this->g_form_fields = (new CourseMdl())->getFields();
        $this->render("subjectlist");
    }
}