<?php

if (!function_exists('encryptParam')) {

    /**
     * function for encrypt the srting
     * @param string $param
     * @return string 
     * @author Icreon Tech -Dev2.
     */
    function encryptParam($param) {
        try {
            if (isset($param)) {
                return $encryptedData = base64_encode(serialize($param));
            }
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}
if (!function_exists('decryptParam')) {

    /**
     * function for decrypt the srting
     * 
     * @param string $param
     * @return string 
     * @author Icreon Tech -Dev2.
     */
    function decryptParam($param) {
        try {
            if (isset($param)) {
                return $decryptedData = @unserialize(base64_decode($param));
            }
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}

if (!function_exists('asd')) {

    /**
     * Dump the passed variables and end the script.
     *
     * @param  array $data
     * @return void
     * @author Icreon Tech -Dev2.
     */
    function asd($data, $isExit = TRUE) {
        echo "<pre>";
        print_r($data);
        if ($isExit) {
            die(1);
        }
    }

}
if (!function_exists('statusArray')) {

    /**
     * This function return status array.
     *
     * @param  void
     * @return array
     * @author Icreon Tech -Dev2.
     */
    function statusArray() {
        try {
            return array("Active" => "Active", "Inactive" => "Inactive");
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}
if (!function_exists('inputDateFormat')) {

    /**
     * This function convert a date string into database accetable date format
     *
     * @param  type string $date 
     * @return string
     * @author Icreon Tech -Dev2.
     */
    function inputDateFormat($date) {
        $date = str_replace('/', '-', $date);
        return Carbon\Carbon::parse($date)->format('Y-m-d');
    }

}

if (!function_exists('outputDateFormat')) {

    /**
     * This function convert a date string into application's date format
     *
     * @param  type string $date 
     * @return string
     * @author Icreon Tech -Dev2.
     */
    function outputDateFormat($date) {
        if ($date == NULL_DATETIME) {
            return '';
        }
        $date = Carbon\Carbon::parse($date)->format('d-m-Y');
        return $date = str_replace('-', '/', $date);
    }

}

if (!function_exists('questionSetSubjects')) {

    /**
     * This function return question set subject array.
     *
     * @param  void
     * @return array
     * @author Icreon Tech -Dev5.
     */
    function questionSetSubjects() {
        try {
            return array("English" => 'English', "Math" => "Math");
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}
if (!function_exists('subjectPapers')) {

    /**
     * This function return question paper array.
     *
     * @param  void
     * @return array
     * @author Icreon Tech -Dev2.
     */
    function subjectPapers() {
        try {
            return array(
                "Math" => array(
                    1 => array('name' => 'Paper 1: Arithmetic', 'time' => '1800'),
                    2 => array('name' => 'Paper 2: Reasoning', 'time' => '2400'),
                    3 => array('name' => 'Paper 3: Reasoning', 'time' => '2400')
                ),
                "English" => array(
                    4 => array('name' => 'Paper 1: Grammar, Punctuation & Spelling Questions', 'time' => '2700'),
                    5 => array('name' => 'Paper 2: Spelling', 'time' => '900')
                )
            );
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}
if (!function_exists('paperAttempts')) {


    /**
     * This function return question paper array.
     *
     * @param  void
     * @return array
     * @author Icreon Tech -Dev2.
     */
    function paperAttempts() {
        try {
            return array(1 => 'Attempt 1', 2 => 'Attempt 2', 3 => 'Attempt 3');
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}
if (!function_exists('questionSetGroups')) {

    /**
     * This function return question set group array.
     *
     * @param  void
     * @return array
     * @author Icreon Tech -Dev2.
     */
    function questionSetGroups() {
        try {
            return array("Test" => "Test", "Revision" => "Revision");
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}

if (!function_exists('questionKeyStage')) {

    /**
     * This function return question set group array.
     *
     * @param  void
     * @return array
     * @author Icreon Tech -Dev5.
     */
    function questionKeyStage() {
        try {
            return array(1 => 'KS 1', 2 => 'KS 2', 3 => 'KS 3', 4 => 'KS 4');
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}

if (!function_exists('questionYearGroup')) {

    /**
     * This function return question set group array.
     *
     * @param  void
     * @return array
     * @author Icreon Tech -Dev2.
     */
    function questionYearGroup() {
        try {
            return array(
                1 => array(
                    1 => 'YG 1',
                    2 => 'YG 2'
                ),
                2 => array(
                    3 => 'YG 3',
                    4 => 'YG 4',
                    5 => 'YG 5',
                    6 => 'YG 6'
                ),
                3 => array(
                    7 => 'YG 7',
                    8 => 'YG 8',
                    9 => 'YG 9'
                ),
                4 => array(
                    10 => 'YG 10',
                    11 => 'YG 11'
                )
            );
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}
if (!function_exists('SchoolClassYear')) {

    /**
     * This function return status array.
     *
     * @param  void
     * @return array
     * @author Icreon Tech -Dev2.
     */
    function SchoolClassYear() {
        try {
            return array("First" => "First", "Second" => "Second", "Third" => "Third");
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}

if (!function_exists('allYearGroups')) {

    /**
     * This function return question set group array.
     *
     * @param  void
     * @return array
     * @author Icreon Tech -Dev5.
     */
    function allYearGroups() {
        try {
            return array(1 => 'YG 1', 2 => 'YG 2', 3 => 'YG 3', 4 => 'YG 4', 5 => 'YG 5', 6 => 'YG 6', 7 => 'YG 7', 8 => 'YG 8', 9 => 'YG 9', 10 => 'YG 10', 11 => 'YG 11');
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}
if (!function_exists('questionStatus')) {

    /**
     * This function return question set group array.
     *
     * @param  void
     * @return array
     * @author Icreon Tech -Dev5.
     */
    function questionStatus() {
        try {
            return array('Published' => 'Published', 'In Review' => 'In Review', 'Unpublished' => 'Unpublished', 'Rejected' => 'Rejected');
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}

if (!function_exists('questionDifficulty')) {

    /**
     * This function return question set group array.
     * @author Icreon Tech -Dev2.
     * @param  void
     * @return array
     */
    function questionDifficulty() {
        try {
            return array(3 => '3', 4 => '4', 5 => '5', 6 => '6', 7 => '6+');
           // return array(3 => '3', 4 => '4', 5 => '5', 6 => '6');
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}
if (!function_exists('questionType')) {

    /**
     * This function return question type array.
     * @author Icreon Tech -Dev2.
     * @param  void
     * @return type array
     */
    function questionType() {
        return array(
            0 => array('id' => 13, 'name' => 'Boolean Type Question'),
            1 => array('id' => 11, 'name' => 'Drag Drop & Re-ordering'),
            2 => array('id' => 19, 'name' => 'Draw line on Image'),
            3 => array('id' => 3, 'name' => 'Fill in the blanks'),
            4 => array('id' => 7, 'name' => 'Insert Literacy Feature'),
            5 => array('id' => 31, 'name' => 'Input on Image'),
            6 => array('id' => 18, 'name' => 'Joining Dots on Diagram/Grid'),
            7 => array('id' => 15, 'name' => 'Label Literacy Feature'),
            8 => array('id' => 30, 'name' => 'Line of Symmetry Question'),
            9 => array('id' => 4, 'name' => 'Matching with drag & drop question'),
            10 => array('id' => 20, 'name' => 'Measurement (image)'),
            11 => array('id' => 28, 'name' => 'Measure Line and Angle with text box (image)'),
            12 => array('id' => 16, 'name' => 'Missing Words on Image'),
            13 => array('id' => 22, 'name' => 'Missing Number on Image'),
            14 => array('id' => 1, 'name' => 'Multiple Choice with Single Answer'),
            15 => array('id' => 2, 'name' => 'Multiple Choice with Multiple Answers'),
            16 => array('id' => 12, 'name' => 'Number /Word Selection (Circle)'),
            17 => array('id' => 6, 'name' => 'Numerical/Text Box Single or Multiple Question (image) with Keywords'),
            18 => array('id' => 29, 'name' => 'Pie Chart'),
            19 => array('id' => 24, 'name' => 'Reflection (Bottom - Top)'),
            20 => array('id' => 26, 'name' => 'Reflection (Left - Diagonal)'),
            21 => array('id' => 23, 'name' => 'Reflection (Left - Right)'),
            22 => array('id' => 27, 'name' => 'Reflection (Right - Diagonal)'),
            23 => array('id' => 17, 'name' => 'Reflection (Right - Left)'),
            24 => array('id' => 25, 'name' => 'Reflection (Top - Bottom)'),
            25 => array('id' => 8, 'name' => 'Spelling with Audio'),
            26 => array('id' => 21, 'name' => 'Shading Shapes'),
            27 => array('id' => 14, 'name' => 'Select Literacy Feature'),
            28 => array('id' => 10, 'name' => 'Table - Single/Multiple entry'),
            29 => array('id' => 32, 'name' => 'Table - Fill Blanks'),
            30 => array('id' => 9, 'name' => 'Underline Literacy Feature'),
        );
    }

}

if (!function_exists('questionTypeList')) {

    /**
     * This function return question type array.
     * @author Icreon Tech -Dev2.
     * @param  void
     * @return type array
     */
    function questionTypeList() {
        //$questionTypes = questionType();

        $questionTypes['Math'] = subjectQuestionType('Math');
        $questionTypes['English'] = subjectQuestionType('English');
        $questionTypes['All'] = subjectQuestionType('');
        foreach ($questionTypes['Math'] as $questionType) {
            $data['Math'][$questionType['id']] = $questionType['name'];
        }
        foreach ($questionTypes['English'] as $questionType) {
            $data['English'][$questionType['id']] = $questionType['name'];
        }
        foreach ($questionTypes['All'] as $questionType) {
            $data[''][$questionType['id']] = $questionType['name'];
        }
        // asd($data);

        return $data;
    }

}

if (!function_exists('buildTree')) {

    /**
     * Build a parent child hierarchy.
     * @author     Icreon Tech - dev2.
     * @param type array $element
     * @param type int $parentId
     * @return Response
     */
    function buildTree(array $elements, $parentId = 0) {
        try {
            $branch = array();
            foreach ($elements as $element) {
                if ($element['parent_id'] == $parentId) {
                    $children = buildTree($elements, $element['id']);
                    if ($children) {
                        $element['children'] = $children;
                    }
                    $branch[] = $element;
                }
            }
            return $branch;
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}

if (!function_exists('cityDD')) {

    /**
     * This function return city array.
     * @author Icreon Tech -Dev2.
     * @param  void
     * @return type array
     */
    function cityDD() {
        return [
            'London' => 'London',
            'Liverpool' => 'Liverpool',
            'Aberdeen' => 'Aberdeen',
            'Armagh' => 'Armagh',
            'Bath' => 'Bath',
            'Birmingham' => 'Birmingham',
            'Bristol' => 'Bristol',
            'Carlisle' => 'Carlisle',
            'Coventry' => 'Coventry',
        ];
    }

}
if (!function_exists('voucherDiscountType')) {

    /**
     * This function return  the types of discount related to the voucher module.
     * @author Icreon Tech -Dev5.
     * @return type array
     */
    function voucherDiscountType() {
        try {
            return array('Percent' => 'Percent', 'Amount' => 'Amount');
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}
if (!function_exists('titles')) {

    /**
     * This function return  the types of discount related to the voucher module.
     * @author Icreon Tech -Dev5.
     * @return type array
     */
    function titles() {
        try {
            return array('Mr' => 'Mr', 'Miss' => 'Miss', 'Ms' => 'Ms', 'Mrs' => 'Mrs', 'Dr' => 'Dr', 'Prof' => 'Prof');
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}
if (!function_exists('voucherUserType')) {

    /**
     * This function return  the types of user related to the voucher module.
     * @author Icreon Tech -Dev5.
     * @return type array
     */
    function voucherUserType() {
        try {
            return array(SCHOOL => 'School', TUTOR => 'Parent/Tutor');
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}
if (!function_exists('enquiryUserType')) {

    /**
     * This function return  the types of user related to the enquiry module.
     * @author Icreon Tech -Dev5.
     * @return type array
     */
    function enquiryUserType() {
        try {
            return array(TUTOR => 'Parent/Tutor', TEACHER => 'Teacher');
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}
if (!function_exists('enquiryHowFind')) {

    /**
     * This function return  the types of how did you find related to the enquiry module.
     * @author Icreon Tech -Dev5.
     * @return type array
     */
    function enquiryHowFind() {
        try {
            return array('Search Engine' => 'Search Engine', 'Youtube' => 'Youtube', 'Social Media' => 'Social Media', 'Word of Mouth' => 'Word of Mouth', 'E-Mail' => 'E-Mail');
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}
if (!function_exists('visibleToArray')) {

    /**
     * This function return  the types of how did you find related to the Helpcentre module.
     * @author Icreon Tech -Dev5.
     * @return type array
     */
    function visibleToArray() {
        try {
            return array(SCHOOL => 'School', TUTOR => 'Parent/Tutor', ADMIN => 'Admin', TEACHER => 'Teacher', STUDENT => 'Student');
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}

function groupOwnerType() {
    try {
        return array(SCHOOL => 'School', TUTOR => 'Tutor', ADMIN => 'Admin', TEACHER => 'Teacher');
    } catch (Exception $exc) {
        return $exc->getTraceAsString();
    }
}

if (!function_exists('getSubject')) {

    /**
     * used to get the subject name.
     * @author     Icreon Tech - dev1.
     * @param type $subject string
     * @return Response
     */
    function getSubject($subject) {
        try {
            if (strtolower($subject) == strtolower(MATHS))
                return MATH;
            else if (strtolower($subject) == strtolower(ENGLISH))
                return ENGLISH;
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}
if (!function_exists('userTypeArray')) {

    /**
     * This function return all the types of user.
     * @author Icreon Tech -Dev5.
     * @return type array
     */
    function userTypeArray() {
        try {
            return array(SCHOOL => 'School', TUTOR => 'Parent/Tutor', ADMIN => 'Administrator', TEACHER => 'Teacher');
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}
if (!function_exists('enquiryUserType')) {

    /**
     * This function return all the types of user.
     * @author Icreon Tech -Dev5.
     * @return type array
     */
    function enquiryUserType() {
        try {
            return array(TUTOR => 'Tutor', TEACHER => 'Teacher', PARENT => 'Parent');
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}

if (!function_exists('monthArray')) {

    /**
     * This function return all month 
     * @author Icreon Tech -Dev1.
     * @return type array
     */
    function monthArray() {
        try {
            return array('1' => 'Jan', '2' => 'Feb', '3' => 'Mar', '4' => 'Apr', '5' => 'May', '6' => 'Jun', '7' => 'Jul', '8' => 'Aug', '9' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec');
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}

if (!function_exists('validateFile')) {

    /**
     * This function return all the types of user.
     * @author Icreon Tech -Dev5.
     * @return type array
     */
    function validateFile($fileType, $extension) {
        switch ($fileType):
            case 'image':
                return in_array(strtoupper($extension), array('jpg', 'jpeg', 'png', 'gif'));
                break;
            case 'audio':
                return in_array($extension, array('mp3'));
                break;
            case 'video':
                break;
        endswitch;
    }

}

if (!function_exists('isValidFileExtension')) {

    /**
     * validate file extendion
     * @author Icreon Tech -Dev2.
     * @param type string $fileType 
     * @param type string $extension 
     * @return type array
     */
    function isValidFileExtension($fileType, $extension) {
        switch ($fileType):
            case 'image':
                return in_array(strtoupper($extension), array('jpg', 'jpeg', 'png', 'gif'));
                break;
            case 'audio':
                return in_array($extension, array('mp3'));
                break;
            case 'video':
                break;
        endswitch;
    }

}
if (!function_exists('creditCardExpiryYear')) {

    /**
     * used for credit card expiry years
     * @param type integer $startYear 
     * @author Icreon Tech -Dev1.
     * @return type array
     */
    function creditCardExpiryYear($startYear) {
        $y = 0;
        for ($y = $startYear; $y <= ($startYear + 10); $y++) {
            $year[$y] = $y;
        }
        return $year;
    }

}
if (!function_exists('titles')) {

    /**
     * This function return  the title list.
     * @author Icreon Tech -Dev5.
     * @return type array
     */
    function titles() {
        try {
            return array('Mr' => 'Mr', 'Miss' => 'Miss', 'Mrs' => 'Mrs', 'Dr' => 'Dr', 'Prof' => 'Prof');
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}
if (!function_exists('questionValidateReason')) {

    /**
     * This function return question type array.
     * @author Icreon Tech -Dev2.
     * @param  void
     * @return type array
     */
    function questionValidateReason() {
        return array(
            0 => array('id' => 1, 'name' => 'SPAG - Spelling, Grammar & Punctuation'),
            1 => array('id' => 2, 'name' => 'IA - Incorrect Answer'),
            2 => array('id' => 3, 'name' => 'IP - Incorrect Pitch'),
            3 => array('id' => 4, 'name' => 'NFP - Not Fit for Purpose'),
            4 => array('id' => 5, 'name' => 'IQS - Incorrect Question Style'),
            5 => array('id' => 6, 'name' => 'VR - Verified')
        );
    }

}

if (!function_exists('questionValidateReasonList')) {

    /**
     * This function return question type array.
     * @author Icreon Tech -Dev2.
     * @param  void
     * @return type array
     */
    function questionValidateReasonList() {
        $validateReasons = questionValidateReason();
        foreach ($validateReasons as $validateReason) {
            $data[$validateReason['id']] = $validateReason['name'];
        }
        return $data;
    }

}

if (!function_exists('validateStage')) {

    /**
     * This function return question type array.
     * @author Icreon Tech -Dev2.
     * @param  void
     * @return type array
     */
    function validateStage() {
        try {
            return array(0 => 'Pending Validate', 1 => 'Stage 1', 2 => 'Stage 2');
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}
if (!function_exists('noOfStudentSlabForSchool()')) {

    /**
     * This function return no of schools.
     * @author Icreon Tech -Dev2.
     * @param  void
     * @return type array
     */
    function noOfStudentSlabForSchool() {
        try {
            return array(5 => '5', 10 => '10', 15 => '15', 20 => '20', 25 => '25', 30 => '30');
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}
if (!function_exists('noOfStudentSlabForTutor()')) {

    /**
     * This function return no of schools.
     * @author Icreon Tech -Dev2.
     * @param  void
     * @return type array
     */
    function noOfStudentSlabForTutor() {
        try {
            for ($i = 1; $i <= 5; $i++) {
                $noArray[$i] = $i;
            }
            return $noArray;
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}

if (!function_exists('outputDateTimeFormat')) {

    /**
     * This function convert a date string into application's date format
     *
     * @param  type string $date 
     * @return string
     * @author Icreon Tech -Dev2.
     */
    function outputDateTimeFormat($date) {
        if ($date == NULL_DATETIME) {
            return '';
        }
        $date = Carbon\Carbon::parse($date)->format('d-m-Y H:i');
        return $date = str_replace('-', '/', $date);
    }

}

if (!function_exists('subjectQuestionType')) {

    /**
     * This function return question type array.
     * @author Icreon Tech -Dev2.
     * @param  void
     * @return type array
     */
    function subjectQuestionType($subject) {
        if ($subject == 'Math') {
            return array(
                0 => array('id' => 13, 'name' => 'Boolean Type Question'),
                1 => array('id' => 11, 'name' => 'Drag Drop & Re-ordering'),
                2 => array('id' => 19, 'name' => 'Draw line on Image'),
                3 => array('id' => 3, 'name' => 'Fill in the blanks'),
                4 => array('id' => 31, 'name' => 'Input on Image'),
                5 => array('id' => 18, 'name' => 'Joining Dots on Diagram/Grid'),
                6 => array('id' => 30, 'name' => 'Line of Symmetry Question'),
                7 => array('id' => 4, 'name' => 'Matching with drag & drop question'),
                8 => array('id' => 20, 'name' => 'Measurement (image)'),
                9 => array('id' => 28, 'name' => 'Measure Line and Angle with text box (image)'),
                10 => array('id' => 16, 'name' => 'Missing Words on Image'),
                11 => array('id' => 22, 'name' => 'Missing Number on Image'),
                12 => array('id' => 1, 'name' => 'Multiple Choice with Single Answer'),
                13 => array('id' => 2, 'name' => 'Multiple Choice with Multiple Answers'),
                14 => array('id' => 12, 'name' => 'Number /Word Selection (Circle)'),
                15 => array('id' => 6, 'name' => 'Numerical/Text Box Single or Multiple Question (image) with Keywords'),
                16 => array('id' => 29, 'name' => 'Pie Chart'),
                17 => array('id' => 24, 'name' => 'Reflection (Bottom - Top)'),
                18 => array('id' => 26, 'name' => 'Reflection (Left - Diagonal)'),
                19 => array('id' => 23, 'name' => 'Reflection (Left - Right)'),
                20 => array('id' => 27, 'name' => 'Reflection (Right - Diagonal)'),
                21 => array('id' => 17, 'name' => 'Reflection (Right - Left)'),
                22 => array('id' => 25, 'name' => 'Reflection (Top - Bottom)'),
                23 => array('id' => 8, 'name' => 'Spelling with Audio'),
                24 => array('id' => 21, 'name' => 'Shading Shapes'),
                25 => array('id' => 10, 'name' => 'Table - Single/Multiple entry'),
                26 => array('id' => 32, 'name' => 'Table - Fill Blanks'),
            );
        } else if ($subject == 'English') {
            return array(
                0 => array('id' => 13, 'name' => 'Boolean Type Question'),
                1 => array('id' => 11, 'name' => 'Drag Drop & Re-ordering'),
                2 => array('id' => 3, 'name' => 'Fill in the blanks'),
                3 => array('id' => 7, 'name' => 'Insert Literacy Feature'),
                4 => array('id' => 31, 'name' => 'Input on Image'),
                5 => array('id' => 15, 'name' => 'Label Literacy Feature'),
                6 => array('id' => 4, 'name' => 'Matching with drag & drop question'),
                7 => array('id' => 16, 'name' => 'Missing Words on Image'),
                8 => array('id' => 1, 'name' => 'Multiple Choice with Single Answer'),
                9 => array('id' => 2, 'name' => 'Multiple Choice with Multiple Answers'),
                10 => array('id' => 12, 'name' => 'Number /Word Selection (Circle)'),
                11 => array('id' => 6, 'name' => 'Numerical/Text Box Single or Multiple Question (image) with Keywords'),
                12 => array('id' => 8, 'name' => 'Spelling with Audio'),
                13 => array('id' => 14, 'name' => 'Select Literacy Feature'),
                14 => array('id' => 10, 'name' => 'Table - Single/Multiple entry'),
                15 => array('id' => 32, 'name' => 'Table - Fill Blanks'),
                16 => array('id' => 9, 'name' => 'Underline Literacy Feature'),
            );
        } else {
            return array(
                0 => array('id' => 13, 'name' => 'Boolean Type Question'),
                1 => array('id' => 11, 'name' => 'Drag Drop & Re-ordering'),
                2 => array('id' => 19, 'name' => 'Draw line on Image'),
                3 => array('id' => 3, 'name' => 'Fill in the blanks'),
                4 => array('id' => 7, 'name' => 'Insert Literacy Feature'),
                5 => array('id' => 31, 'name' => 'Input on Image'),
                6 => array('id' => 18, 'name' => 'Joining Dots on Diagram/Grid'),
                7 => array('id' => 15, 'name' => 'Label Literacy Feature'),
                8 => array('id' => 30, 'name' => 'Line of Symmetry Question'),
                9 => array('id' => 4, 'name' => 'Matching with drag & drop question'),
                10 => array('id' => 20, 'name' => 'Measurement (image)'),
                11 => array('id' => 28, 'name' => 'Measure Line and Angle with text box (image)'),
                12 => array('id' => 16, 'name' => 'Missing Words on Image'),
                13 => array('id' => 22, 'name' => 'Missing Number on Image'),
                14 => array('id' => 1, 'name' => 'Multiple Choice with Single Answer'),
                15 => array('id' => 2, 'name' => 'Multiple Choice with Multiple Answers'),
                16 => array('id' => 12, 'name' => 'Number /Word Selection (Circle)'),
                17 => array('id' => 6, 'name' => 'Numerical/Text Box Single or Multiple Question (image) with Keywords'),
                18 => array('id' => 29, 'name' => 'Pie Chart'),
                19 => array('id' => 24, 'name' => 'Reflection (Bottom - Top)'),
                20 => array('id' => 26, 'name' => 'Reflection (Left - Diagonal)'),
                21 => array('id' => 23, 'name' => 'Reflection (Left - Right)'),
                22 => array('id' => 27, 'name' => 'Reflection (Right - Diagonal)'),
                23 => array('id' => 17, 'name' => 'Reflection (Right - Left)'),
                24 => array('id' => 25, 'name' => 'Reflection (Top - Bottom)'),
                25 => array('id' => 8, 'name' => 'Spelling with Audio'),
                26 => array('id' => 21, 'name' => 'Shading Shapes'),
                27 => array('id' => 14, 'name' => 'Select Literacy Feature'),
                28 => array('id' => 10, 'name' => 'Table - Single/Multiple entry'),
                29 => array('id' => 32, 'name' => 'Table - Fill Blanks'),
                30 => array('id' => 9, 'name' => 'Underline Literacy Feature'),
            );
        }
    }

}

if (!function_exists('awardFontStyle')) {

    /**
     * This function convert a date string into application's date format
     *
     * @param  type string $date 
     * @return string
     * @author Icreon Tech -Dev2.
     */
    function awardFontStyle() {
        try {
            return array('fira-sans-italic' => 'Italic', 'fira-sans-bold-italic' => 'Italic Bold', 'opensansregular' => 'Open sans regular', 'opensanssemibold' => 'Open sans semi bold');
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}
if (!function_exists('baseLineScoreType')) {

    /**
     * This function return the types base line in score or discount.
     * @author Icreon Tech -Dev1.
     * @return type array
     */
    function baseLineScoreType() {
        try {
            return array('1' => 'Score', '2' => 'Percentage');
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}
if (!function_exists('senProvision')) {

    /**
     * This function return the sen provision.
     * @author Icreon Tech -Dev1.
     * @return type array
     */
    function senProvision() {
        try {
            return array('1' => 'SEN Support', '2' => 'EHC Plan');
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}



if (!function_exists('helpCenterCategory')) {

    /**
     * @author Icreon Tech -Dev2.
     */
    function helpCenterCategory() {
        try {
            return array(1 => "User Centre", 2 => "Message Centre", 3 => "Revision Centre", 4 => "Test Centre", 5 => "Reporting", 6 => "My Account", 7 => 'Billing', 8 => 'General System Help', 9 => 'English', 10 => 'Math', 11 => 'General Educational Help');
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}

if (!function_exists('helpCenterCategoryGroup')) {

    /**
     * @author Icreon Tech -Dev2.
     */
    function helpCenterCategoryGroup() {
        try {
            return $category = array("System Help" => array(1 => "User Centre", 2 => "Message Centre", 3 => "Revision Centre", 4 => "Test Centre", 5 => "Reporting", 6 => "My Account", 7 => 'Billing', 8 => 'General System Help'), "Educational" => array(9 => 'English', 10 => 'Math', 11 => 'General Educational Help'));
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}

if (!function_exists('getFileExtensionFromFIlename')) {

    /**
     * @author Icreon Tech -Dev2.
     */
    function getFileExtensionFromFilename($filename) {
        try {
            return substr($filename, (strrpos($filename, ".") + 1));
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}

if (!function_exists('getSubscriptionExpiryDate')) {

    /**
     * @author Icreon Tech -Dev2.
     */
    function getSubscriptionExpiryDate($data) {
        try {
            if ($data['user_type'] == SCHOOL)
                $endDate = date("Y", strtotime($data['start_date'])) . '-12-31 23:59:59';
            else {
                $date = $data['start_date'];
                $date = strtotime($date);
                $new_date = strtotime('+ 1 year', $date);
                $endDate = date('Y-m-d', $new_date);
            }
            return $endDate;
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}

if (!function_exists('public_path_upload')) {

    /**
     * Get the path to the public folder.
     *
     * @param  string  $path
     * @return string
     */
    function public_path_upload($path = '') {
        return env('FILE_UPLOAD_ROOT_PATH') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

}
if (!function_exists('view_instructions')) {

    /**
     * array of view instructions for different type of questions
     *
     * @param null
     * @return array
     */
    function view_instructions() {
        try {
            $instratctArray[1] = 'Select your answer by clicking on one or more boxes. To change your answer, unclick and re-enter.';
            $instratctArray[2] = 'Select your answer by clicking on one or more boxes. To change your answer, unclick and re-enter.';
            $instratctArray[3] = 'Input your answer into the box. To change your answer, delete and re-enter.';
            $instratctArray[4] = "Select and move the boxes in the left-hand column so that they are in line with the correct answers in the right-hand column. To change your answer, click 'Clear Answer'.";
            $instratctArray[6] = 'Input your answer into the box. To change your answer, delete and re-enter.';
            $instratctArray[7] = 'Move the punctuation mark/s to the correct space/s in the sentence. To change your answer, delete and re-enter.';
            $instratctArray[8] = 'Listen to the audio file and input your answer into the box. To change your answer, delete and re-enter.';
            $instratctArray[9] = "Highlight the word/s and click the green 'underline' button to set your answer. To change your answer, click 'Clear Answer'.";
            $instratctArray[10] = 'Select or input your answer into one or more boxes. To change your answer, delete and re-enter.';
            $instratctArray[11] = 'Select and move the boxes into the correct order. To change your answer, select and move the box again.';
            $instratctArray[12] = "Select one or more options as your answer/s. To change your answer, click 'Clear Answer'.";
            $instratctArray[13] = "Select your answer by clicking on one or more boxes. To change your answer, unclick and re-enter.";
            $instratctArray[14] = 'Click one or more boxes to select your answer. To change your answer, unclick and re-enter.';
            $instratctArray[15] = 'Click one or more boxes to select your answer. To change your answer, unclick and re-enter.';
            $instratctArray[16] = 'Input your answer into the space/s provided. To change your answer, delete and re-enter.';
            $instratctArray[17] = "Connect the dots to create the reflection. To change your answer, click 'Clear Answer'.";
            $instratctArray[18] = "Join the dots to answer the question. To change your answer, click 'Clear Answer'.";
            $instratctArray[19] = "Draw the line/s to answer the question. To change your answer, click 'Clear Answer'.";
            $instratctArray[20] = 'Click and place the line in the correct position by moving your mouse over the diagram. To change your answer, click and place the line again.';
            $instratctArray[21] = 'Shade the shape by clicking on the square. Keep clicking to change the options.';
            $instratctArray[22] = 'Select and move the options into the space/s provided. To change your answer, select and move the box again.';
            $instratctArray[23] = "Connect the dots to create the reflection. To change your answer, click 'Clear Answer'.";
            $instratctArray[24] = "Connect the dots to create the reflection. To change your answer, click 'Clear Answer'.";
            $instratctArray[25] = "Connect the dots to create the reflection. To change your answer, click 'Clear Answer'.";
            $instratctArray[26] = "Connect the dots to create the reflection. To change your answer, click 'Clear Answer'.";
            $instratctArray[27] = "Connect the dots to create the reflection. To change your answer, click 'Clear Answer'.";
            $instratctArray[28] = 'Measure the line or angle with the protractor / ruler provided on the screen and input your answer into the box. To change your answer, delete and re-enter.';
            $instratctArray[29] = 'Click on the dots to complete the pie chart.';
            $instratctArray[30] = "Click one or more boxes to select your answer. To change your answer, unclick and re-enter.";
            $instratctArray[31] = 'Input your answer into the space/s provided. To change your answer, delete and re-enter.';
            $instratctArray[32] = 'Input your answer into one or more boxes. To change your answer, delete and re-enter.';

            return $instratctArray;
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}

function multilevel_array_sort($array, $on, $order = SORT_ASC) {

        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    natsort($sortable_array);
                    break;
                case SORT_DESC:
                    natsort($sortable_array);
                    //$sortable_array = array_reverse($sortable_array, false);
                    break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }
    function calculateTaskCompleteTimeOld($maxtime,$remainingtime){
        $timeTaken = '';
        if($remainingtime == 0 || $remainingtime == ''){
            $timeTaken = $maxtime;
        }else if($remainingtime < $maxtime){
            $timeTaken = $maxtime - $remainingtime;
        }else{
            $timeTaken = $remainingtime;
        }
        return $timeTaken;
    }
    
    function calculateTaskCompleteTime($params){
        $timeTaken = '';
        
        if($params['remainingtime'] == 0 || $params['remainingtime'] == ''){
            $timeTaken = $params['quesmaxtime'];
        }else if($params['remainingtime'] > $params['quesmaxtime']){
            $timeTaken = $params['remainingtime'];
        }else{
            //$time_spent = ($studentStoredTestPaper['quesmaxtime'] - $studentStoredTestPaper['remainingtime']) / 60;
            //JT need to change in future
            $time_spent_diff = ($params['quesmaxtime'] - $params['remainingtime']);
            $diff_range = range($time_spent_diff, $time_spent_diff+10);
            if(in_array($params['completetime'], $diff_range)){
                $timeTaken = ($time_spent_diff);
            }else{
                if($params['completetime'] - $time_spent_diff > 4500){
                    $timeTaken = ($time_spent_diff);
                }else{
                    $timeTaken = ($params['completetime']);
                }
            }
            //end JT
        }
        return $timeTaken;
    }