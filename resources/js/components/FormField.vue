<template>
    <DefaultField :field="currentField" :errors="errors" :show-help-text="showHelpText" :full-width-content="true">
        <template #field>
            <div v-for="locale in locales"
                :style="[locale == 'ar' ? { 'border-bottom': '2px solid #7c858e', direction: 'rtl' } : {}]">
                <input v-if="template == null" type="text" class="my-2 w-full form-control form-input form-input-bordered"
                    :class="errorClasses" :placeholder="currentField.name" v-model="withoutTemplateValue[locale]" />
                <div v-else v-for="(item, index) in template" class="my-2 inline-block">
                    <div v-if="item.word[locale].split('')[0] == '+'">
                        <input type="text" class="form-control form-input form-input-bordered inline-block w-auto mx-1 mb-1"
                            :class="errorClasses" :placeholder="item.word[locale].substr(1)" required
                            v-model="tempName[locale][index]" />
                    </div>
                    <div v-else-if="item.word[locale].split('')[0] == '-'">
                        <select v-model="tempName[locale][index]" required
                            class="form-control form-input form-input-bordered inline-block w-auto mx-1 mb-1"
                            :class="errorClasses">
                            <option value="null" disabled>{{ item.word[locale].substr(1).split('-')[0] }}</option>
                            <option v-for="optionItem in item.word[locale].substr(1).split('-').slice(1)"
                                :value="optionItem">{{ optionItem }}</option>
                        </select>
                    </div>
                    <div v-else-if="item.word[locale].split('')[0] != null">
                        <input type="text" readonly required :size="item.word[locale].length"
                            class="form-control form-input form-input-bordered inline-block w-auto mx-1 mb-1"
                            v-model="tempName[locale][index]" />
                    </div>
                </div>
            </div>
        </template>
    </DefaultField>
</template>

<script>
import { DependentFormField, HandlesValidationErrors } from 'laravel-nova'

export default {
    mixins: [DependentFormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    data() {
        return {
            fields: [],
            locales: ['ar', 'en'],
            template: null,
            tempName: null,
            withoutTemplateValue: null,
            category: null
        }
    },
    mounted() {

        this.watchedComponents.forEach(component => {
            let attribute = 'value'
            // nova-nested-tree-attach-many
            if (component.props.field.component === 'belongs-to-field') {
                attribute = 'selectedResource';
            }

            (component.component.proxy).$watch(attribute, (value) => {
                this.category = (value && attribute === 'selectedResource') ? value.value : value;
                this.updateResults();
            }, { immediate: true });

        });

    },
    computed: {

        watchedComponents() {
            return this.$parent.$.subTree.children[0].children[0].children.filter(component => {
                return this.isWatchingComponent(component);
            })
        },

        endpoint() {
            return this.currentField.endpoint
                .replace('{' + this.currentField.category + '}', this.category ? this.category : '')
                .replace('{product}', this.resourceId ? this.resourceId : null)
        },
    },
    methods: {
        isWatchingComponent(component) {
            return component.props.field !== undefined && component.props.field.attribute === this.currentField.category;
        },

        updateResults() {
            if (this.notWatching() || (this.category != null && this.category !== '')) {
                Nova.request().get(this.endpoint)
                    .then(response => {
                        if (response.data.template != null && response.data.template != '') {
                            this.template = response.data.template;
                            if (response.data.temp_name) {
                                this.tempName = {
                                    'ar': response.data.temp_name.ar.split('(^)'),
                                    'en': response.data.temp_name.en.split('(^)')
                                }
                            } else {
                                this.tempName = {
                                    'ar': new Array(this.template.length).fill(null),
                                    'en': new Array(this.template.length).fill(null)
                                }
                            }
                        } else {
                            this.template = null;
                            if (response.data.name) {
                                this.withoutTemplateValue = {
                                    'ar': response.data.name.ar,
                                    'en': response.data.name.en
                                }
                            } else {
                                this.withoutTemplateValue = {
                                    'ar': null,
                                    'en': null
                                }
                            }
                        }
                    })
            }
        },

        notWatching() {
            return this.currentField.category === undefined;
        },

        /*
        * Set the initial, internal value for the field.
        */
        setInitialValue() {
            this.value = this.fieldValue || ''
        },

        /**
        * Fill the given FormData object with the field's internal value.
        */
        fill(formData) {
            if (this.tempName)
                formData.append(this.currentField.attribute, JSON.stringify(this.tempName) || '')
            else
                formData.append('withoutTemplate', JSON.stringify(this.withoutTemplateValue) || '')
        },
    }
}
</script>
