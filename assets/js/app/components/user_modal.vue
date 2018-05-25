<template>
    <transition name="fade">
        <div class="modal_outer" v-if="show">
            <div id="modals" class="actions_modal login_modal">
                <span class="close_span" @click.prevent="close_modal">
                    <span class="line one"></span>
                    <span class="line two"></span>
                </span>
                <div class="content">

                </div>
            </div>
        </div>
    </transition>
</template>

<script>

    export default {
        props: ['title'],
        mounted() {
            console.log('Component mounted.');
            jQuery('a.login_modal').on('click', () => {
                this.show = !this.show;
                this.type = 'login';
            });
        },
        created() {
        },
        data() {
            return {
                show: false,
            }
        },
        methods: {
            close_modal() {
                this.show = 0;
            },
        },
    }
</script>

