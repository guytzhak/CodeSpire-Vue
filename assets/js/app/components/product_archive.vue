<template>
    <div class="product_actions">
        <div class="price">
            <span class="woocommerce-Price-currencySymbol">{{ currency_symbol }}</span>{{ product.display_price }}
        </div>
        <ul v-if="isObject(product_variations)">

            <li v-for="(variation, type) in product_variations">
                <label v-bind:class=" type == product.active_class ? 'active' : '' ">
                    <input type="radio" v-bind:name="product.product_id" @click="update_price(variation.price, variation.variation_id, variation.name, type )" v-bind:value="variation.variation_id"> {{ variation.name }}
                </label>
            </li>
        </ul>
        <div class="quantity_input">
            <button class="plus" @click.prevent="plusQty">+</button>
            <input type="number" name="quantity" id="quantity" class="quantity"
               v-model="product.quantity">
            <button class="minus" @click.prevent="minusQty">-</button>
        </div>
        <button class="cs_add_to_cart btn btn-primary" @click.prevent="add_to_cart" v-if="product.quantity > 0">הוסף לסל</button>
        <span class="choose_quantity btn btn-primary" v-if="product.quantity <= 0">בחר כמות</span>
    </div>
</template>

<script>
    import Bus from '../bus.js';

    export default {
        mounted() {
        },
        created() {
            this.product.product_id = this.product_id;
            this.product.variations = this.product_variations;

            if( typeof(this.product.variations) == 'object') {
                this.product.variations = this.product_variations;
                var default_variation_key = Object.keys(this.product.variations)[0];
                this.product.display_price = this.product.variations[default_variation_key].price;
                this.product.active_class = default_variation_key;
            } else {
                this.product.display_price = this.product_variations;
            }
        },
        props: ['product_id', 'product_variations', 'user_log_in'],
        data () {
            return {
                currency_symbol: cs_obj.currency_symbol,
                product: {
                    product_id: '',
                    quantity: '1',
                    variations: '',
                    display_price: '',
                    variation_id: '',
                    active_class: '',
                    attribute: {
                        product_type: ''
                    },
                },
            }
        },
        methods: {
            add_to_cart() {
                if( this.$cookie.get('locations_popup') == '1' ) {
                    axios.get('/wp-json/cs/v1/add_to_cart/?product_id=' + this.product.product_id + '&quantity=' + this.product.quantity + '&variation_id=' + this.product.variation_id + '&attribute=[product_type]=' + this.product.attribute.product_type)
                        .then((response) => {
                            //console.log(response);
                            if (response.data.add_to_cart == 'false') {
                                if (response.data.notices.length > 0) {
                                    var msg = response.data.notices;
                                    console.log(msg);
                                    Bus.$emit('popup', msg);
                                } else {

                                }
                            } else {
                                if (response.data.notices.length > 0) {
                                    var msg = response.data.notices,
                                        status = 1,
                                        popup_arr = {status: status, notices: msg};
                                    Bus.$emit('popup', popup_arr);
                                } else {

                                }
                            }
                            document.querySelectorAll('.widget_shopping_cart_content')[0].innerHTML = response.data.widget_shopping_cart_content;
                            document.querySelectorAll('.cart_count')[0].innerHTML = response.data.cart_count;

                        })
                        .catch((e) => {

                        });
                } else {
                    const status = 1,
                        popup_arrr = {status: status};
                    Bus.$emit('popup_check_location', popup_arrr);
                }
            },
            update_price(price, variation_id, attribute, attr_slug) {
                this.product.display_price = price;
                this.product.variation_id = variation_id;
                this.product.attribute.product_type = attribute;
                this.product.active_class = attr_slug;
                this.product.quantity = 1;
            },
            isObject(value) {
                if( typeof(value) == 'object' ) {
                    return true;
                } else {
                    return false;
                }
            },
            plusQty() {

                if( this.product.active_class == 'kg' ) {
                    this.product.quantity = (parseFloat(this.product.quantity) + parseFloat('0.1')).toFixed(1);
                } else {
                    this.product.quantity++;
                }

            },
            minusQty() {

                if( this.product.quantity > 0) {
                    if( this.product.active_class == 'kg' ) {
                        this.product.quantity = (parseFloat(this.product.quantity) -  parseFloat('0.1')).toFixed(1);
                    } else {
                        this.product.quantity--;
                    }
                }

            },
        },
    }
</script>