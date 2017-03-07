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
        init: function () {

            survey_form.addEvents(jQuery('body'));

        }, addEvents: function (html) {

            html.find('.submit-survey-result').click(function () {
                survey_form.submitSurvey(jQuery(this));
            });

            html.find('.survey-view-result').click(function () {
                var survey_id = jQuery(this).attr('survey_id');
                survey_form.updateSurvey(survey_id, 1);
            });

            html.find('.survey-view-form').click(function () {
                var survey_id = jQuery(this).attr('survey_id');
                survey_form.updateSurvey(survey_id, 0);
            });

            survey_form.showCharts();

            return html;

        }, submitSurvey: function (this_element) {

            var survey = this_element.closest('.survey-survey').find(':input');
            var survey_id = this_element.closest('.survey-survey').find('.survey_id').val();

            var data = kazist.serializeFormJSON(survey);

            data = kazist.createNestedObject(data, ["form[survey_id]"], survey_id);

            kazist.callAjaxByRoute('surveys.surveys.ajaxsaveanswers', data, true);

            survey_form.updateSurvey(survey_id, 1);

        }, updateSurvey: function (survey_id, show_result) {

            var data_object = {survey_id: survey_id, 'show_result': show_result};

            console.log(data_object);

            var form = kazist.callAjaxByRoute('surveys.surveys.ajaxloadresult', data_object, true);

            jQuery('.take-survey-form').replaceWith(form);

            survey_form.addEvents(jQuery('.take-survey-form'));

        }, showCharts: function () {

            var question_element = jQuery('.survey-result-question');

            question_element.each(function (index) {

                var this_element = jQuery(this);
                var question_id = this_element.attr('question_id');
                var question_chart = this_element.attr('question_chart');
                var question_title = this_element.attr('question_title');
                var question_data = window['survey_result_question_' + question_id];
console.log(question_title);
                kazist.loadChart(this_element, question_chart, question_title, 'Result', question_data);

            });

        }
    };
}();