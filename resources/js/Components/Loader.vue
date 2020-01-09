<template>
    <div class="top-0 left-0 flex justify-center items-center w-full h-full z-50"
         :class="[background, backgroundPosition]"
         v-show="show">
        <div class="rounded-full animate-fast animate-spin animate-linear animate-infinite"
             :class="fadedBorderColor"
             :style="loaderStyles">
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            show: {
                type: Boolean,
                default: false
            },
            width: {
                type: String,
                default: '50%',
            },
            height: {
                type: String,
                default: '50%',
            },
            maxWidth: {
                type: String,
                default: '50px',
            },
            maxHeight: {
                type: String,
                default: '50px',
            },
            background: {
                type: String,
                default: 'bg-white-50',
            },
            fadedBorderColor: {
                type: String,
                default: 'border-highlight-10',
            },
            primaryBorderColor: {
                type: String,
                default: 'var(--highlight)',
            },
            backgroundPosition: {
                type: String,
                default: 'absolute',
            },
            borderWidth: {
                type: String,
                default: '5px',
            }
        },

        computed: {
            loaderStyles() {
                return {
                    borderTopColor: this.primaryBorderColor,
                    borderWidth: this.borderWidth,
                    width: this.width,
                    height: this.height,
                    maxWidth: this.maxWidth,
                    maxHeight: this.maxHeight,
                }
            }
        }
    }
</script>