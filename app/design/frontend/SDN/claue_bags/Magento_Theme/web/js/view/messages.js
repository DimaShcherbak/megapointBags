/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * @api
 */
define([
    'jquery',
    'uiComponent',
    'Magento_Customer/js/customer-data',
    'underscore',
    'escaper',
    'jquery/jquery-storageapi'
], function ($, Component, customerData, _, escaper) {
    'use strict';

    return Component.extend({
        defaults: {
            cookieMessages: [],
            messages: [],
            selector: '.page.messages .message',
            allowedTags: ['div', 'span', 'b', 'strong', 'i', 'em', 'u', 'a'],
            listens: {
                isHidden: 'onHiddenChange'
            }
        },

        /**
         * Extends Component object by storage observable messages.
         */
        initialize: function () {
            this._super();

            this.cookieMessages = _.unique($.cookieStorage.get('mage-messages'), 'text');
            this.messages = customerData.get('messages').extend({
                disposableCustomerData: 'messages'
            });
            // this.RemoveMessageByTime(); // Вызываем автоматическое скрытие сообщений при инициализации

            // Force to clean obsolete messages
            if (!_.isEmpty(this.messages().messages)) {
                customerData.set('messages', {});
            }

            setTimeout(() => {
                $.mage.cookies.set('mage-messages', '', {
                    samesite: 'strict',
                    domain: ''
                });
            }, 5000)
        },

        /**
         * Prepare the given message to be rendered as HTML
         *
         * @param {String} message
         * @return {String}
         */
        prepareMessageForHtml: function (message) {
            var self = this;
            setTimeout(function () {
                self.RemoveMessage();
            }, 3000);
            return escaper.escapeHtml(message, this.allowedTags);
        },

        RemoveMessage: function () {
            $('.page.messages .message').toggleClass('fadeIn fadeOut');
            setTimeout(function () {
                $('.page.messages .message').hide()
            }, 2000);
        },

        onHiddenChange: function (isHidden) {
            var self = this;
            if (isHidden) {
                setTimeout(function () {
                    self.RemoveMessage();
                }, 5000);
            }
            this.isHidden(false);
        },


        // Добавленный метод для автоматического закрытия сообщений через 4 секунды
        // RemoveMessageByTime: function () {
        //     var self = this;
        //     setTimeout(function () {
        //         $('.page.messages .message').hide();
        //     }, 5000);
        // }

    });
});
