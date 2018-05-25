<template>
    <transition name="fade">
        <div class="modal_outer" v-if="show">
            <div id="modals" v-bind:class="classObject" class="actions_modal">
                <span class="close_span" @click.prevent="close_modal">
                    <span class="line one"></span>
                    <span class="line two"></span>
                </span>
                <div class="content">

                    <div v-html="modalContent">

                    </div>

                    <Autocomplete
                            :items="filteredData"
                            :auto-select-one-item="false"
                            :min-len="minLen"
                            v-model="item"
                            :get-label="getLabel"
                            :component-item='template'
                            @update-items="changeInputEvent"
                            @item-selected="itemSelected"
                            placeholder="חפשו את עיר או ישוב המשלוח"
                            v-if="!successMode"
                    >
                    </Autocomplete>
                    <button
                            class="btn btn-primary"
                            @click.prevent="checkSelectedLocation"
                            v-if="!successMode">בדיקה</button>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
    import Bus from '../bus.js';
    import ItemTemplate from './ItemTemplate.vue'
    import Autocomplete from 'v-autocomplete'

    export default  {
        components: {
            ItemTemplate,
            Autocomplete
        },
        computed: {
            filteredData: function () {
                var filterKey = this.filterKey;
                var data = this.items;
                if (filterKey) {
                    data = data.filter(function (row) {
                        return Object.keys(row).some(function (key) {
                            return String(row[key]).toLowerCase().indexOf(filterKey) > -1
                        })
                    })
                }

                return data
            }
        },
        created() {
            if( !this.hasCookie('locations_popup') ) {
                this.get_locations_start();
                this.getLocationsCheckContent();
                Bus.$on('popup_check_location', response => {
                    this.show = true;
                    this.status = response.status;
                    if (response.status == 1) {
                        this.classObject.success = true;
                    } else if (response.status == 0) {
                        this.classObject.fail = true;
                    }
                });
            }
        },
        props: ['modal_content'],
        data () {
            return {
                show: false,
                status: '',
                classObject: {
                    success: false,
                    fail: false,
                    check_location_modal: true
                },
                items: [],
                item: '',
                template: ItemTemplate,
                filterKey: '',
                selectedValue: '',
                minLen: 0,
                modalContent: '',
                successMode: false
            }
        },
        methods: {
            close_modal() {
                this.show = 0;
                this.addCookie();
            },
            getLocationsCheckContent() {
                axios.get('/wp-json/cs/v1/get_locations_check_content/')
                    .then((response) => {
                        this.modalContent = response.data;
                    });
            },
            get_locations_start() {
                axios.get('/wp-json/cs/v1/get_available_locations/')
                    .then((response) => {
                        this.items = response.data;
                    });
            },
            getLabel(item) {
                return item.name;
            },
            changeInputEvent(str) {
                this.selectedValue = '';
                this.filterKey = str;
            },
            itemSelected(str) {
                this.selectedValue = str.name;
            },
            checkSelectedLocation(){
                if( this.selectedValue != '' ) {
                    this.successMsgAfterSubmit();
                }
            },
            successMsgAfterSubmit() {
                axios.get('/wp-json/cs/v1/get_locations_check_success_content/')
                    .then((response) => {
                        this.successMode = true;
                        this.modalContent = response.data;
                        this.addCookie();
                    });

            },
            addCookie() {
                this.$cookie.set('locations_popup', '1', 999);
            },
            hasCookie(cookie_name) {
                if( this.$cookie.get(cookie_name) ) {
                    return true;
                } else {
                    return false;
                }
            }
        },

    }
</script>

