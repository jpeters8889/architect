import Architect from "../ArchitectApplication";

export default {
    props: {
        field: {},
    },

    data: () => ({
        value: '',
    }),

    mounted() {
        this.setValue();

        window.Architect.$on(this.field.value + '-value', value => {
            this.value = value;
        });
    },

    methods: {
        setValue() {
            this.value = '';

            this.field.fillFormData = this.fillFormData;

            if (this.field.value !== undefined && this.field.value !== null) {
                this.value = this.field.value;
            }
        },

        fillFormData(formData) {
            formData.append(this.field.name, String(this.value));
        },

        onChange(value) {
            this.value = value;
        }
    }
}