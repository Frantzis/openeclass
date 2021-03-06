@extends('layouts.default')

@push('head_styles')
<link href="{{ $urlAppend }}js/jstree3/themes/proton/style.min.css" type='text/css' rel='stylesheet'>
@endpush

@push('head_scripts')
<script type='text/javascript' src='{{ $urlAppend }}js/jstree3/jstree.min.js'></script>
<script type='text/javascript' src='{{ $urlAppend }}js/pwstrength.js'></script>
<script type='text/javascript' src='{{ $urlAppend }}js/tools.js'></script>

<script type='text/javascript'>

    function deactivate_input_password () {
            $('#coursepassword').attr('disabled', 'disabled');
            $('#coursepassword').closest('div.form-group').addClass('invisible');
    }

    function activate_input_password () {
            $('#coursepassword').removeAttr('disabled', 'disabled');
            $('#coursepassword').closest('div.form-group').removeClass('invisible');
    }

    function displayCoursePassword() {

            if ($('#courseclose,#courseiactive').is(":checked")) {
                    deactivate_input_password ();
            } else {
                    activate_input_password ();
            }
    }

    var lang = {
        pwStrengthTooShort: "{{ js_escape(trans('langPwStrengTooShort')) }}",
        pwStrengthWeak: "{{ js_escape(trans('langPwStrengthWeak')) }}",
        pwStrengthGood: "{{ js_escape(trans('langPwStrengthGood')) }}",
        pwStrengthStrong: "{{ js_escape(trans('langPwStrengthStrong')) }}"
    }

    function showCCFields() {
        $('#cc').show();
    }
    function hideCCFields() {
        $('#cc').hide();
    }

    $(document).ready(function() {

        $('#coursepassword').keyup(function() {
            $('#result').html(checkStrength($('#coursepassword').val()))
        });

        displayCoursePassword();

        $('#courseopen').click(function(event) {
                activate_input_password();
        });
        $('#coursewithregistration').click(function(event) {
                activate_input_password();
        });
        $('#courseclose').click(function(event) {
                deactivate_input_password();
        });
        $('#courseinactive').click(function(event) {
                deactivate_input_password();
        });

        $('input[name=l_radio]').change(function () {
            if ($('#cc_license').is(":checked")) {
                showCCFields();
            } else {
                hideCCFields();
            }
        }).change();
    });

</script>
@endpush

