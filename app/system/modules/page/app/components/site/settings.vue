<template>

    {{> settings}}

</template>

<script>

    module.exports = {

        paramAttributes: ['node', 'form', 'type'],

        section: {
            name: 'page-settings',
            label: 'Settings',
            priority: 0,
            active: 'page'
        }

    };

</script>
