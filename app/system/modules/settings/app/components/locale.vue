<template>

    <div class="uk-margin uk-flex uk-flex-space-between uk-flex-wrap" data-uk-margin>
        <div data-uk-margin>

            <h2 class="uk-margin-remove">{{ 'Localization' | trans }}</h2>

        </div>
        <div data-uk-margin>

            <button class="uk-button uk-button-primary" type="submit">{{ 'Save' | trans }}</button>

        </div>
    </div>

    <div class="uk-form-row">
        <label for="form-sitelocale" class="uk-form-label">{{ 'Site Locale' | trans }}</label>
        <div class="uk-form-controls">
            <select id="form-sitelocale" class="uk-form-width-large" v-model="option.site.locale" options="locales | toOptions"></select>
        </div>
    </div>

    <div class="uk-form-row">
        <label for="form-adminlocale" class="uk-form-label">{{ 'Admin Locale' | trans }}</label>
        <div class="uk-form-controls">
            <select id="form-adminlocale" class="uk-form-width-large" v-model="option.admin.locale" options="locales | toOptions"></select>
        </div>
    </div>

    <div class="uk-form-row">
        <label for="form-timezone" class="uk-form-label">{{ 'Time Zone' | trans }}</label>
        <div class="uk-form-controls">
            <select id="form-timezone" class="uk-form-width-large" v-model="option.timezone" options="timezones | toOptions"></select>
        </div>
    </div>

</template>

<script>

    module.exports = {

        section: {
            name: 'settings-locale',
            label: 'Localization',
            icon: 'uk-icon-globe',
            priority: 20
        },

        paramAttributes: ['config', 'options'],

        data: function() {
            return window.$system;
        },

        computed: {

            option: function() {
                return this.$root.$get('options.system');
            }

        }

    };

</script>