@section('content')

    <div id='operations_container'>
        {!! $action_bar !!}
    </div>

    <div class='form-wrapper'>
        <form class='form-horizontal' role='form' method='post' action="{{ $form_url }}" onsubmit='return validateNodePickerForm();'>
        <fieldset>
        <div class='form-group'>
            <label for='fcode' class='col-sm-2 control-label'>{{ trans('langCode') }}</label>
            <div class='col-sm-10'>
                <input type='text' class='form-control' name='fcode' id='fcode' value='{{ $public_code }}'>
            </div>
        </div>
        <div class='form-group'>
            <label for='title' class='col-sm-2 control-label'>{{ trans('langCourseTitle') }}:</label>
            <div class='col-sm-10'>
                <input type='text' class='form-control' name='title' id='title' value='{{ q($title) }}'>
            </div>
        </div>
        <div class='form-group'>
            <label for='teacher_name' class='col-sm-2 control-label'>{{ trans('langTeachers') }}:</label>
            <div class='col-sm-10'>
                <input type='text' class='form-control' name='teacher_name' id='teacher_name' value='{{ $teacher_name }} '>
            </div>
        </div>
        <div class='form-group'>
            <label for='Faculty' class='col-sm-2 control-label'>{{ trans('langFaculty') }}:</label>
            <div class='col-sm-10'>
                {!! $buildusernode !!}
            </div>
        </div>
        <div class='form-group'>
            <label for='course_keywords' class='col-sm-2 control-label'>{{ trans('langCourseKeywords') }}</label>
            <div class='col-sm-10'>
                <input type='text' class='form-control' name='course_keywords' id='course_keywords' value='{{ $course_keywords }}'>
            </div>
        </div>
        <div class='form-group'>
            <label class='col-sm-2 control-label'> {{ trans('langCourseFormat') }}:</label>
            <div class='col-sm-10'>
                <div class='radio'>
                  <label>
                    <input type='radio' name='view_type' value='simple' id='simple' {{ $course_type_simple }}>
                    {{ trans('langCourseSimpleFormat') }}
                  </label>
                </div>
                <div class='radio'>
                  <label>
                    <input type='radio' name='view_type' value='units' id='units' {{ $course_type_units }}>
                    {{ trans('langWithCourseUnits') }}
                  </label>
                </div>
                <div class='radio'>
                  <label>
                    <input type='radio' name='view_type' value='wall' id='wall' {{ $course_type_wall }}>
                    {{ trans('langCourseWallFormat') }}
                  </label>
                </div>
            </div>
        </div>

        @if (isset($isOpenCourseCertified)) {
            <input type='hidden' name='course_license' value='{{ getIndirectReference($course_license) }}'>
        @endif

        <div class='form-group'>
            <label class='col-sm-2 control-label'>{{ trans('langOpenCoursesLicense') }}:</label>
            <div class='col-sm-10'>
                <div class='radio'>
                  <label>
                    <input type='radio' name='l_radio' value='0' {{ $license_checked0 }} $disabledVisibility>
                    {{ trans('langLicenseUnset') }}
                  </label>
                </div>
                <div class='radio'>
                  <label>
                    <input type='radio' name='l_radio' value='10' {{ $license_checked10 }} $disabledVisibility>
                    {{ trans('langCopyrightedNotFree') }}
                  </label>
                </div>
                <div class='radio'>
                  <label>
                    <input id='cc_license' type='radio' name='l_radio' value='cc' {{ $cc_checked}} $disabledVisibility>
                    {{ trans("langCMeta['course_license']") }}
                  </label>
                </div>
            </div>
        </div>
        <div class='form-group'>
            <div class='col-sm-10 col-sm-offset-2' id='cc'>
                {!! $license_selection !!}
            </div>
        </div>
        <div class='form-group'>
            <label class='col-sm-2 control-label'>{{ trans('langConfidentiality') }}:</label>
            <div class='col-sm-10'>
                <div class='radio'>
                  <label>
                    <input id='courseopen' type='radio' name='formvisible' value='2' {{ $course_open }}>
                    {!! course_access_icon(COURSE_OPEN) !!}{{ trans('langOpenCourse') }}
                    <span class='help-block'><small>{{ trans('langPublic') }}</small></span>
                  </label>
                </div>
                <div class='radio'>
                  <label>
                    <input id='coursewithregistration' type='radio' name='formvisible' value='1' {{ $course_registration }}>
                    {!! course_access_icon(COURSE_REGISTRATION) !!} {{ trans('langTypeRegistration') }}
                    <span class='help-block'><small>{{ trans('langPrivOpen') }}</small></span>
                  </label>
                </div>
                <div class='radio'>
                  <label>
                    <input id='courseclose' type='radio' name='formvisible' value='0' {{ $course_closed }} {{ $disable_visibility }}>
                    {!! course_access_icon(COURSE_CLOSED) !!} {{ trans('langClosedCourse') }}
                    <span class='help-block'><small>{{ trans('langClosedCourseShort') }}</small></span>
                  </label>
                </div>
                <div class='radio'>
                  <label>
                    <input id='courseinactive' type='radio' name='formvisible' value='3' {{ $course_inactive }} {{ $disable_visibility }}>
                      {!! course_access_icon(COURSE_INACTIVE) !!}{!! trans('langInactiveCourse') !!}
                    <span class='help-block'><small>{{ trans('langCourseInactive') }}</small></span>
                  </label>
                </div>
            </div>
        </div>
        <div class='form-group'>
            <label for='coursepassword' class='col-sm-2 control-label'>{{ trans('langOptPassword') }}:</label>
            <div class='col-sm-10'>
                  <input class='form-control' id='coursepassword' type='text' name='password' value='{{ q($password) }}' autocomplete='off'>
            </div>
            <div class='col-sm-2 text-center padding-thin'>
                <span id='result'></span>
            </div>
        </div>
        <div class='form-group'>
            <label for='Options' class='col-sm-2 control-label'>{{ trans('langLanguage') }}:</label>
            <div class='col-sm-10'>
                {!! $lang_select_options !!}
            </div>
        </div>
        <div class='form-group'>
            <label for='courseoffline' class='col-sm-2 control-label'>{{ trans('langCourseOfflineSettings') }}:</label>
            <div class="col-sm-10">
                <div class="radio">
                    <label>
                        <input type='radio' value='1' name='enable_offline_course' {{ $log_offline_course_enable }} {{ $log_offline_course_inactive }}> {{ trans('langActivate') }}
                        <span class='help-block'><small>{{ trans('langCourseOfflineLegend') }}</small></span>
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type='radio' value='0' name='enable_offline_course' {{ $log_offline_course_disable }} {{ $log_offline_course_inactive }}> {{ trans('langDeactivate') }}
                    </label>
                </div>
            </div>
        </div>
        <div class='form-group'>
            <label class='col-sm-2 control-label'>{{ trans('langCourseUserRequests') }}:</label>
            <div class='col-sm-10'>
                <div class='radio'>
                <div class='radio'>
                  <label>
                        <input type='radio' value='0' name='disable_log_course_user_requests' {{ $log_course_user_requests_enable }} {{ $log_course_user_requests_inactive }}> {{ trans('langActivate') }}
                        <span class='help-block'><small>{{ $log_course_user_requests_disable }}</small></span>
                  </label>
                </div>
                  <label>
                        <input type='radio' value='1' name='disable_log_course_user_requests' {{ $log_course_user_requests_disable }} {{ $log_course_user_requests_inactive }}> {{ trans('langDeactivate') }}
                  </label>
                </div>
            </div>
        </div>
        <div class='form-group'>
            <label class='col-sm-2 control-label'>{{ trans('langCourseSharing') }}:</label>
            <div class='col-sm-10'>
                <div class='radio'>
                  <label>
                        <input type='radio' value='1' name='s_radio' {{ $checkSharingEn }} {{ $sharing_radio_dis }}> {{ trans('langSharingEn') }}
                  </label>
                </div>
                <div class='radio'>
                  <label>
                        <input type='radio' value='0' name='s_radio' {{ $checkSharingDis }} {{ $sharing_radio_dis }}> {{ trans('langSharingDis') }}
                        <span class='help-block'><small>{{ $sharing_dis_label }}</small></span>
                  </label>
                </div>
            </div>
        </div>
        <div class='form-group'>
            <label class='col-sm-2 control-label'>{{ trans('langForum') }}:</label>
            <div class='col-sm-10'>
                <div class='radio'>
                  <label>
                        <input type='radio' value='1' name='f_radio' {{ $checkForumEn }}> {{ trans('langDisableForumNotifications') }}
                  </label>
                </div>
                <div class='radio'>
                  <label>
                        <input type='radio' value='0' name='f_radio' {{ $checkForumDis }}> {{ trans('langActivateForumNotifications') }}
                  </label>
                </div>
            </div>
        </div>

        <div class='form-group'>
        <label class='col-sm-2 control-label'>
            {{ trans('langCourseRating') }}:
        </label>
        <div class='col-sm-10'>
                <div class='radio'>
                  <label>
                        <input type='radio' value='1' name='r_radio' {{ $checkRatingEn }}> {{ trans('langRatingEn') }}
                  </label>
                </div>
                <div class='radio'>
                  <label>
                        <input type='radio' value='0' name='r_radio' {{ $checkRatingDis }}> {{ trans('langRatingDis') }}
                  </label>
                </div>
            </div>
        </div>
        <div class='form-group'>
            <label class='col-sm-2 control-label'>{{ trans('langCourseAnonymousRating') }}:</label>
            <div class='col-sm-10'>
                <div class='radio'>
                  <label>
                        <input type='radio' value='1' name='ran_radio' {{ $checkAnonRatingEn }} {{ $anon_rating_radio_dis }}> {{ trans('langRatingAnonEn') }}
                  </label>
                </div>
                <div class='radio'>
                  <label>
                        <input type='radio' value='0' name='ran_radio' {{ $checkAnonRatingDis }} {{ $anon_rating_radio_dis }}> {{ trans('langRatingAnonDis') }}
                        <span class='help-block'><small>{{ $anon_rating_dis_label }}</small></span>
                  </label>
                </div>
            </div>
        </div>
        <div class='form-group'>
            <label class='col-sm-2 control-label'>{{ trans('langCourseCommenting') }}:</label>
            <div class='col-sm-10'>
                <div class='radio'>
                  <label>
                        <input type='radio' value='1' name='c_radio' {{ $checkCommentEn }}> {{ trans('langCommentsEn') }}
                  </label>
                </div>
                <div class='radio'>
                  <label>
                        <input type='radio' value='0' name='c_radio' {{ $checkCommentDis }}> {{ trans('langCommentsDis') }}
                  </label>
                </div>
            </div>
        </div>
        <div class='form-group'>
            <label class='col-sm-2 control-label'>{{ trans('langAbuseReport') }}:</label>
            <div class='col-sm-10'>
                <div class='radio'>
                  <label>
                        <input type='radio' value='1' name='ar_radio' {{ $checkAbuseReportEn }}> {{ trans('langAbuseReportEn') }}
                  </label>
                </div>
                <div class='radio'>
                  <label>
                        <input type='radio' value='0' name='ar_radio' {{ $checkAbuseReportDis }}> {{ trans('langAbuseReportDis') }}
                  </label>
                </div>
            </div>
        </div>
        {!! showSecondFactorChallenge() !!}
        <div class='form-group'>
            <div class='col-sm-10 col-sm-offset-2'>
                <input class='btn btn-primary' type='submit' name='submit' value='{{ trans('langSubmit') }}'>
            </div>
        </div>
    </fieldset>
    {!! generate_csrf_token_form_field() !!}
    </form>
</div>

@endsection
