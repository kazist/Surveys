/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


jQuery(document).ready(function () {
    question_form.init();
});

question_form = function () {
    return {
        init: function () {

            question_form.addEvents(jQuery('body'));

        }, addEvents: function (html) {

            html.find('#type').change(function () {
                question_form.addTypeEvents(jQuery(this));
            });

            html.find('.add-question-option').click(function () {
                question_form.addQuestionOptionEvents(jQuery(this));
            });

            html.find('.delete-question-option').click(function () {
                question_form.deleteQuestionOptionEvents(jQuery(this));
            });

            html.find('.save-new-question').click(function () {
                question_form.saveNewQuestion(jQuery(this));
            });

            html.find('.save-existing-question').click(function () {
                question_form.saveExistingQuestion(jQuery(this));
            });

            html.find('.survey-question-delete').click(function () {
                question_form.deleteSurveyQuestionEvents(jQuery(this));
            });

            return html;

        }, addTypeEvents: function (this_element) {

            var type = this_element.val();

            if (type !== '') {
                jQuery('.question-text').hide();
                jQuery('.question-' + type + '-sample').show();

                if (!jQuery('.question-' + type + '-sample tbody tr').length) {
                    jQuery('.question-' + type + '-sample .add-question-option').trigger("click");
                }
            }

        }, addQuestionOptionEvents: function (this_element) {

            var type = this_element.attr('question_type');
            var option_html = jQuery('.option-' + type + '-tr-sample tbody').html();
            var tr_length = parseInt(this_element.closest('table').find('tbody tr').length) + 1;

            option_html = question_form.addEvents(jQuery(option_html));

            option_html.find('.option-answer').html('<input type="checkbox" name="form[quiz_params][new][answer]" value="1">');
            option_html.find('.option-answer input').attr('name', 'form[quiz_params][new][answer][' + tr_length + ']');
            option_html.find('.option-label input').attr('name', 'form[quiz_params][new][label][' + tr_length + ']');
            option_html.find('.option-image input').attr('name', 'form[quiz_params][new][image][' + tr_length + ']');

            option_html.find('.option-answer input').addClass('question-input');
            option_html.find('.option-label input').addClass('question-input');
            option_html.find('.option-image input').addClass('question-input');

            this_element.closest('table').find('tbody').append(option_html);
            this_element.closest('table').find('tbody').iCheck({
                checkboxClass: 'icheckbox_minimal',
                radioClass: 'iradio_minimal'
            });


        }, deleteQuestionOptionEvents: function (this_element) {

            var can_delete = confirm('Are you sure?');

            if (can_delete) {
                this_element.closest('tr').remove();
            }
        }, deleteSurveyQuestionEvents: function (this_element) {

            var can_delete = confirm('Are you sure?');

            if (can_delete) {

                var survey_id = this_element.closest('form').find('.survey_id').val();
                var question_id = this_element.attr('question_id');
                var survey_question_id = this_element.attr('survey_question_id');

                if (question_id) {
                    data = {};
                    data = kazist.createNestedObject(data, ["form[question_id]"], question_id);
                    data = kazist.createNestedObject(data, ["form[survey_id]"], survey_id);
                    data = kazist.createNestedObject(data, ["form[id]"], survey_question_id);

                    var msg = kazist.callAjaxByRoute('surveys.questions.ajaxdeletesurveyquestion', data, true);
                    survey_form.loadSurveyForm(survey_id);
                }
            }

        }, saveExistingQuestion: function (this_element) {

            var title = 'Validation Error';
            var message = '';
            var is_valid = true;

            var question_id = this_element.closest('form').find('.existing_question').val();
            var survey_id = this_element.closest('form').find('.survey_id').val();


            if (!parseInt(survey_id)) {
                is_valid = false;
                message += '<br>' + 'No Survey set.';
            }

            if (question_id === '') {
                is_valid = false;
                message += '<br>' + 'Select Question Above.';
            }

            if (is_valid) {
                data = {};
                data = kazist.createNestedObject(data, ["form[question_id]"], question_id);
                data = kazist.createNestedObject(data, ["form[survey_id]"], survey_id);

                var msg = kazist.callAjaxByRoute('surveys.questions.ajaxaddsurveyquestion', data, true);
                survey_form.loadSurveyForm(survey_id);

            } else {

                kazist.showDialog(title, message, 'error');
                return false;
            }
        }, saveNewQuestion: function (this_element) {
            var title = 'Validation Error';
            var message = '';
            var is_valid = true;

            var question_id = this_element.closest('form').find('.question_id').val();
            var survey_id = this_element.closest('form').find('.survey_id').val();
            var type_val = this_element.closest('.question-form').find('#type').val();
            var question_val = this_element.closest('.question-form').find('#question').val();
            var question_text = this_element.closest('.question-form').find('.question-' + type_val + '-sample .option-label input');

            question_id = (typeof question_id !== "undefined") ? question_id : 0;
            survey_id = (typeof survey_id !== "undefined") ? survey_id : 0;

            question_text.each(function (index, value) {

                this_element = jQuery(this);

                if (this_element.val() === '') {
                    message += '<br>' + type_val + ' Options(labels) ' + (index + 1) + ' should not be empty.';
                    this_element.addClass('has-error');
                    is_valid = false;
                }

                this_element.removeClass('has-error');

            });

            if (type_val === '') {
                is_valid = false;
                message += '<br>' + 'Type is a required Fields and can not be empty.';
            }

            if (question_val === '') {
                is_valid = false;
                message += '<br>' + 'Question is a required Fields and can not be empty.';
            }

            if (is_valid) {

                var question_type = this_element.closest('.question-form').find(':input');
                var data = kazist.serializeFormJSON(question_type);

                data = kazist.createNestedObject(data, ["form[id]"], question_id);
                data = kazist.createNestedObject(data, ["form[survey_id]"], survey_id);

                var msg = kazist.callAjaxByRoute('surveys.questions.ajaxaddquestion', data, true);

                if (typeof survey_id == "undefined") {
                    if (typeof msg.question_id != "undefined") {
                        window.location = kazicode.url + '?surveys/questions/edit' + msg.question_id;
                    } else {
                        window.location = kazicode.url + '?surveys/questions';
                    }
                } else {
                    if (typeof msg.question_id != "undefined") {
                        // survey_form.new_question_id = msg.question_id;
                        survey_form.loadSurveyForm(survey_id);
                    }
                }


                return true;

            } else {

                kazist.showDialog(title, message, 'error');

                return false;
            }

        }
    };
}();