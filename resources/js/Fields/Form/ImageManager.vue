<template>
    <div class="form-control">
        <input type="file" class="hidden" ref="fileTrigger" accept="image/*" multiple @change="processImages" />

        <ul class="flex flex-wrap align-start">
            <li class="mr-2 mb-2 border-1 border-highlight rounded max-w-imageManager relative flex justify-center items-center text-6xl text-highlight cursor-pointer max-h-imageManager"
                :class="images.length > 0 ? 'initial' : 'hidden'"
                v-for="image in images"
            >
                <div v-if="image.processing">
                    <font-awesome-icon :icon="['fas', 'circle-notch']" spin></font-awesome-icon>
                </div>
                <div v-else>
                    <img :src="'/'+image.path" alt="" />

                    <div class="absolute left-0 bottom-0 m-1 flex flex-wrap">
                        <div v-for="(display, button) in metas.buttons"
                             v-if="display"
                             class="rounded text-sm w-auto cursor-pointer mr-1 p-1"
                             :class="generateButtonClass(button, image)"
                             @click="handleImageButtonClick(button, image)"
                        >
                            <font-awesome-icon :icon="generateButtonIcon(button)"></font-awesome-icon>
                        </div>
                    </div>
                </div>
            </li>

            <li class="mr-2 mb-2 border-1 border-highlight rounded min-w-imageManager relative flex justify-center items-center text-6xl text-highlight cursor-pointer h-imageManager"
                @click="uploadImage"
            >
                <font-awesome-icon :icon="['fas', 'plus']"></font-awesome-icon>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        props: ['name', 'value', 'metas'],

        data: () => ({
            images: [],

            selectedImages: {},

            hasImages: {},
        }),

        mounted() {
            Object.keys(this.metas.buttons).forEach((button) => {
                if (this.metas.buttons[button] === true) {
                    this.$set(this.selectedImages, button, '');
                    this.$set(this.hasImages, button, false);
                }
            });
        },

        methods: {
            uploadImage() {
                this.$refs.fileTrigger.click();
            },

            processImages() {
                const files = this.$refs.fileTrigger.files;
                const data = new FormData;
                let mapping = [];

                for (let x = 0; x < files.length; x++) {
                    data.append(`files[${x}]`, files[x]);
                    mapping.push(this.pushImage());
                }

                Architect.request().post('/images', data, {'Content-Type': 'multipart/form-data'})
                    .then((response) => {
                        response.data.forEach((image, index) => {
                            this.displayImage(image, mapping[index]);
                        });
                    });
            },

            pushImage() {
                let location = this.images.length;
                this.$set(this.images, location, {processing: true});

                return location;
            },

            displayImage(image, index) {
                this.$set(this.images, index, {
                    processing: false,
                    ...image
                });
            },

            handleImageButtonClick(button, image) {
                if (button === 'insert') {
                    // @todo
                    return;
                }

                let imageText = image.filename;
                let toSelect = true;

                if (this.selectedImages[button] === image.filename) {
                    imageText = '';
                    toSelect = false;
                }

                this.$set(this.selectedImages, button, imageText);
                this.$set(this.hasImages, button, toSelect);
            },

            generateButtonClass(button, image) {
                if (this.selectedImages[button] === image.filename) {
                    return 'bg-primary text-white';
                }

                if (this.hasImages[button] === true) {
                    return 'bg-9 text-5';
                }

                return 'bg-6 text-black';
            },

            generateButtonIcon(button) {
                switch (button) {
                    case 'social':
                        return ['fab', 'facebook-square'];
                    case 'main':
                        return ['fas', 'home'];
                    case 'square':
                        return ['fas', 'crop'];
                    case 'insert':
                        return ['fas', 'paste'];
                }
            }
        }
    }
</script>