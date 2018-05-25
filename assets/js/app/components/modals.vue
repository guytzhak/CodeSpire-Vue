<template>
    <transition name="fade">
        <div class="modal_outer" v-if="show">
            <div id="modals" v-bind:class="classObject" class="actions_modal">
                <span class="close_span" @click.prevent="close_modal">
                    <span class="line one"></span>
                    <span class="line two"></span>
                </span>
                <div class="content">
                    <ul>
                        <li v-for="notice in notices" v-html="notice">

                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </transition>
</template>
  
<script>

    import Bus from '../bus.js';

    export default  {
        mounted() {
        },
        created() {
            Bus.$on('popup', response => {

                this.show = true;
                this.status = response.status;
                this.notices = response.notices;
                if( response.status == 1 ) {
                    this.classObject.success = true;
                } else if( response.status == 0 ) {
                    this.classObject.fail = true;
                }
                setTimeout( () => {
                    this.show = false;
                }, 2000);
            });
        },
        data () {
            return {
                show: false,
                notices: '',
                status: '',
                classObject: {
                    success: false,
                    fail: false
                }
            }
        },
        methods: {
            close_modal() {
                this.show = 0;
            },
        },
    }
</script>

