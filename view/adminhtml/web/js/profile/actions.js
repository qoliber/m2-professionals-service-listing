/**
 * Created by Qoliber
 *
 * @category    Qoliber
 * @package     Qoliber_Psl
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */
define([
    'jquery',
    'uiComponent',
    'Magento_Ui/js/modal/modal',
    'mage/translate'
], function ($, Component, modal, $t) {
    'use strict';

    return Component.extend({
        defaults: {
            profileId: 0,
            customerId: 0,
            ajaxUrl: '',
            rejectModalSelector: '#reject-modal',
            rejectBtnSelector: '#reject-profile-btn',
            approveBtnSelector: '#approve-profile-btn',
            rejectionTextSelector: '#rejection-reason-text',
            statusMessageSelector: '#profile-action-status',
            profileStatusSelector: '#profile-status'
        },

        /**
         * Initialize component
         *
         * @param config
         * @returns {*}
         */
        initialize: function (config) {
            this._super();
            if (config.element) {
                this.element = config.element;
            }

            this.initializeModal();
            this.bindEvents();

            return this;
        },

        /**
         * Initialize modal dialog
         */
        initializeModal: function () {
            const modalOptions = {
                type: 'popup',
                responsive: true,
                title: $t('Disapprove Profile'),
                buttons: [{
                    text: $t('Cancel'),
                    class: 'action-secondary',
                    click: function () {
                        this.closeModal();
                    }
                }, {
                    text: $t('Needs Changes'),
                    class: 'action-primary',
                    click: () => {
                        const reason = $(this.rejectionTextSelector).val();
                        if (!reason) {
                            this.showStatusMessage('error', $t('Please provide a rejection reason'));
                            return;
                        }
                        this.processProfile('disapproved', reason);
                    }
                }]
            };

            modal(modalOptions, $(this.rejectModalSelector));
        },

        /**
         * Bind event handlers
         */
        bindEvents: function () {
            const $container = $(this.element);

            $container.find(this.rejectBtnSelector).on('click', () => {
                $(this.rejectModalSelector).modal('openModal');
            });

            $container.find(this.approveBtnSelector).on('click', () => {
                this.processProfile('approved');
            });
        },

        /**
         * Process profile via Ajax and update status message
         *
         * @param action
         * @param rejectionReason
         */
        processProfile: function (action, rejectionReason = null) {
            $.ajax({
                url: this.ajaxUrl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: action,
                    profile_id: this.profileId,
                    customer_id: this.customerId,
                    rejection_reason: rejectionReason,
                    form_key: window.FORM_KEY
                },
                beforeSend: () => {
                    $('body').trigger('processStart');
                    this.showStatusMessage('info', $t('Processing...'));
                },
                success: (response) => {
                    $('body').trigger('processStop');
                    if (response.success) {
                        this.showStatusMessage(
                            'success',
                            $t('Action completed successfully!'),
                            response.profileStatus
                        );
                    } else {
                        this.showStatusMessage(
                            'error',
                            response.message || $t('An error occurred while processing the profile.')
                        );
                    }
                    $(this.rejectModalSelector).modal('closeModal');
                },
                error: () => {
                    $('body').trigger('processStop');
                    this.showStatusMessage('error', $t('An error occurred while processing the request.'));
                }
            });
        },

        /**
         *
         * @param type
         * @param message
         * @param profileStatus
         */
        showStatusMessage: function (type, message, profileStatus = null) {
            const $status = $(this.statusMessageSelector);
            $status.text(message).fadeIn();

            const $profileStatus = $(this.profileStatusSelector);
            $profileStatus.text(profileStatus).fadeIn();

            setTimeout(() => {
                $status.fadeOut();
            }, 30000);
        }
    });
});
