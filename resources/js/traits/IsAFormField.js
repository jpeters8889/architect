import Architect from "../ArchitectApplication";

export default {
    props: {
        field: {},
    },

    data: () => ({
        actualValue: '',
    }),

    mounted() {
        this.setValue();

        window.Architect.$on(this.field.value + '-value', value => {
            this.actualValue = value;
        });
    },

    methods: {
        setValue() {
            this.actualValue = '';

            this.field.fillFormData = this.fillFormData;

            if (this.field.value !== undefined && this.field.value !== null) {
                this.actualValue = this.field.value;
            }
        },

        fillFormData(formData) {
            formData.append(this.field.name, String(this.actualValue));
        },

        onChange(value) {
            this.actualValue = value;
        }
    }
}