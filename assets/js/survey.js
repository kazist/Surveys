/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


jQuery(document).ready(function () {
    survey_form.init();
});

survey_form = function () {
    return {
        new_question_id: '',
        init: function () {

            survey_form.addEvents(jQuery('body'));

        }, addEvents: function (html) {

            html.find('.survey-add-new').click(function () {
                survey_form.addNewSurveyQuestionEvents(jQuery(this));
            });

            html.find('.survey-add-existing').click(function () {
                survey_form.addExistingSurveyQuestionEvents(jQuery(this));
            });

            html.find('.save-survey').click(function () {
                survey_form.saveNewSurvey(jQuery(this));
            });

            html.find('.show-survey-form').click(function () {
                survey_form.loadSurveyForm('', 0);
            });

            return html;

        }, addNewSurveyQuestionEvents: function (this_element) {

            var data_object = {type: 'new'};
            var survey_id = this_element.closest('form').find('.survey_id').val();

            if (typeof survey_id === "undefined" || !survey_id) {
                survey_form.saveNewSurvey(this_element);
                return false;
            }

            var form = kazist.callAjaxByRoute('surveys.questions.ajaxloadquestionform', data_object, true);

            jQuery('.survey-question-form').html(form);

            question_form.addEvents(jQuery('.survey-question-form'));


        }, addExistingSurveyQuestionEvents: function (this_element) {

            var data_object = {type: 'existing'};
            var survey_id = this_element.closest('form').find('.survey_id').val();

            if (typeof survey_id === "undefined" || !survey_id) {
                survey_form.saveNewSurvey(this_element);
                return false;
            }

            var form = kazist.callAjaxByRoute('surveys.questions.ajaxloadquestionform', data_object, true);

            jQuery('.survey-question-form').html(form);

            question_form.addEvents(jQuery('.survey-question-form'));

        }, saveNewSurvey: function (this_element) {

            var title = 'Validation Error';
            var message = '<b>Survey Must be Saved First before Adding Questions.</b>';
            var is_valid = true;


            var survey_id = this_element.closest('form').find('#survey_id').val();
            var survey_title = this_element.closest('form').find('#title').val();
            var description = this_element.closest('form').find('#description').val();

            if (survey_title === '') {
                is_valid = false;
                message += '<br>' + 'Survey Title is a required Fields and can not be empty.'
            }

            if (description === '' || description === ' ' || description === '  ' || description === '  ') {
                is_valid = false;
                message += '<br>' + 'Description is a required Fields and can not be empty.'
            }

            if (is_valid) {

                data = {};
                data = kazist.createNestedObject(data, ["form[id]"], survey_id);
                data = kazist.createNestedObject(data, ["form[title]"], survey_title);
                data = kazist.createNestedObject(data, ["form[description]"], description);

                var msg = kazist.callAjaxByRoute('surveys.surveys.ajaxsavesurvey', data, true);

                if (typeof msg.survey_id != "undefined") {
                    survey_form.loadSurveyForm(msg.survey_id, 0);
                }

            } else {
                kazist.showDialog(title, message, 'error');
            }

        }, loadSurveyForm: function (survey_id, hide_on_new) {

            var data_object = {survey_id: survey_id, hide_on_new: hide_on_new}

            var form = kazist.callAjaxByRoute('surveys.surveys.ajaxloadsurveyform', data_object, true);

            jQuery('.survey-form').html(form);
            jQuery('#survey_id').val(survey_id);

            survey_form.addEvents(jQuery('.survey-form'));
            question_form.addEvents(jQuery('.survey-form'));

        }

    };
}();