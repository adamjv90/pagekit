var $ = require('jquery');
var _ = require('lodash');
var UIkit = require('uikit');

var Site = Vue.extend({

    mixins: [require('./tree')],

    data: function() {
        return _.merge({selected: null}, window.$data);
    },

    computed: {

        sections: function () {

            var sections = [];

            _.each(this.$options.components, function (component) {
                if (component.options.section) {
                    sections.push(component.options.section);
                }
            });

            return sections;
        }

    },

    http: {

        error: function (msg) {
            UIkit.notify(msg, 'danger');
        }

    },

    validators: {

        unique: function(value) {
            var menu = _.find(this.menus, { id: value });
            return !menu || this.menu.oldId == menu.id;
        }

    },

    events: {

        loaded: 'select'

    },

    methods: {

        select: function(node) {

            if (!node) {
                node = this.selected && _.find(this.nodes, { id: this.selected.id }) || this.selectFirst();
            }

            this.$set('selected', node);
        },

        selectFirst: function() {

            var self = this, first = null;

            this.menus.some(function (menu) {
                return first = _.first(self.tree[menu.id]);
            });

            return first;
        }

    },

    components: {
        'menu-list': require('./components/menus.vue'),
        'node-edit': require('./components/edit.vue'),
        'alias':     require('./components/alias.vue')
    }

});

$(function () {

    new Site().$mount('#site');

});

module.exports = Site;
