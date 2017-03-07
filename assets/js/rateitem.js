/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


jQuery(document).ready(function () {
    rate_form.init();
});

rate_form = function () {
    return {
        init: function () {

            rate_form.addEvents(jQuery('body'));

        }, addEvents: function (html) {

            html.find('.survey-rate-item').click(function () {

                if (!jQuery('.survey-rate-rated').length) {
                    rate_form.submitItemRatings(jQuery(this));
                } else {
                    title = 'You can only rate once.';
                    message = 'You can only rate this article once. Repeated rating is ignored.';
                    kazist.showDialog(title, message, 'error');
                }

                return;

            });

            return html;

        }, submitItemRatings: function (this_element) {

            var option_id = this_element.attr('option_id');
            var rate_id = this_element.closest('.survey-rate').attr('rate_id');
            var app_id = this_element.closest('.survey-rate').attr('app_id');
            var com_id = this_element.closest('.survey-rate').attr('com_id');
            var subset_id = this_element.closest('.survey-rate').attr('subset_id');
            var item_id = this_element.closest('.cms-content').find('#id').val();

            var data_object = {item_id: item_id, rate_id: rate_id, option_id: option_id, app_id: app_id, com_id: com_id, subset_id: subset_id};

            kazist.callAjaxByRoute('surveys.rates.ajaxsaverate', data_object, true);

            rate_form.updateSurvey(item_id, rate_id, app_id, com_id, subset_id);

        }, updateSurvey: function (item_id, rate_id, app_id, com_id, subset_id) {

            var data_object = {item_id: item_id, rate_id: rate_id, app_id: app_id, com_id: com_id, subset_id: subset_id};

            var form = kazist.callAjaxByRoute('surveys.rates.ajaxloadrate', data_object, true);

            jQuery('.survey-rate').replaceWith(form);

            rate_form.addEvents(jQuery('.survey-rate'));
        }
    };
}();